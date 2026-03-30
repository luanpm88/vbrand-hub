@extends('layouts.admin')

@section('title', 'Articles')

@section('breadcrumb')
    <li class="breadcrumb-item">Admin</li>
    <li class="breadcrumb-item active">Articles</li>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Articles</h1>
        <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> New Article
        </a>
    </div>

    {{-- Search & Filters --}}
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" action="{{ route('admin.articles.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Search title, excerpt..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="category" class="form-select form-select-sm">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="type" class="form-select form-select-sm">
                        <option value="">All Types</option>
                        <option value="tutorial" {{ request('type') == 'tutorial' ? 'selected' : '' }}>Tutorial</option>
                        <option value="guide" {{ request('type') == 'guide' ? 'selected' : '' }}>Guide</option>
                        <option value="reference" {{ request('type') == 'reference' ? 'selected' : '' }}>Reference</option>
                        <option value="comparison" {{ request('type') == 'comparison' ? 'selected' : '' }}>Comparison</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-funnel"></i> Filter</button>
                    @if(request()->hasAny(['search', 'category', 'status', 'type']))
                        <a href="{{ route('admin.articles.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    @if(request()->hasAny(['search', 'category', 'status', 'type']))
        <div class="mb-2 text-muted small">
            {{ $articles->total() }} article{{ $articles->total() !== 1 ? 's' : '' }} found
        </div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Type</th>
                        <th>Views</th>
                        <th>Date</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $article)
                        <tr>
                            <td>
                                <a href="{{ route('admin.articles.edit', $article) }}" class="fw-medium text-decoration-none">
                                    {{ $article->title }}
                                </a>
                            </td>
                            <td>
                                @if($article->category)
                                    <span class="badge" style="background-color: {{ $article->category->color ?? '#6c757d' }};">
                                        {{ $article->category->name }}
                                    </span>
                                @else
                                    <span class="text-muted">--</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusBg = match($article->status) {
                                        'published' => 'bg-success',
                                        'archived' => 'bg-danger',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $statusBg }}">{{ ucfirst($article->status) }}</span>
                            </td>
                            <td>
                                @if($article->content_type)
                                    {{ ucfirst($article->content_type) }}
                                @else
                                    <span class="text-muted">--</span>
                                @endif
                            </td>
                            <td>{{ number_format($article->views ?? 0) }}</td>
                            <td>
                                <small class="text-muted">
                                    {{ $article->published_at ? $article->published_at->format('M d, Y') : '--' }}
                                </small>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this article?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                @if(request()->hasAny(['search', 'category', 'status', 'type']))
                                    No articles match your filters. <a href="{{ route('admin.articles.index') }}">Clear filters</a>
                                @else
                                    No articles yet. <a href="{{ route('admin.articles.create') }}">Create your first article.</a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($articles->hasPages())
        <div class="mt-3">
            {{ $articles->links('pagination::bootstrap-5') }}
        </div>
    @endif
@endsection
