<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Services\TagService;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct(private TagService $tagService) {}

    public function index()
    {
        $tags = Tag::withCount('articles')->orderBy('name')->paginate(50);
        return view('admin.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.tags.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tags,slug',
        ]);

        $this->tagService->store($data);

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag created.');
    }

    public function edit(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:tags,slug,' . $tag->id,
        ]);

        $this->tagService->update($tag, $data);

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag updated.');
    }

    public function destroy(Tag $tag)
    {
        $this->tagService->delete($tag);

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag deleted.');
    }
}
