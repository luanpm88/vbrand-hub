@extends('layouts.kb')

@section('title', $tag->name . ' — Email Marketing Articles | AcelleMail KB')
@section('meta_description', 'Articles about ' . $tag->name . ' for email marketing. Tutorials, guides, and best practices on AcelleMail Knowledge Base.')
@section('og_title', $tag->name . ' — AcelleMail Knowledge Base')
@section('canonical_url', route('articles.tag', $tag->slug))

@section('content')
    {{-- Breadcrumbs --}}
    @include('partials.breadcrumbs', ['items' => [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => '#' . $tag->name, 'url' => '#'],
    ]])

    {{-- Tag Header --}}
    <div class="kb-search-header">
        <h1>
            <span class="kb-tag" style="font-size: 1rem; padding: 6px 16px; pointer-events: none;">{{ $tag->name }}</span>
        </h1>
        <p class="mt-8">{{ $articles->total() }} {{ Str::plural('article', $articles->total()) }} tagged with "{{ $tag->name }}"</p>
    </div>

    {{-- Articles --}}
    <div class="kb-grid-3">
        @forelse($articles as $article)
            @include('partials.article-card', ['article' => $article])
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 48px 0;">
                <p class="text-muted">No articles with this tag yet.</p>
                <a href="{{ route('home') }}" class="kb-btn kb-btn--secondary mt-16">Back to Home</a>
            </div>
        @endforelse
    </div>

    <div class="kb-pagination">
        {{ $articles->links() }}
    </div>
@endsection

@push('jsonld')
@include('partials.seo.jsonld-breadcrumb', ['breadcrumbItems' => [
    ['name' => 'Home', 'url' => route('home')],
    ['name' => $tag->name, 'url' => route('articles.tag', $tag->slug)],
]])
@endpush
