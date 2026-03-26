<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class);
    }

    public function scopePopular($query)
    {
        return $query->withCount('articles')->orderByDesc('articles_count');
    }

    public function getUrlAttribute(): string
    {
        return "/tag/{$this->slug}";
    }
}
