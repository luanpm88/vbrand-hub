@extends('layouts.admin')

@section('title', 'New Article')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.articles.index') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.articles.index') }}">Articles</a></li>
    <li class="breadcrumb-item active">New</li>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">New Article</h1>
    </div>

    <form action="{{ route('admin.articles.store') }}" method="POST" id="articleForm">
        @csrf

        <div class="row">
            {{-- Left column --}}
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" class="form-control" required placeholder="e.g. How to Install AcelleMail on Ubuntu 22.04">
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" id="slug" name="slug" value="{{ old('slug') }}" class="form-control" placeholder="auto-generated from title">
                        </div>

                        <div class="mb-3">
                            <label for="body_markdown" class="form-label">Body (Markdown)</label>
                            <textarea id="body_markdown" name="body_markdown" class="form-control">{{ old('body_markdown') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right column --}}
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header">Settings</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select id="category_id" name="category_id" class="form-select">
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="content_type" class="form-label">Content Type</label>
                            <select id="content_type" name="content_type" class="form-select">
                                <option value="">-- Select Type --</option>
                                <option value="tutorial" {{ old('content_type') === 'tutorial' ? 'selected' : '' }}>Tutorial</option>
                                <option value="guide" {{ old('content_type') === 'guide' ? 'selected' : '' }}>Guide</option>
                                <option value="reference" {{ old('content_type') === 'reference' ? 'selected' : '' }}>Reference</option>
                                <option value="comparison" {{ old('content_type') === 'comparison' ? 'selected' : '' }}>Comparison</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="difficulty" class="form-label">Difficulty</label>
                            <select id="difficulty" name="difficulty" class="form-select">
                                <option value="">-- Select Difficulty --</option>
                                <option value="beginner" {{ old('difficulty') === 'beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="intermediate" {{ old('difficulty') === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="advanced" {{ old('difficulty') === 'advanced' ? 'selected' : '' }}>Advanced</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-select">
                                <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived" {{ old('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="published_at" class="form-label">Published At</label>
                            <input type="datetime-local" id="published_at" name="published_at" value="{{ old('published_at') }}" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="excerpt" class="form-label">Excerpt</label>
                            <textarea id="excerpt" name="excerpt" class="form-control" rows="3" placeholder="Brief summary...">{{ old('excerpt') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tags --}}
        @if(isset($tags) && $tags->count() > 0)
            <div class="card mb-4">
                <div class="card-header">Tags</div>
                <div class="card-body">
                    <div class="row">
                        @foreach($tags as $tag)
                            <div class="col-md-4">
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" name="tags[]" value="{{ $tag->id }}" id="tag_{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tag_{{ $tag->id }}">{{ $tag->name }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- Meta fields --}}
        <div class="card mb-4">
            <div class="card-header">Meta Fields</div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="meta_title" class="form-label">Meta Title</label>
                    <input type="text" id="meta_title" name="meta_title" value="{{ old('meta_title') }}" class="form-control" placeholder="SEO title (defaults to article title)">
                </div>
                <div class="mb-3">
                    <label for="meta_description" class="form-label">Meta Description</label>
                    <textarea id="meta_description" name="meta_description" class="form-control" rows="2" placeholder="SEO description (defaults to excerpt)">{{ old('meta_description') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Submit buttons --}}
        <div class="d-flex gap-2 mb-4">
            <button type="submit" name="status" value="draft" class="btn btn-secondary">
                <i class="bi bi-file-earmark"></i> Save as Draft
            </button>
            <button type="submit" name="status" value="published" class="btn btn-primary">
                <i class="bi bi-check-circle"></i> Publish
            </button>
            <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
    <script>
        // Initialize EasyMDE
        const easyMDE = new EasyMDE({
            element: document.getElementById('body_markdown'),
            spellChecker: false,
            toolbar: [
                'bold', 'italic', 'heading', '|',
                'quote', 'code', '|',
                'unordered-list', 'ordered-list', '|',
                'link', 'image', 'table', 'horizontal-rule', '|',
                'preview', 'side-by-side', 'fullscreen', '|',
                'guide'
            ],
            minHeight: '400px',
        });

        // Auto-generate slug from title
        const titleInput = document.getElementById('title');
        const slugInput = document.getElementById('slug');

        titleInput.addEventListener('input', function() {
            if (!slugInput.value || slugInput.dataset.autoGenerated === 'true') {
                slugInput.value = this.value
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/-$/, '');
                slugInput.dataset.autoGenerated = 'true';
            }
        });

        slugInput.addEventListener('input', function() {
            this.dataset.autoGenerated = 'false';
        });
    </script>
@endpush
