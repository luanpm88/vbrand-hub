<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Models\Tag;
use App\Services\ArticleService;
use App\Services\CategoryService;
use App\Services\SearchService;

class ArticleController extends Controller
{
    public function __construct(
        private ArticleService $articleService,
        private CategoryService $categoryService,
        private SearchService $searchService,
    ) {}

    public function index(SearchRequest $request)
    {
        $filters = $request->validated();
        $articles = $this->articleService->getPublishedArticles($filters);
        $featuredArticles = empty($filters) || $request->input('page', 1) > 1
            ? collect() : $this->articleService->getFeaturedArticles(3);
        $categories = $this->categoryService->getWithArticleCounts();
        $popularTags = Tag::popular()->limit(20)->get();

        return view('articles.index', compact('articles', 'featuredArticles', 'categories', 'popularTags', 'filters'));
    }

    public function show(string $slug)
    {
        $article = $this->articleService->getArticleBySlug($slug);

        if (!$article) {
            abort(404);
        }

        $this->articleService->recordView($article);
        $toc = $this->articleService->generateToc($article->body_html);
        $categories = $this->categoryService->getWithArticleCounts();

        return view('articles.show', compact('article', 'toc', 'categories'));
    }

    public function category(string $slug)
    {
        $category = $this->categoryService->getBySlug($slug);

        if (!$category) {
            abort(404);
        }

        $articles = $this->articleService->getArticlesByCategory($slug);
        $categories = $this->categoryService->getWithArticleCounts();

        return view('articles.category', compact('category', 'articles', 'categories'));
    }

    public function tag(string $slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();
        $articles = $this->articleService->getArticlesByTag($slug);
        $categories = $this->categoryService->getWithArticleCounts();

        return view('articles.tag', compact('tag', 'articles', 'categories'));
    }

    public function search(SearchRequest $request)
    {
        $query = $request->input('q', '');
        $articles = $this->searchService->search($query);
        $categories = $this->categoryService->getWithArticleCounts();

        return view('articles.search', compact('query', 'articles', 'categories'));
    }
}
