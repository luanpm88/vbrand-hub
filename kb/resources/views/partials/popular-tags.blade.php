{{-- Popular Tags — receives $tags --}}
<div class="kb-tags-section__list">
    @foreach($tags as $tag)
        <a href="{{ route('articles.tag', $tag->slug) }}" class="kb-tag">
            {{ $tag->name }}
        </a>
    @endforeach
</div>
