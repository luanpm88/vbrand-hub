{{-- Category Grid — receives $categories --}}
<div class="kb-grid-3">
    @foreach($categories as $category)
        <a href="{{ route('articles.category', $category->slug) }}" class="kb-cat-card">
            <div class="kb-cat-card__icon" style="background-color: {{ $category->color ?? '#E8571A' }}20;">
                <span>{{ $category->icon ?? '📁' }}</span>
            </div>
            <div class="kb-cat-card__info">
                <h3>{{ $category->name }}</h3>
                <span>{{ $category->articles_count ?? $category->articles()->count() }} {{ Str::plural('article', $category->articles_count ?? $category->articles()->count()) }}</span>
            </div>
        </a>
    @endforeach
</div>
