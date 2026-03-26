<header class="kb-header">
    <div class="kb-container">
        <div class="kb-header__inner">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="kb-header__logo">
                <span class="kb-header__logo-icon">A</span>
                AcelleMail KB
            </a>

            {{-- Navigation --}}
            <nav class="kb-header__nav">
                <a href="{{ route('home') }}" class="kb-header__link {{ request()->routeIs('home') ? 'kb-header__link--active' : '' }}">Home</a>

                <div class="kb-header__dropdown">
                    <button class="kb-header__dropdown-toggle" type="button">
                        Categories
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <div class="kb-header__dropdown-menu">
                        @php
                            $headerCategories = \App\Models\Category::ordered()->get();
                        @endphp
                        @foreach($headerCategories as $cat)
                            <a href="{{ route('articles.category', $cat->slug) }}" class="kb-header__dropdown-item">
                                {{ $cat->icon ?? '' }} {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('articles.search') }}" class="kb-header__link {{ request()->routeIs('articles.search') ? 'kb-header__link--active' : '' }}">Search</a>
            </nav>

            {{-- Search --}}
            <form action="{{ route('articles.search') }}" method="GET" class="kb-header__search">
                <svg class="kb-header__search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input
                    type="text"
                    name="q"
                    class="kb-header__search-input"
                    placeholder="Search articles..."
                    value="{{ request('q') }}"
                    autocomplete="off"
                >
            </form>

            {{-- Mobile toggle --}}
            <button class="kb-header__mobile-toggle" type="button" aria-label="Toggle menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
        </div>
    </div>
</header>
