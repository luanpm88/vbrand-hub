<?php

namespace App\Listeners;

use App\Events\ArticlePublished;
use Illuminate\Support\Facades\Cache;

class ClearCacheListener
{
    public function handle(ArticlePublished $event): void
    {
        Cache::forget('sitemap');
        Cache::forget('categories_with_counts');
        Cache::forget('featured_articles');
    }
}
