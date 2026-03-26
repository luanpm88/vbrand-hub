<?php

namespace App\DTOs;

use App\Models\Article;

class ArticleDTO
{
    public static function card(Article $article): array
    {
        return [
            'id' => $article->id,
            'title' => $article->title,
            'slug' => $article->slug,
            'url' => $article->url,
            'excerpt' => $article->excerpt,
            'featured_image' => $article->featured_image,
            'category' => $article->relationLoaded('category') && $article->category
                ? CategoryDTO::summary($article->category) : null,
            'tags' => $article->relationLoaded('tags')
                ? $article->tags->map(fn($t) => TagDTO::summary($t))->toArray() : [],
            'author_name' => $article->author_name,
            'reading_time' => $article->reading_time,
            'content_type' => $article->content_type,
            'difficulty' => $article->difficulty,
            'published_at' => $article->published_at?->format('M d, Y'),
            'views_count' => $article->views_count,
        ];
    }

    public static function detail(Article $article): array
    {
        return array_merge(self::card($article), [
            'body_html' => $article->body_html,
            'author_avatar' => $article->author_avatar,
            'author_bio' => $article->author_bio,
            'meta_title' => $article->meta_title ?: $article->title,
            'meta_description' => $article->meta_description ?: $article->excerpt,
            'related_articles' => $article->relationLoaded('relatedArticles')
                ? $article->relatedArticles->map(fn($a) => self::card($a))->toArray() : [],
        ]);
    }

    public static function admin(Article $article): array
    {
        return array_merge(self::card($article), [
            'status' => $article->status,
            'created_at' => $article->created_at?->format('Y-m-d H:i'),
            'updated_at' => $article->updated_at?->format('Y-m-d H:i'),
        ]);
    }
}
