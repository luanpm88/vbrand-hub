@extends('layouts.app')

@section('title', 'Manage Articles — AcelleMail KB Admin')

@section('content')
    <div class="flex justify-between items-center mb-32">
        <h1>Manage Articles</h1>
        <a href="{{ route('admin.articles.create') }}" class="kb-btn kb-btn--primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            New Article
        </a>
    </div>

    @if(session('success'))
        <div style="background: #dcfce7; border: 1px solid #bbf7d0; color: #166534; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; font-size: 0.9375rem;">
            {{ session('success') }}
        </div>
    @endif

    <table class="kb-admin-table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Type</th>
                <th>Status</th>
                <th>Published</th>
                <th style="width: 140px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($articles as $article)
                <tr>
                    <td>
                        <a href="{{ route('articles.show', $article->slug) }}" style="font-weight: 500; color: #241C15;">
                            {{ $article->title }}
                        </a>
                    </td>
                    <td>
                        @if($article->category)
                            <span class="kb-badge kb-badge--category" style="background-color: {{ $article->category->color ?? '#E8571A' }};">
                                {{ $article->category->name }}
                            </span>
                        @else
                            <span class="text-muted">--</span>
                        @endif
                    </td>
                    <td>
                        @if($article->content_type)
                            <span class="text-small">{{ ucfirst($article->content_type) }}</span>
                        @else
                            <span class="text-muted">--</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $statusClass = match($article->status) {
                                'published' => 'kb-badge--published',
                                'archived' => 'kb-badge--archived',
                                default => 'kb-badge--draft',
                            };
                        @endphp
                        <span class="kb-badge kb-badge--status {{ $statusClass }}">
                            {{ ucfirst($article->status) }}
                        </span>
                    </td>
                    <td>
                        <span class="text-small text-muted">
                            {{ $article->published_at ? $article->published_at->format('M d, Y') : '--' }}
                        </span>
                    </td>
                    <td>
                        <div class="kb-admin-table__actions">
                            <a href="{{ route('admin.articles.edit', $article) }}" class="kb-btn kb-btn--secondary kb-btn--sm">Edit</a>

                            <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this article?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="kb-btn kb-btn--danger kb-btn--sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 48px 16px; color: #6E6860;">
                        No articles yet. <a href="{{ route('admin.articles.create') }}">Create your first article.</a>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if(method_exists($articles, 'links'))
        <div class="kb-pagination">
            {{ $articles->links() }}
        </div>
    @endif
@endsection
