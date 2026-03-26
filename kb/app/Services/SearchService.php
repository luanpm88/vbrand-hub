<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SearchService
{
    public function search(string $query, int $perPage = 12): LengthAwarePaginator
    {
        $query = trim($query);
        if (empty($query)) {
            return Article::published()->with(['category', 'tags'])->latest('published_at')->paginate($perPage);
        }

        return Article::published()
            ->with(['category', 'tags'])
            ->where(function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('excerpt', 'LIKE', "%{$query}%")
                  ->orWhere('body_markdown', 'LIKE', "%{$query}%");
            })
            ->orderByRaw("CASE WHEN title LIKE ? THEN 1 WHEN excerpt LIKE ? THEN 2 ELSE 3 END", ["%{$query}%", "%{$query}%"])
            ->latest('published_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function suggest(string $query, int $limit = 5): Collection
    {
        return Article::published()
            ->where('title', 'LIKE', "%{$query}%")
            ->select('title', 'slug')
            ->limit($limit)
            ->get();
    }
}
