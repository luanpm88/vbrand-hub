{{-- Mobile nav overlay — mirrors the desktop IA (8 top-level + 2 CTAs).
     Features accordion contains the 6 platform-section jump-links pointing
     at /features#anchor; Email/Automation/Integrations are flat siblings
     so non-developer visitors find them at first glance. Developers is last
     so the dev-flavoured language doesn't dominate the menu. --}}
<div class="mc-mobile-nav" id="mobileNav" aria-hidden="true">
  <button class="mc-mobile-nav__close" id="mobileNavClose" aria-label="Close menu">&times;</button>

  <nav class="mc-mobile-nav__links" aria-label="Mobile navigation">

    {{-- Home — re-added per user request, sits first --}}
    <a href="{{ route('home') }}" class="mc-mobile-nav__link">Home</a>

    {{-- Features accordion — 6 jump-links into /features sections --}}
    <div class="mc-mobile-nav__group" data-mobile-group>
      <button class="mc-mobile-nav__group-trigger" aria-expanded="false">
        Features
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 4 10 8 6 12"/></svg>
      </button>
      <div class="mc-mobile-nav__group-panel"><div class="mc-mobile-nav__group-panel-inner">
        <a href="{{ route('features') }}" class="mc-mobile-nav__group-link">
          <span class="mc-mobile-nav__group-link-name">All features overview</span>
          <span class="mc-mobile-nav__group-link-desc">Everything AcelleMail does &mdash; one page</span>
        </a>
        <a href="{{ route('features') }}#email-campaigns" class="mc-mobile-nav__group-link">
          <span class="mc-mobile-nav__group-link-name">Email campaigns</span>
          <span class="mc-mobile-nav__group-link-desc">Drag-and-drop, A/B testing, templates, scheduling</span>
        </a>
        <a href="{{ route('features') }}#automation-features" class="mc-mobile-nav__group-link">
          <span class="mc-mobile-nav__group-link-name">Marketing automation</span>
          <span class="mc-mobile-nav__group-link-desc">Triggers, drip series, behavioral targeting</span>
        </a>
        <a href="{{ route('features') }}#lists" class="mc-mobile-nav__group-link">
          <span class="mc-mobile-nav__group-link-name">List management</span>
          <span class="mc-mobile-nav__group-link-desc">Import, segmentation, tags, sign-up forms</span>
        </a>
        <a href="{{ route('features') }}#deliverability" class="mc-mobile-nav__group-link">
          <span class="mc-mobile-nav__group-link-name">Deliverability &amp; analytics</span>
          <span class="mc-mobile-nav__group-link-desc">SPF / DKIM / DMARC, IP warmup, click maps</span>
        </a>
        <a href="{{ route('features') }}#integrations-features" class="mc-mobile-nav__group-link">
          <span class="mc-mobile-nav__group-link-name">Sending services &amp; integrations</span>
          <span class="mc-mobile-nav__group-link-desc">Amazon SES, SendGrid, REST API, WordPress</span>
        </a>
        <a href="{{ route('features') }}#saas-platform" class="mc-mobile-nav__group-link">
          <span class="mc-mobile-nav__group-link-name">SaaS &amp; multi-tenancy</span>
          <span class="mc-mobile-nav__group-link-desc">Multi-tenant, billing, white-label</span>
        </a>
        <a href="{{ route('aurius') }}" class="mc-mobile-nav__group-link">
          <span class="mc-mobile-nav__group-link-name">Aurius 4.0 &mdash; AI plugin</span>
          <span class="mc-mobile-nav__group-link-desc">Install the Acelle AI plugin to add chatbox, sparkle, coach personas, observability. Aurius powers it. Not built into Acelle core.</span>
        </a>
      </div></div>
    </div>

    {{-- Flat top-level: Integrations · Pricing
         (Email + Automation moved INTO Features accordion above) --}}
    <a href="{{ route('integrations') }}" class="mc-mobile-nav__link">Integrations</a>
    <a href="{{ route('pricing') }}" class="mc-mobile-nav__link">Pricing</a>

    {{-- Resources slim --}}
    <div class="mc-mobile-nav__group" data-mobile-group>
      <button class="mc-mobile-nav__group-trigger" aria-expanded="false">
        Resources
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 4 10 8 6 12"/></svg>
      </button>
      <div class="mc-mobile-nav__group-panel"><div class="mc-mobile-nav__group-panel-inner">
        <a href="{{ route('blog.index') }}" class="mc-mobile-nav__group-link">Blog</a>
        <a href="{{ route('help') }}" class="mc-mobile-nav__group-link">Help</a>
        <a href="{{ route('security') }}" class="mc-mobile-nav__group-link">Security &amp; GDPR</a>
        <a href="{{ route('glossary.index') }}" class="mc-mobile-nav__group-link">Glossary</a>
        <a href="{{ route('compare.show', ['slug' => 'mailchimp']) }}" class="mc-mobile-nav__group-link">Mailchimp Comparison</a>
        <a href="{{ route('compare.show', ['slug' => 'listmonk']) }}" class="mc-mobile-nav__group-link">listmonk Comparison</a>
        <a href="{{ route('compare.show', ['slug' => 'mautic']) }}" class="mc-mobile-nav__group-link">Mautic Comparison</a>
        <a href="{{ route('compare.show', ['slug' => 'sendgrid']) }}" class="mc-mobile-nav__group-link">SendGrid Comparison</a>
        <a href="{{ route('compare.show', ['slug' => 'brevo']) }}" class="mc-mobile-nav__group-link">Brevo Comparison</a>
        <a href="{{ route('compare.show', ['slug' => 'klaviyo']) }}" class="mc-mobile-nav__group-link">Klaviyo Comparison</a>
        <a href="/kb" class="mc-mobile-nav__group-link">Knowledge Base &nearr;</a>
      </div></div>
    </div>

    {{-- Company slim --}}
    <div class="mc-mobile-nav__group" data-mobile-group>
      <button class="mc-mobile-nav__group-trigger" aria-expanded="false">
        Company
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 4 10 8 6 12"/></svg>
      </button>
      <div class="mc-mobile-nav__group-panel"><div class="mc-mobile-nav__group-panel-inner">
        <a href="{{ route('about') }}" class="mc-mobile-nav__group-link">About</a>
        <a href="{{ route('contact') }}" class="mc-mobile-nav__group-link">Contact</a>
      </div></div>
    </div>

    {{-- Developers — last (deprioritized for non-dev users) --}}
    <div class="mc-mobile-nav__group" data-mobile-group>
      <button class="mc-mobile-nav__group-trigger" aria-expanded="false">
        Developers
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 4 10 8 6 12"/></svg>
      </button>
      <div class="mc-mobile-nav__group-panel"><div class="mc-mobile-nav__group-panel-inner">
        <a href="{{ route('for.developers') }}" class="mc-mobile-nav__group-link">
          <span class="mc-mobile-nav__group-link-name">Developer overview</span>
          <span class="mc-mobile-nav__group-link-desc">Plugin SDK, Hook system, REST API landing</span>
        </a>
        <a href="{{ route('developers.index') }}" class="mc-mobile-nav__group-link">
          <span class="mc-mobile-nav__group-link-name">Plugin Documentation</span>
          <span class="mc-mobile-nav__group-link-desc">11-page deep-dive reference, source-grounded</span>
        </a>
        <a href="{{ route('developers.getting-started') }}" class="mc-mobile-nav__group-link mc-mobile-nav__group-link--sub">Getting started</a>
        <a href="{{ route('developers.plugin-architecture') }}" class="mc-mobile-nav__group-link mc-mobile-nav__group-link--sub">Plugin architecture</a>
        <a href="{{ route('developers.hook-system') }}" class="mc-mobile-nav__group-link mc-mobile-nav__group-link--sub">Hook system &mdash; 4 patterns</a>
        <a href="{{ route('developers.ui-injection') }}" class="mc-mobile-nav__group-link mc-mobile-nav__group-link--sub">UI injection</a>
        <a href="{{ route('developers.database-models') }}" class="mc-mobile-nav__group-link mc-mobile-nav__group-link--sub">Database &amp; models</a>
        <a href="{{ route('developers.translations') }}" class="mc-mobile-nav__group-link mc-mobile-nav__group-link--sub">Translations</a>
        <a href="{{ route('developers.lifecycle') }}" class="mc-mobile-nav__group-link mc-mobile-nav__group-link--sub">Plugin lifecycle</a>
        <a href="{{ route('developers.testing') }}" class="mc-mobile-nav__group-link mc-mobile-nav__group-link--sub">Testing</a>
        <a href="{{ route('developers.sending-drivers') }}" class="mc-mobile-nav__group-link mc-mobile-nav__group-link--sub">Sending drivers</a>
        <a href="{{ route('developers.payment-gateways') }}" class="mc-mobile-nav__group-link mc-mobile-nav__group-link--sub">Payment gateways</a>
        <a href="{{ route('developers.showcase') }}" class="mc-mobile-nav__group-link mc-mobile-nav__group-link--sub">Plugin showcase</a>
        <a href="{{ route('api') }}" class="mc-mobile-nav__group-link">
          <span class="mc-mobile-nav__group-link-name">REST API reference</span>
          <span class="mc-mobile-nav__group-link-desc">Token auth, 8 resources, webhooks</span>
        </a>
        <a href="/kb" class="mc-mobile-nav__group-link">
          <span class="mc-mobile-nav__group-link-name">Knowledge Base &nearr;</span>
          <span class="mc-mobile-nav__group-link-desc">Operations, setup, deliverability</span>
        </a>
      </div>
    </div>

  </nav>

  <div class="mc-mobile-nav__actions">
    @auth
      @php
        $authUser = auth()->user();
        $authColor = \App\Support\Avatar::color($authUser);
        $authInitials = \App\Support\Avatar::initials($authUser);
        $authGreeting = \App\Support\Avatar::greeting($authUser);
      @endphp
      <div class="mc-mobile-nav__auth">
        <div class="mc-mobile-nav__auth-greeting">
          <span class="mc-topbar__user-avatar" style="background: {{ $authColor }};">{{ $authInitials }}</span>
          <span>Signed in as <strong>{{ $authGreeting }}</strong></span>
        </div>
        <form method="POST" action="{{ route('auth.logout') }}">
          @csrf
          <button type="submit" class="mc-btn mc-btn--secondary mc-btn--full" data-testid="mobile-logout">Sign out</button>
        </form>
      </div>
    @else
      <div class="mc-mobile-nav__auth">
        <a href="{{ route('auth.login') }}" class="mc-btn mc-btn--secondary mc-btn--full" data-testid="mobile-signin"
           data-auth-modal-open data-auth-tab="login">Sign in</a>
        <a href="{{ route('auth.register') }}" class="mc-mobile-nav__link" style="text-align:center; padding-top:8px;" data-testid="mobile-signup"
           data-auth-modal-open data-auth-tab="register">Create an account</a>
      </div>
    @endauth
    <a href="https://codecanyon.net/cart/add_items?item_ids=17796082" class="mc-btn mc-btn--primary mc-btn--full">Get AcelleMail — $74</a>
    <a href="{{ route('demo') }}" class="mc-btn mc-btn--secondary mc-btn--full">Try Demo</a>
  </div>
</div>
