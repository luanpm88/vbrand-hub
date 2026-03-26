<?php

namespace App\Services;

use App\Events\ArticlePublished;
use App\Events\ArticleViewed;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ArticleService
{
    public function __construct(
        private MarkdownService $markdownService,
    ) {}

    public function getPublishedArticles(array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        $query = Article::published()->with(['category', 'tags']);

        if (!empty($filters['category'])) {
            $category = Category::where('slug', $filters['category'])->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        if (!empty($filters['tag'])) {
            $query->whereHas('tags', fn($q) => $q->where('slug', $filters['tag']));
        }

        if (!empty($filters['content_type'])) {
            $query->where('content_type', $filters['content_type']);
        }

        if (!empty($filters['difficulty'])) {
            $query->where('difficulty', $filters['difficulty']);
        }

        $sort = $filters['sort'] ?? 'newest';
        $query = match ($sort) {
            'oldest' => $query->oldest('published_at'),
            'popular' => $query->orderByDesc('views_count'),
            default => $query->latest('published_at'),
        };

        return $query->paginate($perPage)->withQueryString();
    }

    public function getArticleBySlug(string $slug): ?Article
    {
        return Article::published()
            ->with(['category', 'tags', 'relatedArticles.category'])
            ->where('slug', $slug)
            ->first();
    }

    public function getFeaturedArticles(int $limit = 6): Collection
    {
        return Article::published()
            ->with(['category'])
            ->orderByDesc('views_count')
            ->latest('published_at')
            ->limit($limit)
            ->get();
    }

    public function getArticlesByCategory(string $categorySlug, int $perPage = 12): LengthAwarePaginator
    {
        return $this->getPublishedArticles(['category' => $categorySlug], $perPage);
    }

    public function getArticlesByTag(string $tagSlug, int $perPage = 12): LengthAwarePaginator
    {
        return $this->getPublishedArticles(['tag' => $tagSlug], $perPage);
    }

    public function storeArticle(array $data): Article
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $data['body_html'] = $this->markdownService->toHtml($data['body_markdown']);
        $data['reading_time'] = $this->markdownService->calculateReadingTime($data['body_markdown']);

        $article = Article::create($data);

        if (!empty($data['tags'])) {
            $article->tags()->sync($data['tags']);
        }

        if (!empty($data['related_articles'])) {
            $article->relatedArticles()->sync($data['related_articles']);
        }

        if ($article->status === Article::STATUS_PUBLISHED) {
            ArticlePublished::dispatch($article);
        }

        // Update category count
        $this->updateCategoryCount($article->category_id);

        return $article;
    }

    public function updateArticle(Article $article, array $data): Article
    {
        if (!empty($data['body_markdown'])) {
            $data['body_html'] = $this->markdownService->toHtml($data['body_markdown']);
            $data['reading_time'] = $this->markdownService->calculateReadingTime($data['body_markdown']);
        }

        $oldCategoryId = $article->category_id;
        $article->update($data);

        if (isset($data['tags'])) {
            $article->tags()->sync($data['tags']);
        }

        if (isset($data['related_articles'])) {
            $article->relatedArticles()->sync($data['related_articles']);
        }

        if ($article->status === Article::STATUS_PUBLISHED) {
            ArticlePublished::dispatch($article);
        }

        $this->updateCategoryCount($article->category_id);
        if ($oldCategoryId !== $article->category_id) {
            $this->updateCategoryCount($oldCategoryId);
        }

        return $article->fresh();
    }

    public function deleteArticle(Article $article): void
    {
        $categoryId = $article->category_id;
        $article->delete();
        $this->updateCategoryCount($categoryId);
    }

    public function recordView(Article $article): void
    {
        ArticleViewed::dispatch($article);
    }

    public function generateToc(string $html): array
    {
        return $this->markdownService->generateToc($html);
    }

    private function updateCategoryCount(int $categoryId): void
    {
        Category::where('id', $categoryId)->update([
            'articles_count' => Article::published()->where('category_id', $categoryId)->count(),
        ]);
    }
}
