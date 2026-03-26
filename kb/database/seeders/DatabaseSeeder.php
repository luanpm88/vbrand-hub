<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (class_exists(\Faker\Factory::class)) {
            User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@acellemail.com',
            ]);
        }

        $this->call([
            CategorySeeder::class,
            TagSeeder::class,
            ArticleSeeder::class,
        ]);
    }
}
