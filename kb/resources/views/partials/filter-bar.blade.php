{{-- Filter Bar — receives $categories, $filters --}}
<form action="{{ url()->current() }}" method="GET" class="kb-filter-bar">
    <span class="kb-filter-bar__label">Filter:</span>

    {{-- Category --}}
    <select name="category" class="kb-select" onchange="this.form.submit()">
        <option value="">All Categories</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->slug }}" {{ ($filters['category'] ?? '') === $cat->slug ? 'selected' : '' }}>
                {{ $cat->name }}
            </option>
        @endforeach
    </select>

    {{-- Content Type --}}
    <select name="content_type" class="kb-select" onchange="this.form.submit()">
        <option value="">All Types</option>
        <option value="tutorial" {{ ($filters['content_type'] ?? '') === 'tutorial' ? 'selected' : '' }}>Tutorial</option>
        <option value="guide" {{ ($filters['content_type'] ?? '') === 'guide' ? 'selected' : '' }}>Guide</option>
        <option value="reference" {{ ($filters['content_type'] ?? '') === 'reference' ? 'selected' : '' }}>Reference</option>
        <option value="comparison" {{ ($filters['content_type'] ?? '') === 'comparison' ? 'selected' : '' }}>Comparison</option>
    </select>

    {{-- Sort --}}
    <select name="sort" class="kb-select" onchange="this.form.submit()">
        <option value="newest" {{ ($filters['sort'] ?? 'newest') === 'newest' ? 'selected' : '' }}>Newest First</option>
        <option value="oldest" {{ ($filters['sort'] ?? '') === 'oldest' ? 'selected' : '' }}>Oldest First</option>
        <option value="popular" {{ ($filters['sort'] ?? '') === 'popular' ? 'selected' : '' }}>Most Popular</option>
    </select>

    @if(!empty($filters['category']) || !empty($filters['content_type']) || ($filters['sort'] ?? 'newest') !== 'newest')
        <a href="{{ url()->current() }}" class="kb-btn kb-btn--secondary kb-btn--sm">Clear Filters</a>
    @endif
</form>
