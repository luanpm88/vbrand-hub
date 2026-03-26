<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@acellemail.com',
            'password' => bcrypt('123456@'),
        ]);

        $this->call([
            CategorySeeder::class,
            TagSeeder::class,
            ArticleSeeder::class,
        ]);

        // Batch seeders (additional articles)
        if (class_exists(ArticleBatch1Seeder::class)) {
            $this->call(ArticleBatch1Seeder::class);
        }
        if (class_exists(ArticleBatch2Seeder::class)) {
            $this->call(ArticleBatch2Seeder::class);
        }
        if (class_exists(ArticleBatch3Seeder::class)) {
            $this->call(ArticleBatch3Seeder::class);
        }

        // Update category article counts
        foreach (\App\Models\Category::all() as $cat) {
            $cat->update(['articles_count' => $cat->articles()->where('status', 'published')->count()]);
        }
    }
}
