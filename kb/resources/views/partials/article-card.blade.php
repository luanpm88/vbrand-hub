{{-- Article Card — receives $article (Eloquent model with category, tags loaded) --}}
<article class="kb-card">
    @if($article->category)
        <a href="{{ route('articles.category', $article->category->slug) }}">
            <span class="kb-badge kb-badge--category" style="background-color: {{ $article->category->color ?? '#E8571A' }};">
                {{ $article->category->name }}
            </span>
        </a>
    @endif

    <h3 class="kb-card__title" style="margin-top: 10px;">
        <a href="{{ route('articles.show', $article->slug) }}">
            {{ $article->title }}
        </a>
    </h3>

    @if($article->excerpt)
        <p class="kb-card__excerpt">{{ $article->excerpt }}</p>
    @endif

    <div class="kb-card__meta">
        @if($article->content_type)
            <span>{{ ucfirst($article->content_type) }}</span>
            <span class="kb-card__meta-sep"></span>
        @endif

        @if($article->reading_time)
            <span>{{ $article->reading_time }} min read</span>
            <span class="kb-card__meta-sep"></span>
        @endif

        @if($article->published_at)
            <span>{{ $article->published_at->format('M d, Y') }}</span>
        @endif
    </div>
</article>
