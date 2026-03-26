<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Article extends Model
{
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_ARCHIVED = 'archived';

    const TYPE_TUTORIAL = 'tutorial';
    const TYPE_GUIDE = 'guide';
    const TYPE_REFERENCE = 'reference';
    const TYPE_COMPARISON = 'comparison';

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'body_markdown',
        'body_html',
        'category_id',
        'author_name',
        'author_avatar',
        'author_bio',
        'featured_image',
        'status',
        'reading_time',
        'views_count',
        'content_type',
        'difficulty',
        'meta_title',
        'meta_description',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views_count' => 'integer',
        'reading_time' => 'integer',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Article $article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }

            if (empty($article->reading_time) && !empty($article->body_markdown)) {
                $wordCount = str_word_count(strip_tags($article->body_markdown));
                $article->reading_time = (int) ceil($wordCount / 200);
            }
        });

        static::updating(function (Article $article) {
            if ($article->isDirty('body_markdown') && !empty($article->body_markdown)) {
                $wordCount = str_word_count(strip_tags($article->body_markdown));
                $article->reading_time = (int) ceil($wordCount / 200);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function relatedArticles(): BelongsToMany
    {
        return $this->belongsToMany(
            Article::class,
            'article_related',
            'article_id',
            'related_article_id'
        )->withPivot('sort_order')->orderBy('sort_order');
    }

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED)
                     ->where('published_at', '<=', now());
    }

    public function scopeOfCategory($query, int $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeOfContentType($query, string $type)
    {
        return $query->where('content_type', $type);
    }

    public function scopeFeatured($query)
    {
        return $query->published()->orderByDesc('views_count');
    }

    public function getUrlAttribute(): string
    {
        return "/articles/{$this->slug}";
    }
}
