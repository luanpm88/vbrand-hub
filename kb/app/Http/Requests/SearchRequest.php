<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => 'nullable|string|max:255',
            'category' => 'nullable|string|exists:categories,slug',
            'tag' => 'nullable|string|exists:tags,slug',
            'content_type' => 'nullable|in:tutorial,guide,reference,comparison',
            'difficulty' => 'nullable|in:beginner,intermediate,advanced',
            'sort' => 'nullable|in:newest,oldest,popular',
            'page' => 'nullable|integer|min:1',
        ];
    }
}
