{{-- Mobile nav overlay — mirrors the desktop IA (8 top-level + 2 CTAs).
     Features accordion contains the 6 platform-section jump-links pointing
     at /features#anchor; Email/Automation/Integrations are flat siblings
     so non-developer visitors find them at first glance. Developers is last
     so the dev-flavoured language doesn't dominate the menu. --}}
<div class="mc-mobile-nav" id="mobileNav" aria-hidden="true">
  <button class="mc-mobile-nav__close" id="mobileNavClose" aria-label="{{ __('nav.aria_close_menu') }}">&times;</button>

  <nav class="mc-mobile-nav__links" aria-label="{{ __('nav.aria_mobile') }}">

    {{-- Home — re-added per user request, sits first --}}
    <a href="@lroute('home')" class="mc-mobile-nav__link">{{ __('nav.home') }}</a>

    {{-- Features accordion — 6 jump-links into /features sections --}}
    <div class="mc-mobile-nav__group" data-mobile-group>
      <button class="mc-mobile-nav__group-trigger" aria-expanded="false">
        {{ __('nav.features') }}
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 4 10 8 6 12"/></svg>
      </button>
      <div class="mc-mobile-nav__group-panel"><div class="mc-mobile-nav__group-panel-inner">
        <a href="@lroute('features')" class="mc-mobile-nav__group-link">
          <span class="mc-mobile-nav__group-link-name">{{ __('nav.features_mega.all') }}</span>
          <span class="mc-mobile-nav__group-link-desc">{{ __('nav.features_mega.all_desc') }}</span>
        </a>
        <a href="@lroute('features')#email-campaigns" class="mc-mobile-nav__group-link">
          <span class="mc-mobile-nav__group-link-name">{{ __('nav.features_mega.email') }}</span>
          <span class="mc-mobile-nav__group-link-desc">{{ __('nav.features_mega.email_desc') }}</span>
        </a>
        <a href="@lroute('features')#automation-features" class="mc-mobile-nav__group-link">
          <span class="mc-mobile-nav__group-link-name">{{ __('nav.features_mega.auto') }}</span>
          <span class="mc-mobile-nav__group-link-desc">{{ __('nav.features_mega.auto_desc') }}</span>
        </a>
        <a href="@lroute('features')#lists" class="mc-mobile-nav__group-link">
          <span class="mc-mobile-nav__group-link-name">{{ __('nav.features_mega.lists') }}</span>
          <span class="mc-mobile-nav__group-link-desc">{{ __('nav.features_mega.lists_desc') }}</span>
        </a>
        <a href="@lroute('features')#deliverability" class="mc-mobile-nav__group-link">
          <span class="mc-mobile-nav__group-link-name">{{ __('nav.features_mega.deliv') }}</span>
          <span class="mc-mobile-nav__group-link-desc">{{ __('nav.features_mega.deliv_desc') }}</span>
        </a>
        <a href="@lroute('features')#integrations-features" class="mc-mobile-nav__group-link">
          <span class="mc-mobile-nav__group-link-name">{{ __('nav.features_mega.integ') }}</span>
          <span class="mc-mobile-nav__group-link-desc">{{ __('nav.features_mega.integ_desc') }}</span>
        </a>
        <a href="@lroute('features')#saas-platform" class="mc-mobile-nav__group-link">
          <span class="mc-mobile-nav__group-link-name">{{ __('nav.features_mega.saas') }}</span>
          <span class="mc-mobile-nav__group-link-desc">{{ __('nav.features_mega.saas_desc') }}</span>
        </a>
        <a href="@lroute('aurius')" class="mc-mobile-nav__group-link">
          <span class="mc-mobile-nav__group-link-name">{{ __('nav.features_mega.aurius_name') }}</span>
          <span class="mc-mobile-nav__group-link-desc">{{ __('nav.features_mega.aurius_mobile_desc') }}</span>
        </a>
      </div></div>
    </div>

    {{-- Flat top-level: Integrations · Pricing
         (Email + Automation moved INTO Features accordion above) --}}
    <a href="@lroute('integrations')" class="mc-mobile-nav__link">{{ __('nav.integrations') }}</a>
    <a href="@lroute('pricing')" class="mc-mobile-nav__link">{{ __('nav.pricing') }}</a>

    {{-- Resources slim --}}
    <div class="mc-mobile-nav__group" data-mobile-group>
      <button class="mc-mobile-nav__group-trigger" aria-expanded="false">
        {{ __('nav.resources') }}
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 4 10 8 6 12"/></svg>
      </button>
      <div class="mc-mobile-nav__group-panel"><div class="mc-mobile-nav__group-panel-inner">
        <a href="@lroute('blog.index')" class="mc-mobile-nav__group-link">{{ __('nav.blog') }}</a>
        <a href="@lroute('help')" class="mc-mobile-nav__group-link">{{ __('nav.help') }}</a>
        <a href="@lroute('security')" class="mc-mobile-nav__group-link">{{ __('nav.security') }}</a>
        <a href="@lroute('glossary.index')" class="mc-mobile-nav__group-link">{{ __('nav.glossary') }}</a>
        <a href="@lroute('compare.show', ['slug' => 'mailchimp'])" class="mc-mobile-nav__group-link">{{ __('nav.compare.mailchimp') }}</a>
        <a href="@lroute('compare.show', ['slug' => 'listmonk'])" class="mc-mobile-nav__group-link">{{ __('nav.compare.listmonk') }}</a>
        <a href="@lroute('compare.show', ['slug' => 'mautic'])" class="mc-mobile-nav__group-link">{{ __('nav.compare.mautic') }}</a>
        <a href="@lroute('compare.show', ['slug' => 'sendgrid'])" class="mc-mobile-nav__group-link">{{ __('nav.compare.sendgrid') }}</a>
        <a href="@lroute('compare.show', ['slug' => 'brevo'])" class="mc-mobile-nav__group-link">{{ __('nav.compare.brevo') }}</a>
        <a href="@lroute('compare.show', ['slug' => 'klaviyo'])" class="mc-mobile-nav__group-link">{{ __('nav.compare.klaviyo') }}</a>
        <a href="@lroute('kb.index')" class="mc-mobile-nav__group-link">{{ __('nav.kb_external') }}</a>
      </div></div>
    </div>

    {{-- Company slim --}}
    <div class="mc-mobile-nav__group" data-mobile-group>
      <button class="mc-mobile-nav__group-trigger" aria-expanded="false">
        {{ __('nav.company') }}
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 4 10 8 6 12"/></svg>
      </button>
      <div class="mc-mobile-nav__group-panel"><div class="mc-mobile-nav__group-panel-inner">
        <a href="@lroute('about')" class="mc-mobile-nav__group-link">{{ __('nav.about') }}</a>
        <a href="@lroute('contact')" class="mc-mobile-nav__group-link">{{ __('nav.contact') }}</a>
      </div></div>
    </div>

    {{-- Developers — last (deprioritized for non-dev users) --}}
    <div class="mc-mobile-nav__group" data-mobile-group>
      <button class="mc-mobile-nav__group-trigger" aria-expanded="false">
        {{ __('nav.developers') }}
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 4 10 8 6 12"/></svg>
      </button>
      <div class="mc-mobile-nav__group-panel"><div class="mc-mobile-nav__group-panel-inner">
        <a href="@lroute('for.developers')" class="mc-mobile-nav__group-link">
          <span class="mc-mobile-nav__group-link-name">{{ __('nav.developers_mega.overview') }}</span>
          <span class="mc-mobile-nav__group-link-desc">{{ __('nav.mobile_developers.overview_mobile_desc') }}</span>
        </a>
        <a href="@lroute('developers.index')" class="mc-mobile-nav__group-link">
          <span class="mc-mobile-nav__group-link-name">{{ __('nav.developers_mega.docs') }}</span>
          <span class="mc-mobile-nav__group-link-desc">{{ __('nav.developers_mega.docs_desc') }}</span>
        </a>
        <a href="@lroute('developers.getting-started')" class="mc-mobile-nav__group-link mc-mobile-nav__group-link--sub">{{ __('nav.developers_mega.doc_getting_started') }}</a>
        <a href="@lroute('developers.plugin-architecture')" class="mc-mobile-nav__group-link mc-mobile-nav__group-link--sub">{{ __('nav.developers_mega.doc_plugin_arch') }}</a>
        <a href="@lroute('developers.hook-system')" class="mc-mobile-nav__group-link mc-mobile-nav__group-link--sub">{{ __('nav.developers_mega.doc_hook') }}</a>
        <a href="@lroute('developers.ui-injection')" class="mc-mobile-nav__group-link mc-mobile-nav__group-link--sub">{{ __('nav.developers_mega.doc_ui') }}</a>
        <a href="@lroute('developers.database-models')" class="mc-mobile-nav__group-link mc-mobile-nav__group-link--sub">{{ __('nav.developers_mega.doc_database') }}</a>
        <a href="@lroute('developers.translations')" class="mc-mobile-nav__group-link mc-mobile-nav__group-link--sub">{{ __('nav.developers_mega.doc_translations') }}</a>
        <a href="@lroute('developers.lifecycle')" class="mc-mobile-nav__group-link mc-mobile-nav__group-link--sub">{{ __('nav.developers_mega.doc_lifecycle') }}</a>
        <a href="@lroute('developers.testing')" class="mc-mobile-nav__group-link mc-mobile-nav__group-link--sub">{{ __('nav.developers_mega.doc_testing') }}</a>
        <a href="@lroute('developers.sending-drivers')" class="mc-mobile-nav__group-link mc-mobile-nav__group-link--sub">{{ __('nav.developers_mega.doc_sending') }}</a>
        <a href="@lroute('developers.payment-gateways')" class="mc-mobile-nav__group-link mc-mobile-nav__group-link--sub">{{ __('nav.developers_mega.doc_payment') }}</a>
        <a href="@lroute('developers.showcase')" class="mc-mobile-nav__group-link mc-mobile-nav__group-link--sub">{{ __('nav.developers_mega.doc_showcase') }}</a>
        <a href="@lroute('api')" class="mc-mobile-nav__group-link">
          <span class="mc-mobile-nav__group-link-name">{{ __('nav.developers_mega.api') }}</span>
          <span class="mc-mobile-nav__group-link-desc">{{ __('nav.mobile_developers.api_mobile_desc') }}</span>
        </a>
        <a href="@lroute('kb.index')" class="mc-mobile-nav__group-link">
          <span class="mc-mobile-nav__group-link-name">{{ __('nav.developers_mega.kb') }}</span>
          <span class="mc-mobile-nav__group-link-desc">{{ __('nav.mobile_developers.kb_mobile_desc') }}</span>
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
          <span>{{ __('auth.signed_in_as') }} <strong>{{ $authGreeting }}</strong></span>
        </div>
        <form method="POST" action="{{ route('auth.logout') }}">
          @csrf
          <button type="submit" class="mc-btn mc-btn--secondary mc-btn--full" data-testid="mobile-logout">{{ __('auth.sign_out') }}</button>
        </form>
      </div>
    @else
      <div class="mc-mobile-nav__auth">
        <a href="{{ route('auth.login') }}" class="mc-btn mc-btn--secondary mc-btn--full" data-testid="mobile-signin"
           data-auth-modal-open data-auth-tab="login">{{ __('auth.sign_in') }}</a>
        <a href="{{ route('auth.register') }}" class="mc-mobile-nav__link" style="text-align:center; padding-top:8px;" data-testid="mobile-signup"
           data-auth-modal-open data-auth-tab="register">{{ __('auth.create_account') }}</a>
      </div>
    @endauth
    <x-locale-switcher placement="mobile" />
    <a href="https://codecanyon.net/cart/add_items?item_ids=17796082" class="mc-btn mc-btn--primary mc-btn--full">{{ __('cta.get_acellemail') }}</a>
    <a href="@lroute('demo')" class="mc-btn mc-btn--secondary mc-btn--full">{{ __('cta.try_demo') }}</a>
  </div>
</div>
