{{-- Breadcrumbs — receives $items array of [{label, url}] --}}
<nav class="kb-breadcrumbs" aria-label="Breadcrumb">
    @foreach($items as $i => $item)
        @if($i > 0)
            <span class="kb-breadcrumbs__sep">/</span>
        @endif

        @if($i === count($items) - 1)
            <span class="kb-breadcrumbs__current">{{ $item['label'] }}</span>
        @else
            <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
        @endif
    @endforeach
</nav>
