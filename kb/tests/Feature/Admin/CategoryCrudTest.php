<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);
    }

    public function test_index_shows_categories(): void
    {
        Category::create(['name' => 'Test Cat', 'slug' => 'test-cat', 'group' => 'resources', 'sort_order' => 1]);

        $this->actingAs($this->admin)
            ->get(route('admin.categories.index'))
            ->assertStatus(200)
            ->assertSee('Test Cat');
    }

    public function test_can_create_category(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.categories.store'), [
                'name' => 'New Category',
                'description' => 'A test category',
                'group' => 'getting-started',
                'sort_order' => 99,
            ])
            ->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('categories', ['name' => 'New Category', 'slug' => 'new-category']);
    }

    public function test_can_update_category(): void
    {
        $cat = Category::create(['name' => 'Old Name', 'slug' => 'old-name', 'group' => 'resources', 'sort_order' => 1]);

        $this->actingAs($this->admin)
            ->put(route('admin.categories.update', $cat), [
                'name' => 'New Name',
                'group' => 'advanced',
                'sort_order' => 5,
            ])
            ->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseHas('categories', ['id' => $cat->id, 'name' => 'New Name']);
    }

    public function test_can_delete_category(): void
    {
        $cat = Category::create(['name' => 'Delete Me', 'slug' => 'delete-me', 'group' => 'resources', 'sort_order' => 1]);

        $this->actingAs($this->admin)
            ->delete(route('admin.categories.destroy', $cat))
            ->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseMissing('categories', ['id' => $cat->id]);
    }

    public function test_validates_required_fields(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.categories.store'), [])
            ->assertSessionHasErrors(['name', 'group']);
    }
}
