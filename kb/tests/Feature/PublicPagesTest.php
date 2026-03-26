<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\CategorySeeder::class);
        $this->seed(\Database\Seeders\TagSeeder::class);

        $cat = Category::first();
        Article::create([
            'title' => 'Test Article',
            'slug' => 'test-article',
            'excerpt' => 'Test excerpt',
            'body_markdown' => '## Test',
            'body_html' => '<h2>Test</h2>',
            'category_id' => $cat->id,
            'status' => 'published',
            'content_type' => 'tutorial',
            'published_at' => now(),
        ]);
    }

    public function test_homepage_returns_200(): void
    {
        $this->get('/')->assertStatus(200);
    }

    public function test_article_page_returns_200(): void
    {
        $this->get('/articles/test-article')->assertStatus(200);
    }

    public function test_category_page_returns_200(): void
    {
        $cat = Category::first();
        $this->get("/category/{$cat->slug}")->assertStatus(200);
    }

    public function test_tag_page_returns_200(): void
    {
        $tag = Tag::first();
        $article = Article::first();
        $article->tags()->attach($tag->id);
        $this->get("/tag/{$tag->slug}")->assertStatus(200);
    }

    public function test_search_returns_200(): void
    {
        $this->get('/search?q=test')->assertStatus(200);
    }

    public function test_search_empty_query_returns_200(): void
    {
        $this->get('/search?q=&page=1')->assertStatus(200);
    }

    public function test_sitemap_returns_xml(): void
    {
        $this->get('/sitemap.xml')
            ->assertStatus(200)
            ->assertHeader('Content-Type', 'application/xml');
    }

    public function test_nonexistent_article_returns_404(): void
    {
        $this->get('/articles/nonexistent-slug')->assertStatus(404);
    }

    public function test_filter_by_content_type(): void
    {
        $this->get('/?content_type=tutorial')->assertStatus(200);
    }

    public function test_filter_by_category(): void
    {
        $cat = Category::first();
        $this->get("/?category={$cat->slug}")->assertStatus(200);
    }
}
