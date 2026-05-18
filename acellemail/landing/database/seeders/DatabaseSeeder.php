<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * KB content (acellemail.com/kb/*) lives in seeders — edit, then re-seed.
     * - `php artisan migrate:fresh --seed`         rebuilds everything from scratch
     * - `php artisan db:seed --class=ArticleBatch3Seeder`  reseeds one batch
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            TagSeeder::class,
            ArticleSeeder::class,
            ArticleBatch1Seeder::class,
            ArticleBatch2Seeder::class,
            ArticleBatch3Seeder::class,
            // Enrichment waves — each upserts by slug, so they layer over the
            // base batches above. New waves should be added below in order.
            Wave1EnrichSeeder::class,
            Wave2EnrichSeeder::class,
            Wave3EnrichSeeder::class,
            Wave4EnrichSeeder::class,
            Wave5EnrichSeeder::class,
            Wave6EnrichSeeder::class,
            Wave7EnrichSeeder::class,
            Wave8EnrichSeeder::class,
            Wave9EnrichSeeder::class,
            Wave10EnrichSeeder::class,
            Wave11EnrichSeeder::class,
            Wave12EnrichSeeder::class,
            Wave13EnrichSeeder::class,
            Wave14EnrichSeeder::class,
            Wave15EnrichSeeder::class,
            Wave16EnrichSeeder::class,
            Wave17EnrichSeeder::class,
            Wave18EnrichSeeder::class,
            Wave19EnrichSeeder::class,
            Wave20EnrichSeeder::class,
            Wave21EnrichSeeder::class,
            Wave22EnrichSeeder::class,
            Wave23EnrichSeeder::class,
            Wave24EnrichSeeder::class,
            Wave25EnrichSeeder::class,
            Wave26EnrichSeeder::class,
            Wave27EnrichSeeder::class,
            Wave28EnrichSeeder::class,
            Wave29EnrichSeeder::class,
            Wave30EnrichSeeder::class,
            Wave31EnrichSeeder::class,
            Wave32EnrichSeeder::class,
            Wave33EnrichSeeder::class,
            Wave34EnrichSeeder::class,
            Wave35EnrichSeeder::class,
            Wave36EnrichSeeder::class,
            Wave37EnrichSeeder::class,
            Wave38EnrichSeeder::class,
            Wave39EnrichSeeder::class,
            Wave40EnrichSeeder::class,
            Wave41EnrichSeeder::class,
            Wave42EnrichSeeder::class,
            Wave43EnrichSeeder::class,
            Wave44EnrichSeeder::class,
            Wave45EnrichSeeder::class,
            Wave46EnrichSeeder::class,
            Wave47EnrichSeeder::class,
            Wave48EnrichSeeder::class,
            Wave49EnrichSeeder::class,
            Wave50EnrichSeeder::class,
            Wave51EnrichSeeder::class,
            Wave52EnrichSeeder::class,
            Wave53EnrichSeeder::class,
            Wave54EnrichSeeder::class,
            Wave55EnrichSeeder::class,
            Wave56EnrichSeeder::class,
            Wave57EnrichSeeder::class,
            Wave58EnrichSeeder::class,
            Wave59EnrichSeeder::class,
            Wave60EnrichSeeder::class,
            Wave61EnrichSeeder::class,
            Wave62EnrichSeeder::class,
            Wave63EnrichSeeder::class,
            Wave64EnrichSeeder::class,
            Wave65EnrichSeeder::class,
            Wave66EnrichSeeder::class,
            Wave67EnrichSeeder::class,
            Wave68EnrichSeeder::class,
            Wave69EnrichSeeder::class,
            Wave70EnrichSeeder::class,
        ]);

        // Refresh denormalized articles_count on every category.
        foreach (\App\Models\Category::all() as $cat) {
            $cat->update([
                'articles_count' => $cat->articles()->where('status', 'published')->count(),
            ]);
        }
    }
}
