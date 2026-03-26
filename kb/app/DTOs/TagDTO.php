<?php

namespace App\DTOs;

use App\Models\Tag;

class TagDTO
{
    public static function summary(Tag $tag): array
    {
        return [
            'id' => $tag->id,
            'name' => $tag->name,
            'slug' => $tag->slug,
            'url' => $tag->url,
        ];
    }
}
