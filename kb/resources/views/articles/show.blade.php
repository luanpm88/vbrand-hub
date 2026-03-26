@extends('layouts.kb')

@section('title', ($article->meta_title ?: $article->title) . ' — AcelleMail KB')
@section('meta_description', $article->meta_description ?: $article->excerpt)

@section('content')
    {{-- Breadcrumbs --}}
    @include('partials.breadcrumbs', ['items' => [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => $article->category->name ?? 'Uncategorized', 'url' => $article->category ? route('articles.category', $article->category->slug) : route('home')],
        ['label' => $article->title, 'url' => '#'],
    ]])

    {{-- Article Header --}}
    <div class="kb-article-header">
        @if($article->category)
            <a href="{{ route('articles.category', $article->category->slug) }}">
                <span class="kb-badge kb-badge--category" style="background-color: {{ $article->category->color ?? '#E8571A' }};">
                    {{ $article->category->name }}
                </span>
            </a>
        @endif

        <h1>{{ $article->title }}</h1>

        <div class="kb-article-header__meta">
            @if($article->published_at)
                <span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    {{ $article->published_at->format('F d, Y') }}
                </span>
            @endif

            @if($article->reading_time)
                <span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    {{ $article->reading_time }} min read
                </span>
            @endif

            @if($article->views_count)
                <span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    {{ number_format($article->views_count) }} views
                </span>
            @endif

            @if($article->content_type)
                <span>
                    {{ ucfirst($article->content_type) }}
                </span>
            @endif
        </div>
    </div>

    {{-- Two-column layout: Article body + TOC sidebar --}}
    <div class="kb-article-layout">
        <div class="kb-article-layout__content">
            {{-- Article Body --}}
            <div class="kb-prose">
                {!! $article->body_html !!}
            </div>

            {{-- Tags --}}
            @if($article->tags->count() > 0)
                <div class="kb-tags-section">
                    <h4 class="kb-tags-section__title">Tags</h4>
                    <div class="kb-tags-section__list">
                        @foreach($article->tags as $tag)
                            <a href="{{ route('articles.tag', $tag->slug) }}" class="kb-tag">{{ $tag->name }}</a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Author Bio --}}
            @include('partials.author-bio', ['article' => $article])

            {{-- Related Articles --}}
            @if(isset($relatedArticles) && $relatedArticles->count() > 0)
                <div class="kb-section mt-48">
                    <div class="kb-section__header">
                        <h2 class="kb-section__title">Related Articles</h2>
                    </div>
                    <div class="kb-grid-3">
                        @foreach($relatedArticles as $related)
                            @include('partials.article-card', ['article' => $related])
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- TOC Sidebar --}}
        <aside class="kb-article-layout__sidebar">
            @include('partials.toc', ['toc' => $toc ?? []])
        </aside>
    </div>
@endsection

@push('scripts')
<script>
    // Re-run Prism highlighting after page load
    if (typeof Prism !== 'undefined') {
        Prism.highlightAll();
    }
</script>
@endpush
