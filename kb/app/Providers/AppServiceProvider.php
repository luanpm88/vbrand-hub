<?php

namespace App\Providers;

use App\Events\ArticlePublished;
use App\Events\ArticleViewed;
use App\Listeners\ClearCacheListener;
use App\Listeners\IncrementViewsListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Event::listen(ArticlePublished::class, ClearCacheListener::class);
        Event::listen(ArticleViewed::class, IncrementViewsListener::class);
    }
}
