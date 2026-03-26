{{-- Author Bio — receives $article --}}
@if($article->author_name)
<div class="kb-author">
    <div class="kb-author__avatar">
        @if($article->author_avatar)
            <img src="{{ asset('images/' . $article->author_avatar) }}" alt="{{ $article->author_name }}">
        @else
            {{ strtoupper(substr($article->author_name, 0, 1)) }}
        @endif
    </div>
    <div class="kb-author__info">
        <h4>{{ $article->author_name }}</h4>
        @if($article->author_bio)
            <p>{{ $article->author_bio }}</p>
        @endif
    </div>
</div>
@endif
