<?php

namespace App\DTOs;

use App\Models\Category;

class CategoryDTO
{
    public static function summary(Category $category): array
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'url' => $category->url,
            'icon' => $category->icon,
            'color' => $category->color,
            'description' => $category->description,
            'articles_count' => $category->articles_count,
        ];
    }
}
