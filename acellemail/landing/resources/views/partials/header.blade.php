@php $currentPage = $currentPage ?? ''; @endphp

<header class="mc-header" id="mainHeader">
  <div class="mc-container">
    <div class="mc-header__inner">
      <div class="mc-header__left">
        <a href="{{ route('home') }}" class="mc-header__logo" aria-label="AcelleMail Home">
          <img src="{{ asset('images/logo_dark.svg') }}" alt="AcelleMail" width="160" height="32">
        </a>

        <nav class="mc-header__nav" aria-label="Main navigation">
          <a href="{{ route('home') }}" class="mc-header__nav-link{{ $currentPage === 'home' ? ' mc-header__nav-link--active' : '' }}">Home</a>
          <a href="{{ route('features') }}" class="mc-header__nav-link{{ $currentPage === 'features' ? ' mc-header__nav-link--active' : '' }}">Features</a>
          <a href="{{ route('email-marketing') }}" class="mc-header__nav-link{{ $currentPage === 'email-marketing' ? ' mc-header__nav-link--active' : '' }}">Email</a>
          <a href="{{ route('automation') }}" class="mc-header__nav-link{{ $currentPage === 'automation' ? ' mc-header__nav-link--active' : '' }}">Automation</a>
          <a href="{{ route('integrations') }}" class="mc-header__nav-link{{ $currentPage === 'integrations' ? ' mc-header__nav-link--active' : '' }}">Integrations</a>
          <a href="{{ route('pricing') }}" class="mc-header__nav-link{{ $currentPage === 'pricing' ? ' mc-header__nav-link--active' : '' }}">Pricing</a>
          <a href="{{ route('security') }}" class="mc-header__nav-link{{ $currentPage === 'security' ? ' mc-header__nav-link--active' : '' }}">Security</a>
          <a href="{{ route('about') }}" class="mc-header__nav-link{{ $currentPage === 'about' ? ' mc-header__nav-link--active' : '' }}">About</a>
          <a href="{{ route('help') }}" class="mc-header__nav-link{{ $currentPage === 'help' ? ' mc-header__nav-link--active' : '' }}">Help</a>
          <a href="{{ route('contact') }}" class="mc-header__nav-link{{ $currentPage === 'contact' ? ' mc-header__nav-link--active' : '' }}">Contact</a>
        </nav>
      </div>

      <div class="mc-header__right">
        <a href="https://demo.acellemail.com" class="mc-btn mc-btn--secondary mc-btn--sm mc-header__login" target="_blank">Try Demo</a>
        <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" class="mc-btn mc-btn--primary mc-header__cta" target="_blank">Buy Now</a>

        <button class="mc-header__hamburger" id="hamburgerBtn" aria-label="Open menu" aria-expanded="false">
          <span></span><span></span><span></span>
        </button>
      </div>
    </div>
  </div>
</header>
