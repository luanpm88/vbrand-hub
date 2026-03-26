@extends('layouts.admin')

@section('title', 'Categories')

@section('breadcrumb')
    <li class="breadcrumb-item">Admin</li>
    <li class="breadcrumb-item active">Categories</li>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Categories</h1>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> New Category
        </a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">Color</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Group</th>
                        <th>Articles</th>
                        <th>Sort Order</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>
                                <div style="width: 24px; height: 24px; border-radius: 4px; background-color: {{ $category->color ?? '#6c757d' }};" title="{{ $category->color }}"></div>
                            </td>
                            <td class="fw-medium">
                                @if($category->icon)
                                    <i class="bi bi-{{ $category->icon }} me-1"></i>
                                @endif
                                {{ $category->name }}
                            </td>
                            <td><code>{{ $category->slug }}</code></td>
                            <td>
                                @if($category->group)
                                    <span class="badge bg-info">{{ $category->group }}</span>
                                @else
                                    <span class="text-muted">--</span>
                                @endif
                            </td>
                            <td>{{ $category->articles_count ?? $category->articles->count() }}</td>
                            <td>{{ $category->sort_order ?? 0 }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this category?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                No categories yet. <a href="{{ route('admin.categories.create') }}">Create your first category.</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
