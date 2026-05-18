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

        {{-- ============================================================ --}}
        {{-- HOME — top-level link (added back per user request — feels   --}}
        {{-- familiar; logo also links here but the explicit Home link    --}}
        {{-- gives users an obvious anchor in the bar)                    --}}
        {{-- ============================================================ --}}
        <a href="{{ route('home') }}" class="mc-header__nav-link{{ $currentPage === 'home' ? ' mc-header__nav-link--active' : '' }}">Home</a>

        {{-- ============================================================ --}}
        {{-- FEATURES — rich mega-menu, parent <a> click → /features      --}}
        {{-- 6 anchor links to category sections within /features         --}}
        {{-- (Email + Automation now live INSIDE this mega — not as       --}}
        {{-- top-level flat links — to keep the bar shorter)              --}}
        {{-- ============================================================ --}}
        <div class="mc-header__dropdown mc-header__dropdown--mega" data-dropdown>
          <a href="{{ route('features') }}"
             class="mc-header__dropdown-trigger{{ $currentPage === 'features' ? ' mc-header__dropdown-trigger--active' : '' }}"
             aria-expanded="false" aria-haspopup="true">
            Features
            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="4 6 8 10 12 6"/></svg>
          </a>
          <div class="mc-header__dropdown-panel" role="menu">
            <div class="mc-header__megamenu-grid">

              {{-- LEFT col — 6 category jump-links into /features#anchor --}}
              <div class="mc-header__megamenu-links">
                <span class="mc-header__megamenu-group-label">Explore the platform</span>

                <a href="{{ route('features') }}#email-campaigns" class="mc-header__megamenu-link" role="menuitem">
                  <span class="mc-header__megamenu-link-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><polyline points="3 7 12 13 21 7"/></svg>
                  </span>
                  <span class="mc-header__megamenu-link-text">
                    <span class="mc-header__megamenu-link-name">Email campaigns</span>
                    <span class="mc-header__megamenu-link-desc">Drag-and-drop builder · A/B testing · 100+ templates · scheduling · spintax</span>
                  </span>
                </a>

                <a href="{{ route('features') }}#automation-features" class="mc-header__megamenu-link" role="menuitem">
                  <span class="mc-header__megamenu-link-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                  </span>
                  <span class="mc-header__megamenu-link-text">
                    <span class="mc-header__megamenu-link-name">Marketing automation</span>
                    <span class="mc-header__megamenu-link-desc">Trigger workflows · drip series · behavioral targeting · RSS-to-email · birthday emails</span>
                  </span>
                </a>

                <a href="{{ route('features') }}#lists" class="mc-header__megamenu-link" role="menuitem">
                  <span class="mc-header__megamenu-link-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                  </span>
                  <span class="mc-header__megamenu-link-text">
                    <span class="mc-header__megamenu-link-name">List management</span>
                    <span class="mc-header__megamenu-link-desc">Mass import · advanced segmentation · tags · embeddable forms · double opt-in</span>
                  </span>
                </a>

                <a href="{{ route('features') }}#deliverability" class="mc-header__megamenu-link" role="menuitem">
                  <span class="mc-header__megamenu-link-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
                  </span>
                  <span class="mc-header__megamenu-link-text">
                    <span class="mc-header__megamenu-link-name">Deliverability &amp; analytics</span>
                    <span class="mc-header__megamenu-link-desc">SPF / DKIM / DMARC · IP warmup · click maps · spam scoring · real-time analytics</span>
                  </span>
                </a>

                <a href="{{ route('features') }}#integrations-features" class="mc-header__megamenu-link" role="menuitem">
                  <span class="mc-header__megamenu-link-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                  </span>
                  <span class="mc-header__megamenu-link-text">
                    <span class="mc-header__megamenu-link-name">Sending services &amp; integrations</span>
                    <span class="mc-header__megamenu-link-desc">Amazon SES · SendGrid · SparkPost · Mailgun · WordPress · REST API · webhooks</span>
                  </span>
                </a>

                <a href="{{ route('features') }}#saas-platform" class="mc-header__megamenu-link" role="menuitem">
                  <span class="mc-header__megamenu-link-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="2.18"/><line x1="7" y1="2" x2="7" y2="22"/><line x1="17" y1="2" x2="17" y2="22"/><line x1="2" y1="12" x2="22" y2="12"/></svg>
                  </span>
                  <span class="mc-header__megamenu-link-text">
                    <span class="mc-header__megamenu-link-name">SaaS &amp; multi-tenancy</span>
                    <span class="mc-header__megamenu-link-desc">Multi-tenant workspaces · subscription plans · payment gateways · white-label</span>
                  </span>
                </a>

                {{-- AURIUS 4.0 — separate group break so the violet logo
                     reads as "this is a different kind of thing — an AI
                     service, not a built-in feature." Uses the real
                     acelle/ai mark (gradient violet) instead of a stroked
                     icon. --}}
                <span class="mc-header__megamenu-group-label">AI plugin &mdash; optional add-on</span>

                <a href="{{ route('aurius') }}" class="mc-header__megamenu-link{{ $currentPage === 'aurius' ? ' is-active' : '' }}" role="menuitem">
                  <span class="mc-header__megamenu-link-icon" aria-hidden="true" style="background: transparent; padding: 0;">
                    <img src="{{ asset('images/aurius/logo-mark.svg') }}" alt="" width="30" height="30" style="display:block;">
                  </span>
                  <span class="mc-header__megamenu-link-text">
                    <span class="mc-header__megamenu-link-name">
                      Aurius 4.0 &mdash; AI plugin
                      <span style="margin-left:4px;padding:2px 6px;font-size:9px;font-weight:700;letter-spacing:0.06em;background:#EDE9FE;color:#4C1D95;border-radius:999px;text-transform:uppercase;">Beta</span>
                    </span>
                    <span class="mc-header__megamenu-link-desc">Install the Acelle AI plugin to add chatbox, sparkle rewrite, coach personas, and admin observability. Aurius powers it. Not built into Acelle core.</span>
                  </span>
                </a>

                <div class="mc-header__megamenu-footer">
                  <a href="{{ route('features') }}">All features overview &rarr;</a>
                </div>
              </div>

              {{-- RIGHT col — demo CTA banner (product-focused, no pricing) --}}
              <a href="{{ route('demo') }}" class="mc-header__megamenu-banner">
                <span class="mc-header__megamenu-banner-eyebrow">SEE IT IN ACTION</span>
                <svg class="mc-header__megamenu-banner-art" viewBox="0 0 280 200" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="A live AcelleMail dashboard showing campaign opens and a paper-airplane email in flight">
                  <defs>
                    <pattern id="hdr-dots" width="14" height="14" patternUnits="userSpaceOnUse">
                      <circle cx="2" cy="2" r="1" fill="var(--theme-text)" opacity="0.06"/>
                    </pattern>
                    <filter id="hdr-shadow" x="-10%" y="-10%" width="120%" height="120%">
                      <feDropShadow dx="0" dy="6" stdDeviation="8" flood-color="#000" flood-opacity="0.08"/>
                    </filter>
                  </defs>
                  <rect width="280" height="200" fill="url(#hdr-dots)"/>
                  <g transform="translate(20, 30)" filter="url(#hdr-shadow)">
                    <rect width="200" height="130" rx="10" fill="#FFFFFF" stroke="var(--theme-border)" stroke-width="1"/>
                    <rect width="200" height="22" rx="10" fill="var(--theme-bg-light)"/>
                    <rect y="11" width="200" height="11" fill="var(--theme-bg-light)"/>
                    <circle cx="12" cy="11" r="3" fill="#E5E0DA"/>
                    <circle cx="22" cy="11" r="3" fill="#E5E0DA"/>
                    <circle cx="32" cy="11" r="3" fill="#E5E0DA"/>
                    <g transform="translate(14, 38)">
                      <text x="0" y="0" font-family="IBM Plex Sans,sans-serif" font-size="9" font-weight="700" fill="var(--theme-text-tertiary)" letter-spacing="0.6">CAMPAIGN OPENS</text>
                      @php $bars = [12, 18, 14, 22, 28, 24, 32, 26, 30]; @endphp
                      @foreach($bars as $i => $h)
                          <rect x="{{ $i * 16 }}" y="{{ 44 - $h }}" width="10" height="{{ $h }}" rx="2" fill="var(--theme-primary)" opacity="{{ 0.4 + ($i * 0.07) }}"/>
                      @endforeach
                      <line x1="0" y1="46" x2="170" y2="46" stroke="var(--theme-border)" stroke-width="0.8"/>
                    </g>
                    <g transform="translate(14, 100)" font-family="IBM Plex Sans,sans-serif" font-size="9" fill="var(--theme-text-secondary)">
                      <circle cx="4" cy="-3" r="3" fill="var(--theme-primary)"/>
                      <text x="14" y="0">Open rate</text>
                      <text x="68" y="0" font-weight="700" fill="var(--theme-text)">42%</text>
                      <circle cx="98" cy="-3" r="3" fill="var(--theme-text)" opacity="0.5"/>
                      <text x="108" y="0">Click-through</text>
                      <text x="172" y="0" font-weight="700" fill="var(--theme-text)">11%</text>
                    </g>
                  </g>
                  <g transform="translate(180, 70)">
                    <g stroke="var(--theme-primary)" stroke-width="1.5" stroke-linecap="round" opacity="0.55">
                      <line x1="-32" y1="6" x2="-18" y2="6"/>
                      <line x1="-26" y1="14" x2="-14" y2="14"/>
                      <line x1="-30" y1="22" x2="-20" y2="22"/>
                    </g>
                    <g filter="url(#hdr-shadow)">
                      <polygon points="0,0 60,18 30,22 24,42 12,28 0,30" fill="#FFFFFF" stroke="var(--theme-text)" stroke-width="1.4" stroke-linejoin="round"/>
                      <line x1="0" y1="0" x2="30" y2="22" stroke="var(--theme-text)" stroke-width="1.2"/>
                      <line x1="60" y1="18" x2="24" y2="42" stroke="var(--theme-text)" stroke-width="1.2"/>
                    </g>
                    <g transform="translate(28, 12)">
                      <rect width="14" height="9" rx="1.2" fill="var(--theme-primary)"/>
                      <polyline points="0 0, 7 5, 14 0" fill="none" stroke="var(--theme-text)" stroke-width="1" stroke-linejoin="round"/>
                    </g>
                  </g>
                </svg>
                <h4 class="mc-header__megamenu-banner-title">See AcelleMail in flight</h4>
                <p class="mc-header__megamenu-banner-desc">Live demo with a real account &mdash; no signup, no credit card. Build a campaign, run an automation, watch the analytics tick.</p>
                <span class="mc-header__megamenu-banner-cta">Open the demo &rarr;</span>
              </a>

            </div>
          </div>
        </div>

        {{-- ============================================================ --}}
        {{-- INTEGRATIONS — kept as top-level flat link (Email + Auto     --}}
        {{-- moved into the Features mega; Integrations stays here        --}}
        {{-- because external connection points are a distinct mental     --}}
        {{-- model from on-platform features)                              --}}
        {{-- ============================================================ --}}
        <a href="{{ route('integrations') }}" class="mc-header__nav-link{{ $currentPage === 'integrations' ? ' mc-header__nav-link--active' : '' }}">Integrations</a>

        {{-- ============================================================ --}}
        {{-- PRICING                                                       --}}
        {{-- ============================================================ --}}
        <a href="{{ route('pricing') }}" class="mc-header__nav-link{{ $currentPage === 'pricing' ? ' mc-header__nav-link--active' : '' }}">Pricing</a>

        {{-- ============================================================ --}}
        {{-- RESOURCES — slim                                              --}}
        {{-- ============================================================ --}}
        <div class="mc-header__dropdown" data-dropdown>
          <button class="mc-header__dropdown-trigger{{ in_array($currentPage, ['help','security','compare','glossary','blog']) ? ' mc-header__dropdown-trigger--active' : '' }}" aria-expanded="false" aria-haspopup="true">
            Resources
            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="4 6 8 10 12 6"/></svg>
          </button>
          <div class="mc-header__dropdown-panel" role="menu">
            <a href="{{ route('blog.index') }}" class="mc-header__dropdown-item{{ $currentPage === 'blog' ? ' mc-header__dropdown-item--active' : '' }}" role="menuitem">Blog</a>
            <a href="{{ route('help') }}" class="mc-header__dropdown-item{{ $currentPage === 'help' ? ' mc-header__dropdown-item--active' : '' }}" role="menuitem">Help</a>
            <a href="{{ route('security') }}" class="mc-header__dropdown-item{{ $currentPage === 'security' ? ' mc-header__dropdown-item--active' : '' }}" role="menuitem">Security &amp; GDPR</a>
            <a href="{{ route('glossary.index') }}" class="mc-header__dropdown-item{{ $currentPage === 'glossary' ? ' mc-header__dropdown-item--active' : '' }}" role="menuitem">Glossary</a>
            <a href="{{ route('compare.show', ['slug' => 'mailchimp']) }}" class="mc-header__dropdown-item{{ $currentPage === 'compare' ? ' mc-header__dropdown-item--active' : '' }}" role="menuitem">Mailchimp Comparison</a>
            <a href="{{ route('compare.show', ['slug' => 'listmonk']) }}" class="mc-header__dropdown-item{{ $currentPage === 'compare' ? ' mc-header__dropdown-item--active' : '' }}" role="menuitem">listmonk Comparison</a>
            <a href="{{ route('compare.show', ['slug' => 'mautic']) }}" class="mc-header__dropdown-item{{ $currentPage === 'compare' ? ' mc-header__dropdown-item--active' : '' }}" role="menuitem">Mautic Comparison</a>
            <a href="{{ route('compare.show', ['slug' => 'sendgrid']) }}" class="mc-header__dropdown-item{{ $currentPage === 'compare' ? ' mc-header__dropdown-item--active' : '' }}" role="menuitem">SendGrid Comparison</a>
            <a href="{{ route('compare.show', ['slug' => 'brevo']) }}" class="mc-header__dropdown-item{{ $currentPage === 'compare' ? ' mc-header__dropdown-item--active' : '' }}" role="menuitem">Brevo Comparison</a>
            <a href="{{ route('compare.show', ['slug' => 'klaviyo']) }}" class="mc-header__dropdown-item{{ $currentPage === 'compare' ? ' mc-header__dropdown-item--active' : '' }}" role="menuitem">Klaviyo Comparison</a>
            <a href="/kb" class="mc-header__dropdown-item" role="menuitem">Knowledge Base &nearr;</a>
          </div>
        </div>

        {{-- ============================================================ --}}
        {{-- COMPANY — slim                                                --}}
        {{-- ============================================================ --}}
        <div class="mc-header__dropdown" data-dropdown>
          <button class="mc-header__dropdown-trigger{{ in_array($currentPage, ['about','contact']) ? ' mc-header__dropdown-trigger--active' : '' }}" aria-expanded="false" aria-haspopup="true">
            Company
            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="4 6 8 10 12 6"/></svg>
          </button>
          <div class="mc-header__dropdown-panel" role="menu">
            <a href="{{ route('about') }}" class="mc-header__dropdown-item{{ $currentPage === 'about' ? ' mc-header__dropdown-item--active' : '' }}" role="menuitem">About</a>
            <a href="{{ route('contact') }}" class="mc-header__dropdown-item{{ $currentPage === 'contact' ? ' mc-header__dropdown-item--active' : '' }}" role="menuitem">Contact</a>
          </div>
        </div>

        {{-- ============================================================ --}}
        {{-- DEVELOPERS — last position so non-dev users aren't            --}}
        {{-- intimidated by code-flavored language up front. Still a full  --}}
        {{-- mega-menu since the docs investment is real.                  --}}
        {{-- ============================================================ --}}
        <div class="mc-header__dropdown mc-header__dropdown--mega" data-dropdown>
          <a href="{{ route('for.developers') }}"
             class="mc-header__dropdown-trigger{{ in_array($currentPage, ['for-developers','api','developers']) ? ' mc-header__dropdown-trigger--active' : '' }}"
             aria-expanded="false" aria-haspopup="true">
            Developers
            <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="4 6 8 10 12 6"/></svg>
          </a>
          <div class="mc-header__dropdown-panel" role="menu">
            <div class="mc-header__megamenu-grid">

              <div class="mc-header__megamenu-links">
                <span class="mc-header__megamenu-group-label">Get started</span>

                <a href="{{ route('for.developers') }}" class="mc-header__megamenu-link{{ $currentPage === 'for-developers' ? ' is-active' : '' }}" role="menuitem">
                  <span class="mc-header__megamenu-link-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M5 4h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"/><path d="M9 9l-3 3 3 3"/><path d="M15 9l3 3-3 3"/></svg>
                  </span>
                  <span class="mc-header__megamenu-link-text">
                    <span class="mc-header__megamenu-link-name">Developer overview</span>
                    <span class="mc-header__megamenu-link-desc">Plugin SDK, four-pattern Hook system, REST API</span>
                  </span>
                </a>

                <a href="{{ route('developers.index') }}" class="mc-header__megamenu-link{{ $currentPage === 'developers' ? ' is-active' : '' }}" role="menuitem">
                  <span class="mc-header__megamenu-link-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                  </span>
                  <span class="mc-header__megamenu-link-text">
                    <span class="mc-header__megamenu-link-name">Plugin Documentation</span>
                    <span class="mc-header__megamenu-link-desc">11-page deep-dive reference, source-grounded</span>
                  </span>
                </a>

                <a href="{{ route('api') }}" class="mc-header__megamenu-link{{ $currentPage === 'api' ? ' is-active' : '' }}" role="menuitem">
                  <span class="mc-header__megamenu-link-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                  </span>
                  <span class="mc-header__megamenu-link-text">
                    <span class="mc-header__megamenu-link-name">REST API reference</span>
                    <span class="mc-header__megamenu-link-desc">Token auth, 8 resources, lifecycle webhooks</span>
                  </span>
                </a>

                <span class="mc-header__megamenu-group-label">Reference</span>

                <a href="/kb" class="mc-header__megamenu-link" role="menuitem">
                  <span class="mc-header__megamenu-link-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                  </span>
                  <span class="mc-header__megamenu-link-text">
                    <span class="mc-header__megamenu-link-name">Knowledge Base &nearr;</span>
                    <span class="mc-header__megamenu-link-desc">Operations, setup, deliverability tuning</span>
                  </span>
                </a>

                <div class="mc-header__megamenu-footer">
                  <a href="{{ route('for.developers') }}">Visit developer landing &rarr;</a>
                </div>
              </div>

              <div class="mc-header__megamenu-banner mc-header__megamenu-banner--list">
                <h4 class="mc-header__megamenu-banner-title">Plugin Documentation — direct deep-dives</h4>

                <div class="mc-header__megamenu-doctracks">
                  <div class="mc-header__megamenu-doctrack">
                    <span class="mc-header__megamenu-doctrack-label">Foundation</span>
                    <a href="{{ route('developers.getting-started') }}" class="mc-header__megamenu-doc-link" role="menuitem">Getting started</a>
                    <a href="{{ route('developers.plugin-architecture') }}" class="mc-header__megamenu-doc-link" role="menuitem">Plugin architecture</a>
                    <a href="{{ route('developers.hook-system') }}" class="mc-header__megamenu-doc-link" role="menuitem">Hook system &mdash; 4 patterns</a>
                  </div>

                  <div class="mc-header__megamenu-doctrack">
                    <span class="mc-header__megamenu-doctrack-label">Building &amp; Quality</span>
                    <a href="{{ route('developers.ui-injection') }}" class="mc-header__megamenu-doc-link" role="menuitem">UI injection</a>
                    <a href="{{ route('developers.database-models') }}" class="mc-header__megamenu-doc-link" role="menuitem">Database &amp; models</a>
                    <a href="{{ route('developers.translations') }}" class="mc-header__megamenu-doc-link" role="menuitem">Translations</a>
                    <a href="{{ route('developers.lifecycle') }}" class="mc-header__megamenu-doc-link" role="menuitem">Plugin lifecycle</a>
                    <a href="{{ route('developers.testing') }}" class="mc-header__megamenu-doc-link" role="menuitem">Testing</a>
                    <a href="{{ route('developers.sending-drivers') }}" class="mc-header__megamenu-doc-link" role="menuitem">Sending drivers</a>
                    <a href="{{ route('developers.payment-gateways') }}" class="mc-header__megamenu-doc-link" role="menuitem">Payment gateways</a>
                    <a href="{{ route('developers.showcase') }}" class="mc-header__megamenu-doc-link" role="menuitem">Plugin showcase &mdash; Acelle AI</a>
                  </div>
                </div>

                <a href="{{ route('developers.index') }}" class="mc-header__megamenu-banner-cta mc-header__megamenu-banner-cta--inline">Browse all docs &rarr;</a>
              </div>

            </div>
          </div>
        </div>

      </nav>

      <div class="mc-header__right">
        {{-- Auth state lives in the top utility bar (see partials/topbar.blade.php).
             Keeping it out of the main nav strip prevents the "Sign in" pill
             from wrapping under cramped widths between Developers ▾ and Try Demo. --}}
        <a href="{{ route('demo') }}" class="mc-btn mc-btn--secondary mc-btn--sm mc-header__login">Try Demo</a>
        <a href="https://codecanyon.net/cart/add_items?item_ids=17796082" class="mc-btn mc-btn--primary mc-header__cta">Get AcelleMail — $74</a>

        <button class="mc-header__hamburger" id="hamburgerBtn" aria-label="Open menu" aria-expanded="false">
          <span></span><span></span><span></span>
        </button>
      </div>
    </div>
  </div>
</header>
