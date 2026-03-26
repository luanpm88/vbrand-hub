<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryService
{
    public function getAllOrdered(): Collection
    {
        return Category::ordered()->get();
    }

    public function getWithArticleCounts(): Collection
    {
        return Category::ordered()->where('articles_count', '>', 0)->get();
    }

    public function getBySlug(string $slug): ?Category
    {
        return Category::where('slug', $slug)->first();
    }
}
