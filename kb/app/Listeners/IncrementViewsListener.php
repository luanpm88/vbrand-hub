<?php

namespace App\Listeners;

use App\Events\ArticleViewed;
use App\Models\Article;

class IncrementViewsListener
{
    public function handle(ArticleViewed $event): void
    {
        $sessionKey = 'viewed_article_' . $event->article->id;
        $lastViewed = session($sessionKey);

        if (!$lastViewed || now()->diffInMinutes($lastViewed) >= 30) {
            Article::where('id', $event->article->id)->increment('views_count');
            session([$sessionKey => now()]);
        }
    }
}
