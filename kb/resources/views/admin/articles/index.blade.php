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
                                No articles yet. <a href="{{ route('admin.articles.create') }}">Create your first article.</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($articles->hasPages())
        <div class="mt-3">
            {{ $articles->links() }}
        </div>
    @endif
@endsection
