@extends('layouts.kb')

@section('title', $category->name . ' — AcelleMail KB')
@section('meta_description', $category->description ?: 'Browse ' . $category->name . ' articles on AcelleMail Knowledge Base.')

@section('content')
    {{-- Breadcrumbs --}}
    @include('partials.breadcrumbs', ['items' => [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => $category->name, 'url' => '#'],
    ]])

    {{-- Category Header --}}
    <div class="kb-category-header">
        <div class="kb-category-header__icon" style="background-color: {{ $category->color ?? '#E8571A' }}15;">
            <span class="kb-cat-card__dot" style="background: {{ $category->color ?? '#E8571A' }}; width: 20px; height: 20px;"></span>
        </div>
        <div class="kb-category-header__info">
            <h1>{{ $category->name }}</h1>
            @if($category->description)
                <p>{{ $category->description }}</p>
            @endif
        </div>
    </div>

    {{-- Articles --}}
    <div class="kb-grid-3">
        @forelse($articles as $article)
            @include('partials.article-card', ['article' => $article])
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 48px 0;">
                <p class="text-muted">No articles in this category yet.</p>
                <a href="{{ route('home') }}" class="kb-btn kb-btn--secondary mt-16">Back to Home</a>
            </div>
        @endforelse
    </div>

    <div class="kb-pagination">
        {{ $articles->links() }}
    </div>
@endsection
