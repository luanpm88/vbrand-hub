<div class="mc-mobile-nav" id="mobileNav" aria-hidden="true">
  <button class="mc-mobile-nav__close" id="mobileNavClose" aria-label="Close menu">&times;</button>

  <nav class="mc-mobile-nav__links" aria-label="Mobile navigation">
    <a href="{{ route('home') }}" class="mc-mobile-nav__link">Home</a>
    <a href="{{ route('features') }}" class="mc-mobile-nav__link">Features</a>
    <a href="{{ route('email-marketing') }}" class="mc-mobile-nav__link">Email Marketing</a>
    <a href="{{ route('automation') }}" class="mc-mobile-nav__link">Automation</a>
    <a href="{{ route('integrations') }}" class="mc-mobile-nav__link">Integrations</a>
    <a href="{{ route('pricing') }}" class="mc-mobile-nav__link">Pricing</a>

    {{-- Resources group --}}
    <div class="mc-mobile-nav__group" data-mobile-group>
      <button class="mc-mobile-nav__group-trigger" aria-expanded="false">
        Resources
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 4 10 8 6 12"/></svg>
      </button>
      <div class="mc-mobile-nav__group-panel">
        <a href="https://knowledge.acellemail.com" class="mc-mobile-nav__group-link" target="_blank">Knowledge Base</a>
        <a href="{{ route('help') }}" class="mc-mobile-nav__group-link">Help</a>
        <a href="{{ route('security') }}" class="mc-mobile-nav__group-link">Security</a>
      </div>
    </div>

    {{-- Company group --}}
    <div class="mc-mobile-nav__group" data-mobile-group>
      <button class="mc-mobile-nav__group-trigger" aria-expanded="false">
        Company
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 4 10 8 6 12"/></svg>
      </button>
      <div class="mc-mobile-nav__group-panel">
        <a href="{{ route('about') }}" class="mc-mobile-nav__group-link">About</a>
        <a href="{{ route('contact') }}" class="mc-mobile-nav__group-link">Contact</a>
      </div>
    </div>
  </nav>

  <div class="mc-mobile-nav__actions">
    <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" class="mc-btn mc-btn--primary mc-btn--full" target="_blank">Download Acelle</a>
    <a href="https://demo.acellemail.com" class="mc-btn mc-btn--secondary mc-btn--full" target="_blank">Try Demo</a>
  </div>
</div>
