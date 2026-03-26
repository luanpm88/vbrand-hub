<?php

namespace App\Services;

use App\Models\Tag;
use Illuminate\Support\Str;

class TagService
{
    public function store(array $data): Tag
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        return Tag::create($data);
    }

    public function update(Tag $tag, array $data): Tag
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        $tag->update($data);
        return $tag->fresh();
    }

    public function delete(Tag $tag): void
    {
        $tag->articles()->detach();
        $tag->delete();
    }
}
