<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Services\ArticleService;

class ArticleController extends Controller
{
    public function __construct(private ArticleService $articleService) {}

    public function index()
    {
        $query = Article::with('category');

        // Keyword search
        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($categoryId = request('category')) {
            $query->where('category_id', $categoryId);
        }

        // Status filter
        if ($status = request('status')) {
            $query->where('status', $status);
        }

        // Content type filter
        if ($type = request('type')) {
            $query->where('content_type', $type);
        }

        $articles = $query->latest('updated_at')
            ->paginate(20)
            ->withQueryString();

        $categories = Category::ordered()->get();

        return view('admin.articles.index', compact('articles', 'categories'));
    }

    public function create()
    {
        $categories = Category::ordered()->get();
        $tags = Tag::orderBy('name')->get();

        return view('admin.articles.create', compact('categories', 'tags'));
    }

    public function store(StoreArticleRequest $request)
    {
        $article = $this->articleService->storeArticle($request->validated());

        return redirect()->route('admin.articles.edit', $article)
            ->with('success', 'Article created.');
    }

    public function edit(Article $article)
    {
        $categories = Category::ordered()->get();
        $tags = Tag::orderBy('name')->get();
        $article->load('tags', 'relatedArticles');

        return view('admin.articles.edit', compact('article', 'categories', 'tags'));
    }

    public function update(UpdateArticleRequest $request, Article $article)
    {
        $this->articleService->updateArticle($article, $request->validated());

        return redirect()->route('admin.articles.edit', $article)
            ->with('success', 'Article updated.');
    }

    public function destroy(Article $article)
    {
        $this->articleService->deleteArticle($article);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article deleted.');
    }

    public function preview(Article $article)
    {
        $article->load('category', 'tags');
        $toc = $this->articleService->generateToc($article->body_html);
        return view('articles.show', [
            'article' => $article,
            'toc' => $toc,
            'categories' => Category::ordered()->where('articles_count', '>', 0)->get(),
        ]);
    }
}
