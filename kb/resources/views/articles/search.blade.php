@extends('layouts.kb')

@section('title', ($query ?? '') ? '"' . $query . '" — Search — AcelleMail KB' : 'Search — AcelleMail KB')

@section('content')
    {{-- Search Form --}}
    <div class="kb-search-header">
        <h1>Search Articles</h1>
        @if(!empty($query))
            <p>{{ $articles->total() }} {{ Str::plural('result', $articles->total()) }} for "{{ $query }}"</p>
        @else
            <p>Find tutorials, guides, and references.</p>
        @endif
    </div>

    <form action="{{ route('articles.search') }}" method="GET" class="kb-search-form">
        <svg class="kb-search-form__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
        <input type="text" name="q" value="{{ $query ?? '' }}" placeholder="Search tutorials, guides, references..." autofocus autocomplete="off">
    </form>

    {{-- Results --}}
    @if(isset($articles))
        <div class="kb-grid-3">
            @forelse($articles as $article)
                @include('partials.article-card', ['article' => $article])
            @empty
                @if(!empty($query))
                    <div style="grid-column: 1 / -1; text-align: center; padding: 48px 0;">
                        <p class="text-muted" style="font-size: 1.125rem; margin-bottom: 8px;">No results found for "{{ $query }}"</p>
                        <p class="text-muted text-small">Try different keywords or browse by category.</p>
                        <a href="{{ route('home') }}" class="kb-btn kb-btn--secondary mt-24">Browse All Articles</a>
                    </div>
                @endif
            @endforelse
        </div>

        @if($articles->count() > 0)
            <div class="kb-pagination">
                {{ $articles->appends(['q' => $query])->links() }}
            </div>
        @endif
    @endif
@endsection
