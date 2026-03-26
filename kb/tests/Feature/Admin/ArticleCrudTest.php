<?php

namespace Tests\Feature\Admin;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\CategorySeeder::class);
        $this->seed(\Database\Seeders\TagSeeder::class);

        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_index_shows_articles(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.articles.index'))
            ->assertStatus(200);
    }

    public function test_create_page_loads(): void
    {
        $this->actingAs($this->admin)
            ->get(route('admin.articles.create'))
            ->assertStatus(200);
    }

    public function test_can_store_article(): void
    {
        $category = Category::first();

        $this->actingAs($this->admin)
            ->post(route('admin.articles.store'), [
                'title' => 'Test Article Title',
                'body_markdown' => '## Hello World\n\nThis is a test article.',
                'category_id' => $category->id,
                'status' => 'draft',
                'content_type' => 'tutorial',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('articles', ['title' => 'Test Article Title']);
    }

    public function test_store_validates_required_fields(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.articles.store'), [])
            ->assertSessionHasErrors(['title', 'body_markdown', 'category_id', 'status', 'content_type']);
    }

    public function test_can_edit_article(): void
    {
        $article = Article::create([
            'title' => 'Edit Me',
            'slug' => 'edit-me',
            'body_markdown' => '## Test',
            'body_html' => '<h2>Test</h2>',
            'category_id' => Category::first()->id,
            'status' => 'draft',
            'content_type' => 'tutorial',
        ]);

        $this->actingAs($this->admin)
            ->get(route('admin.articles.edit', $article))
            ->assertStatus(200)
            ->assertSee('Edit Me');
    }

    public function test_can_update_article(): void
    {
        $article = Article::create([
            'title' => 'Original Title',
            'slug' => 'original-title',
            'body_markdown' => '## Test',
            'body_html' => '<h2>Test</h2>',
            'category_id' => Category::first()->id,
            'status' => 'draft',
            'content_type' => 'tutorial',
        ]);

        $this->actingAs($this->admin)
            ->put(route('admin.articles.update', $article), [
                'title' => 'Updated Title',
                'body_markdown' => '## Updated',
                'category_id' => Category::first()->id,
                'status' => 'published',
                'content_type' => 'guide',
                'published_at' => now()->toDateTimeString(),
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('articles', ['title' => 'Updated Title', 'status' => 'published']);
    }

    public function test_can_delete_article(): void
    {
        $article = Article::create([
            'title' => 'Delete Me',
            'slug' => 'delete-me',
            'body_markdown' => '## Test',
            'body_html' => '<h2>Test</h2>',
            'category_id' => Category::first()->id,
            'status' => 'draft',
            'content_type' => 'tutorial',
        ]);

        $this->actingAs($this->admin)
            ->delete(route('admin.articles.destroy', $article))
            ->assertRedirect(route('admin.articles.index'));

        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }

    public function test_article_tags_are_synced(): void
    {
        $tag = Tag::first();

        $this->actingAs($this->admin)
            ->post(route('admin.articles.store'), [
                'title' => 'Tagged Article',
                'body_markdown' => '## Test',
                'category_id' => Category::first()->id,
                'status' => 'draft',
                'content_type' => 'tutorial',
                'tags' => [$tag->id],
            ]);

        $article = Article::where('title', 'Tagged Article')->first();
        $this->assertTrue($article->tags->contains($tag));
    }
}
