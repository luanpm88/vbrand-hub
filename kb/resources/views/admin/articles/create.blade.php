@extends('layouts.app')

@section('title', 'Create Article — AcelleMail KB Admin')

@section('content')
    <div class="mb-32">
        <a href="{{ route('admin.articles.index') }}" class="text-muted text-small" style="display: inline-flex; align-items: center; gap: 4px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
            Back to articles
        </a>
        <h1 class="mt-8">Create New Article</h1>
    </div>

    @if($errors->any())
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; font-size: 0.9375rem;">
            <ul style="list-style: disc; padding-left: 20px; margin: 0;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.articles.store') }}" method="POST" style="max-width: 800px;">
        @csrf

        <div class="kb-form-group">
            <label for="title" class="kb-label">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" class="kb-input" required placeholder="e.g. How to Install AcelleMail on Ubuntu 22.04">
        </div>

        <div class="kb-form-group">
            <label for="slug" class="kb-label">Slug</label>
            <input type="text" id="slug" name="slug" value="{{ old('slug') }}" class="kb-input" placeholder="auto-generated from title if left empty">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="kb-form-group">
                <label for="category_id" class="kb-label">Category</label>
                <select id="category_id" name="category_id" class="kb-select">
                    <option value="">-- Select Category --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="kb-form-group">
                <label for="content_type" class="kb-label">Content Type</label>
                <select id="content_type" name="content_type" class="kb-select">
                    <option value="">-- Select Type --</option>
                    <option value="tutorial" {{ old('content_type') === 'tutorial' ? 'selected' : '' }}>Tutorial</option>
                    <option value="guide" {{ old('content_type') === 'guide' ? 'selected' : '' }}>Guide</option>
                    <option value="reference" {{ old('content_type') === 'reference' ? 'selected' : '' }}>Reference</option>
                    <option value="comparison" {{ old('content_type') === 'comparison' ? 'selected' : '' }}>Comparison</option>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div class="kb-form-group">
                <label for="difficulty" class="kb-label">Difficulty</label>
                <select id="difficulty" name="difficulty" class="kb-select">
                    <option value="">-- Select Difficulty --</option>
                    <option value="beginner" {{ old('difficulty') === 'beginner' ? 'selected' : '' }}>Beginner</option>
                    <option value="intermediate" {{ old('difficulty') === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                    <option value="advanced" {{ old('difficulty') === 'advanced' ? 'selected' : '' }}>Advanced</option>
                </select>
            </div>

            <div class="kb-form-group">
                <label for="status" class="kb-label">Status</label>
                <select id="status" name="status" class="kb-select">
                    <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
        </div>

        <div class="kb-form-group">
            <label for="excerpt" class="kb-label">Excerpt</label>
            <textarea id="excerpt" name="excerpt" class="kb-textarea" rows="3" placeholder="Brief summary of the article (shown in cards and search results)...">{{ old('excerpt') }}</textarea>
        </div>

        <div class="kb-form-group">
            <label for="body_markdown" class="kb-label">Body (Markdown)</label>
            <textarea id="body_markdown" name="body_markdown" class="kb-textarea" rows="20" style="min-height: 400px; font-family: 'IBM Plex Mono', 'Fira Code', monospace; font-size: 0.875rem; line-height: 1.6;" placeholder="Write your article content in Markdown...">{{ old('body_markdown') }}</textarea>
        </div>

        {{-- Tags --}}
        @if(isset($tags) && $tags->count() > 0)
            <div class="kb-form-group">
                <label class="kb-label">Tags</label>
                <div class="kb-checkbox-group">
                    @foreach($tags as $tag)
                        <label>
                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                            {{ $tag->name }}
                        </label>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="kb-form-group">
            <label for="published_at" class="kb-label">Publish Date</label>
            <input type="datetime-local" id="published_at" name="published_at" value="{{ old('published_at') }}" class="kb-input" style="max-width: 300px;">
        </div>

        <div style="display: flex; gap: 12px; margin-top: 32px; padding-top: 24px; border-top: 1px solid #E5E0DA;">
            <button type="submit" class="kb-btn kb-btn--primary">Create Article</button>
            <a href="{{ route('admin.articles.index') }}" class="kb-btn kb-btn--secondary">Cancel</a>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    // Auto-generate slug from title
    document.getElementById('title').addEventListener('input', function() {
        const slugInput = document.getElementById('slug');
        if (!slugInput.value || slugInput.dataset.autoGenerated === 'true') {
            slugInput.value = this.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');
            slugInput.dataset.autoGenerated = 'true';
        }
    });

    document.getElementById('slug').addEventListener('input', function() {
        this.dataset.autoGenerated = 'false';
    });
</script>
@endpush
