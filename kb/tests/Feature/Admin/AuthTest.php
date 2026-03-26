<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_returns_200(): void
    {
        $this->get('/login')->assertStatus(200);
    }

    public function test_guest_cannot_access_admin(): void
    {
        $this->get('/admin/articles')->assertRedirect('/login');
    }

    public function test_guest_cannot_access_admin_categories(): void
    {
        $this->get('/admin/categories')->assertRedirect('/login');
    }

    public function test_guest_cannot_access_admin_tags(): void
    {
        $this->get('/admin/tags')->assertRedirect('/login');
    }

    public function test_admin_can_login(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);

        $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password',
        ])->assertRedirectContains('/');
    }

    public function test_admin_can_access_articles_after_login(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user)->get('/admin/articles')->assertStatus(200);
    }

    public function test_admin_can_logout(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user)->post('/logout')->assertRedirect('/');
    }
}
