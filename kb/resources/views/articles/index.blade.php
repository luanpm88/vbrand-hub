@extends('layouts.app')

@section('title', 'AcelleMail Knowledge Base — Tutorials, Guides & References')
@section('meta_description', 'Learn everything about AcelleMail — self-hosted email marketing. Tutorials, guides, references, and best practices.')

@section('content')
    {{-- Hero Section --}}
    <div class="kb-hero">
        <h1>AcelleMail Knowledge Base</h1>
        <p>Tutorials, guides, and references for building and managing your self-hosted email marketing platform.</p>

        <form action="{{ route('articles.search') }}" method="GET" class="kb-hero__search">
            <svg class="kb-hero__search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" name="q" placeholder="Search tutorials, guides, references..." autocomplete="off">
        </form>
    </div>

    {{-- Categories --}}
    <div class="kb-section">
        <div class="kb-section__header">
            <h2 class="kb-section__title">Browse by Category</h2>
        </div>

        @include('partials.category-grid', ['categories' => $categories])
    </div>

    {{-- Filter Bar --}}
    @include('partials.filter-bar', ['categories' => $categories, 'filters' => $filters])

    <div class="kb-sidebar-layout">
        <div class="kb-sidebar-layout__main">
            {{-- Featured Articles (page 1 only) --}}
            @if($featuredArticles->count() > 0 && $articles->currentPage() === 1)
                <div class="kb-section">
                    <div class="kb-section__header">
                        <h2 class="kb-section__title">Featured</h2>
                    </div>
                    <div class="kb-grid-3">
                        @foreach($featuredArticles as $article)
                            @include('partials.article-card', ['article' => $article])
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- All Articles --}}
            <div class="kb-section">
                <div class="kb-section__header">
                    <h2 class="kb-section__title">All Articles</h2>
                    <span class="text-muted text-small">{{ $articles->total() }} {{ Str::plural('article', $articles->total()) }}</span>
                </div>
                <div class="kb-grid-3">
                    @forelse($articles as $article)
                        @include('partials.article-card', ['article' => $article])
                    @empty
                        <div style="grid-column: 1 / -1; text-align: center; padding: 48px 0;">
                            <p class="text-muted">No articles found matching your filters.</p>
                            <a href="{{ route('home') }}" class="kb-btn kb-btn--secondary mt-16">Clear Filters</a>
                        </div>
                    @endforelse
                </div>

                <div class="kb-pagination">
                    {{ $articles->links() }}
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <aside class="kb-sidebar-layout__aside">
            {{-- Categories with counts --}}
            <div class="kb-widget">
                <h4 class="kb-widget__title">Categories</h4>
                <ul class="kb-widget__list">
                    @foreach($categories as $cat)
                        <li>
                            <a href="{{ route('articles.category', $cat->slug) }}">
                                {{ $cat->name }}
                                <span class="count">{{ $cat->articles_count ?? $cat->articles()->count() }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Popular Tags --}}
            @if(isset($popularTags) && $popularTags->count() > 0)
                <div class="kb-widget">
                    <h4 class="kb-widget__title">Popular Tags</h4>
                    @include('partials.popular-tags', ['tags' => $popularTags])
                </div>
            @endif
        </aside>
    </div>
@endsection
