@php $currentPage = $currentPage ?? ''; @endphp

<header class="mc-header" id="mainHeader">
  <div class="mc-container">
    <div class="mc-header__inner">
      <div class="mc-header__left">
        <a href="{{ route('home') }}" class="mc-header__logo" aria-label="AcelleMail Home">
          <img src="{{ asset('images/logo_dark.svg') }}" alt="AcelleMail" width="160" height="32">
        </a>
      </div>

      <nav class="mc-header__nav" aria-label="Main navigation">
        <a href="{{ route('home') }}" class="mc-header__nav-link{{ $currentPage === 'home' ? ' mc-header__nav-link--active' : '' }}">Home</a>
        <a href="{{ route('features') }}" class="mc-header__nav-link{{ $currentPage === 'features' ? ' mc-header__nav-link--active' : '' }}">Features</a>
        <a href="{{ route('email-marketing') }}" class="mc-header__nav-link{{ $currentPage === 'email-marketing' ? ' mc-header__nav-link--active' : '' }}">Email</a>
        <a href="{{ route('automation') }}" class="mc-header__nav-link{{ $currentPage === 'automation' ? ' mc-header__nav-link--active' : '' }}">Automation</a>
        <a href="{{ route('integrations') }}" class="mc-header__nav-link{{ $currentPage === 'integrations' ? ' mc-header__nav-link--active' : '' }}">Integrations</a>
        <a href="{{ route('pricing') }}" class="mc-header__nav-link{{ $currentPage === 'pricing' ? ' mc-header__nav-link--active' : '' }}">Pricing</a>

        {{-- Resources dropdown --}}
        <div class="mc-header__dropdown" data-dropdown>
          <button class="mc-header__dropdown-trigger{{ in_array($currentPage, ['security','help']) ? ' mc-header__dropdown-trigger--active' : '' }}" aria-expanded="false" aria-haspopup="true">
            Resources
            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="4 6 8 10 12 6"/></svg>
          </button>
          <div class="mc-header__dropdown-panel" role="menu">
            <a href="https://knowledge.acellemail.com" class="mc-header__dropdown-item" role="menuitem" target="_blank">Knowledge Base</a>
            <a href="{{ route('help') }}" class="mc-header__dropdown-item{{ $currentPage === 'help' ? ' mc-header__dropdown-item--active' : '' }}" role="menuitem">Help</a>
            <a href="{{ route('security') }}" class="mc-header__dropdown-item{{ $currentPage === 'security' ? ' mc-header__dropdown-item--active' : '' }}" role="menuitem">Security</a>
          </div>
        </div>

        {{-- Company dropdown --}}
        <div class="mc-header__dropdown" data-dropdown>
          <button class="mc-header__dropdown-trigger{{ in_array($currentPage, ['about','contact']) ? ' mc-header__dropdown-trigger--active' : '' }}" aria-expanded="false" aria-haspopup="true">
            Company
            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="4 6 8 10 12 6"/></svg>
          </button>
          <div class="mc-header__dropdown-panel" role="menu">
            <a href="{{ route('about') }}" class="mc-header__dropdown-item{{ $currentPage === 'about' ? ' mc-header__dropdown-item--active' : '' }}" role="menuitem">About</a>
            <a href="{{ route('contact') }}" class="mc-header__dropdown-item{{ $currentPage === 'contact' ? ' mc-header__dropdown-item--active' : '' }}" role="menuitem">Contact</a>
          </div>
        </div>
      </nav>

      <div class="mc-header__right">
        <a href="https://demo.acellemail.com" class="mc-btn mc-btn--secondary mc-btn--sm mc-header__login" target="_blank">Try Demo</a>
        <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" class="mc-btn mc-btn--primary mc-header__cta" target="_blank">Download Acelle</a>

        <button class="mc-header__hamburger" id="hamburgerBtn" aria-label="Open menu" aria-expanded="false">
          <span></span><span></span><span></span>
        </button>
      </div>
    </div>
  </div>
</header>
