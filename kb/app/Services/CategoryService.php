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

    public function store(array $data): Category
    {
        if (empty($data['slug'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
        }
        return Category::create($data);
    }

    public function update(Category $category, array $data): Category
    {
        if (empty($data['slug'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
        }
        $category->update($data);
        return $category->fresh();
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }
}
