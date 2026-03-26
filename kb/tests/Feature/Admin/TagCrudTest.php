<?php

namespace Tests\Feature\Admin;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TagCrudTest extends TestCase
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

    public function test_index_shows_tags(): void
    {
        Tag::create(['name' => 'Test Tag', 'slug' => 'test-tag']);

        $this->actingAs($this->admin)
            ->get(route('admin.tags.index'))
            ->assertStatus(200)
            ->assertSee('Test Tag');
    }

    public function test_can_create_tag(): void
    {
        $this->actingAs($this->admin)
            ->post(route('admin.tags.store'), ['name' => 'New Tag'])
            ->assertRedirect(route('admin.tags.index'));

        $this->assertDatabaseHas('tags', ['name' => 'New Tag', 'slug' => 'new-tag']);
    }

    public function test_can_update_tag(): void
    {
        $tag = Tag::create(['name' => 'Old', 'slug' => 'old']);

        $this->actingAs($this->admin)
            ->put(route('admin.tags.update', $tag), ['name' => 'Updated'])
            ->assertRedirect(route('admin.tags.index'));

        $this->assertDatabaseHas('tags', ['id' => $tag->id, 'name' => 'Updated']);
    }

    public function test_can_delete_tag(): void
    {
        $tag = Tag::create(['name' => 'Delete', 'slug' => 'delete']);

        $this->actingAs($this->admin)
            ->delete(route('admin.tags.destroy', $tag))
            ->assertRedirect(route('admin.tags.index'));

        $this->assertDatabaseMissing('tags', ['id' => $tag->id]);
    }
}
