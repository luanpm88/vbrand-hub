@extends('layouts.admin')

@section('title', 'Tags')

@section('breadcrumb')
    <li class="breadcrumb-item">Admin</li>
    <li class="breadcrumb-item active">Tags</li>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Tags</h1>
        <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> New Tag
        </a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Articles</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tags as $tag)
                        <tr>
                            <td class="fw-medium">{{ $tag->name }}</td>
                            <td><code>{{ $tag->slug }}</code></td>
                            <td>{{ $tag->articles_count ?? $tag->articles->count() }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.tags.edit', $tag) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this tag?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                No tags yet. <a href="{{ route('admin.tags.create') }}">Create your first tag.</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($tags->hasPages())
        <div class="mt-3">
            {{ $tags->links('pagination::bootstrap-5') }}
        </div>
    @endif
@endsection
