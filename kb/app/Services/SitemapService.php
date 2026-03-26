<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Support\Facades\Cache;

class SitemapService
{
    public function generate(): string
    {
        return Cache::remember('sitemap', 86400, function () {
            $xml = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

            // Homepage
            $xml .= $this->url(url('/'), now()->toW3cString(), '1.0', 'daily');

            // Articles
            foreach (Article::published()->latest('published_at')->get() as $article) {
                $xml .= $this->url(
                    url("/articles/{$article->slug}"),
                    $article->updated_at->toW3cString(),
                    '0.8',
                    'weekly'
                );
            }

            // Categories
            foreach (Category::ordered()->get() as $category) {
                $xml .= $this->url(
                    url("/category/{$category->slug}"),
                    $category->updated_at->toW3cString(),
                    '0.6',
                    'weekly'
                );
            }

            // Tags
            foreach (Tag::has('articles')->get() as $tag) {
                $xml .= $this->url(
                    url("/tag/{$tag->slug}"),
                    $tag->updated_at->toW3cString(),
                    '0.4',
                    'monthly'
                );
            }

            $xml .= '</urlset>';
            return $xml;
        });
    }

    private function url(string $loc, string $lastmod, string $priority, string $changefreq): string
    {
        return "<url><loc>{$loc}</loc><lastmod>{$lastmod}</lastmod><priority>{$priority}</priority><changefreq>{$changefreq}</changefreq></url>";
    }
}
