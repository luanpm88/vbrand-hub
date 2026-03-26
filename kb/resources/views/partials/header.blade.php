<header class="kb-header">
    <div class="kb-container">
        <div class="kb-header__inner">
            <a href="{{ route('home') }}" class="kb-header__logo">
                <img src="{{ asset('images/kb-icon.svg') }}" alt="" width="28" height="28" class="kb-header__logo-icon">
                <img src="{{ asset('images/logo_dark.svg') }}" alt="AcelleMail" height="22" class="kb-header__logo-wordmark">
            </a>

            <nav class="kb-header__nav">
                <a href="{{ route('home') }}" class="kb-header__link {{ request()->routeIs('home') ? 'kb-header__link--active' : '' }}">Home</a>

                <div class="kb-header__dropdown">
                    <button class="kb-header__dropdown-toggle" type="button">
                        Categories
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
                    </button>
                    <div class="kb-header__dropdown-menu">
                        @php $headerCategories = \App\Models\Category::ordered()->get(); @endphp

                        @php
                            $groups = [
                                'getting-started' => 'Getting Started',
                                'infrastructure' => 'Infrastructure',
                                'advanced' => 'Advanced',
                                'resources' => 'Resources',
                            ];
                        @endphp
                        @foreach($groups as $groupSlug => $groupLabel)
                            <div class="kb-header__dropdown-group">
                                <span class="kb-header__dropdown-label">{{ $groupLabel }}</span>
                                @foreach($headerCategories->where('group', $groupSlug) as $cat)
                                    <a href="{{ route('articles.category', $cat->slug) }}" class="kb-header__dropdown-item">{{ $cat->name }}</a>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('articles.search') }}" class="kb-header__link {{ request()->routeIs('articles.search') ? 'kb-header__link--active' : '' }}">Search</a>
                <a href="https://acellemail.com" class="kb-header__link" target="_blank">AcelleMail</a>
            </nav>

            <form action="{{ route('articles.search') }}" method="GET" class="kb-header__search">
                <svg class="kb-header__search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" name="q" class="kb-header__search-input" placeholder="Search articles..." value="{{ request('q') }}" autocomplete="off">
            </form>

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
