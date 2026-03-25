@extends('layouts.app')

@section('title', 'Security & GDPR | AcelleMail')

@section('content')

<!-- ======================================================================
     SECURITY — HERO
     ====================================================================== -->
<section class="mc-features-hero">
  <div class="mc-container">
    <p class="mc-features-hero__eyebrow">TRUST &amp; SECURITY</p>
    <h1 class="mc-features-hero__heading">Your data stays on YOUR server. Full control, full privacy.</h1>
    <p class="mc-text-lg" style="max-width: 680px; margin: var(--space-lg) auto var(--space-xl); color: var(--mc-gray);">
      AcelleMail is self-hosted&mdash;meaning your subscriber data, campaigns, and analytics never leave your server.
      No third-party access, no shared infrastructure. You own everything.
    </p>
    <a href="{{ route('security') }}" class="mc-btn mc-btn--primary mc-btn--lg">Learn about our practices</a>
  </div>
</section>

<!-- ======================================================================
     SECURITY — BADGES ROW
     ====================================================================== -->
<section class="mc-stats-section">
  <div class="mc-container">
    <div class="mc-stats-section__grid">

      <!-- Self-Hosted -->
      <div class="mc-stats-section__item" style="text-align: center;">
        <div style="margin-bottom: var(--space-md);">
          <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M24 4L8 12v12c0 11.1 6.8 21.4 16 24 9.2-2.6 16-12.9 16-24V12L24 4z" stroke="#241C15" stroke-width="2.5" fill="none"/>
            <path d="M18 24l4 4 8-8" stroke="#241C15" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <span class="mc-stats-section__label" style="font-size: 18px;">Self-Hosted</span>
        <p class="mc-stats-section__desc">You control ALL data &mdash; no third-party access, ever</p>
      </div>

      <!-- GDPR Compliant -->
      <div class="mc-stats-section__item" style="text-align: center;">
        <div style="margin-bottom: var(--space-md);">
          <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="24" cy="24" r="18" stroke="#241C15" stroke-width="2.5"/>
            <path d="M24 14v4M24 30v4M14 24h4M30 24h4" stroke="#241C15" stroke-width="2.5" stroke-linecap="round"/>
            <circle cx="24" cy="24" r="6" stroke="#241C15" stroke-width="2.5"/>
          </svg>
        </div>
        <span class="mc-stats-section__label" style="font-size: 18px;">GDPR Tools</span>
        <p class="mc-stats-section__desc">Built-in consent management, data export, and deletion tools</p>
      </div>

      <!-- CAN-SPAM -->
      <div class="mc-stats-section__item" style="text-align: center;">
        <div style="margin-bottom: var(--space-md);">
          <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="10" y="20" width="28" height="20" rx="3" stroke="#241C15" stroke-width="2.5"/>
            <path d="M16 20v-6a8 8 0 0116 0v6" stroke="#241C15" stroke-width="2.5" stroke-linecap="round"/>
            <circle cx="24" cy="31" r="3" stroke="#241C15" stroke-width="2.5"/>
            <path d="M24 34v3" stroke="#241C15" stroke-width="2.5" stroke-linecap="round"/>
          </svg>
        </div>
        <span class="mc-stats-section__label" style="font-size: 18px;">CAN-SPAM &amp; CASL</span>
        <p class="mc-stats-section__desc">Compliance tools for anti-spam regulations built in</p>
      </div>

      <!-- Open Source -->
      <div class="mc-stats-section__item" style="text-align: center;">
        <div style="margin-bottom: var(--space-md);">
          <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M24 4L8 12v12c0 11.1 6.8 21.4 16 24 9.2-2.6 16-12.9 16-24V12L24 4z" stroke="#241C15" stroke-width="2.5" fill="none"/>
            <path d="M17 24h14M24 17v14" stroke="#241C15" stroke-width="2.5" stroke-linecap="round"/>
          </svg>
        </div>
        <span class="mc-stats-section__label" style="font-size: 18px;">Open Source</span>
        <p class="mc-stats-section__desc">Audit the code yourself &mdash; full transparency, no black boxes</p>
      </div>

    </div>
  </div>
</section>

<!-- ======================================================================
     SECURITY — SELF-HOSTED = FULL CONTROL
     ====================================================================== -->
<section class="mc-feature-alt">
  <div class="mc-container">
    <div class="mc-feature-alt__grid">
      <div class="mc-feature-alt__image">
        <img src="{{ asset('images/features/security-control.svg') }}" alt="Self-hosted security control">
      </div>
      <div class="mc-feature-alt__content">
        <h2 class="mc-feature-alt__heading">Self-hosted means full control</h2>
        <p class="mc-feature-alt__text">
          With AcelleMail, your data never leaves your server. Unlike SaaS platforms where your subscriber lists sit on
          someone else&rsquo;s infrastructure, AcelleMail runs entirely on your own hosting&mdash;giving you complete
          ownership and control over every piece of data.
        </p>
        <ul style="list-style: none; padding: 0; margin: var(--space-lg) 0 0 0;">
          <li style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 12px; font-size: 15px; color: var(--mc-dark-gray);">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" style="flex-shrink: 0; margin-top: 2px;"><circle cx="10" cy="10" r="10" fill="var(--mc-yellow)"/><path d="M6 10l3 3 5-5" stroke="var(--mc-white)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            SSL/TLS encryption for all connections to your server
          </li>
          <li style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 12px; font-size: 15px; color: var(--mc-dark-gray);">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" style="flex-shrink: 0; margin-top: 2px;"><circle cx="10" cy="10" r="10" fill="var(--mc-yellow)"/><path d="M6 10l3 3 5-5" stroke="var(--mc-white)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            No third-party access to your subscriber data
          </li>
          <li style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 12px; font-size: 15px; color: var(--mc-dark-gray);">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" style="flex-shrink: 0; margin-top: 2px;"><circle cx="10" cy="10" r="10" fill="var(--mc-yellow)"/><path d="M6 10l3 3 5-5" stroke="var(--mc-white)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Role-based access control with granular permissions
          </li>
          <li style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 0; font-size: 15px; color: var(--mc-dark-gray);">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" style="flex-shrink: 0; margin-top: 2px;"><circle cx="10" cy="10" r="10" fill="var(--mc-yellow)"/><path d="M6 10l3 3 5-5" stroke="var(--mc-white)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            IP whitelisting and rate limiting for API access
          </li>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- ======================================================================
     SECURITY — GDPR COMPLIANCE TOOLS
     ====================================================================== -->
<section class="mc-feature-alt mc-feature-alt--reverse">
  <div class="mc-container">
    <div class="mc-feature-alt__grid">
      <div class="mc-feature-alt__content">
        <h2 class="mc-feature-alt__heading">GDPR compliance tools built in</h2>
        <p class="mc-feature-alt__text">
          AcelleMail includes everything you need to comply with GDPR, CAN-SPAM, and CASL regulations.
          Since you host the data yourself, you have direct control over how personal data is collected,
          stored, and processed&mdash;no reliance on a third-party&rsquo;s promises.
        </p>
        <ul style="list-style: none; padding: 0; margin: var(--space-lg) 0 0 0;">
          <li style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 12px; font-size: 15px; color: var(--mc-dark-gray);">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" style="flex-shrink: 0; margin-top: 2px;"><circle cx="10" cy="10" r="10" fill="var(--mc-yellow)"/><path d="M6 10l3 3 5-5" stroke="var(--mc-white)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            GDPR-friendly signup forms with consent checkboxes
          </li>
          <li style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 12px; font-size: 15px; color: var(--mc-dark-gray);">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" style="flex-shrink: 0; margin-top: 2px;"><circle cx="10" cy="10" r="10" fill="var(--mc-yellow)"/><path d="M6 10l3 3 5-5" stroke="var(--mc-white)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            One-click data export for any subscriber (data portability)
          </li>
          <li style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 12px; font-size: 15px; color: var(--mc-dark-gray);">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" style="flex-shrink: 0; margin-top: 2px;"><circle cx="10" cy="10" r="10" fill="var(--mc-yellow)"/><path d="M6 10l3 3 5-5" stroke="var(--mc-white)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Right to erasure &mdash; delete contact data on request
          </li>
          <li style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 0; font-size: 15px; color: var(--mc-dark-gray);">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" style="flex-shrink: 0; margin-top: 2px;"><circle cx="10" cy="10" r="10" fill="var(--mc-yellow)"/><path d="M6 10l3 3 5-5" stroke="var(--mc-white)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Unsubscribe and preference management for CAN-SPAM/CASL compliance
          </li>
        </ul>
      </div>
      <div class="mc-feature-alt__image">
        <img src="{{ asset('images/features/predictive.svg') }}" alt="GDPR compliance tools">
      </div>
    </div>
  </div>
</section>

<!-- ======================================================================
     SECURITY — STATS
     ====================================================================== -->
<section class="mc-stats-section" style="background: var(--mc-cream);">
  <div class="mc-container">
    <div class="mc-stats-section__header">
      <h2 class="mc-stats-section__heading">Security you can count on</h2>
      <p class="mc-stats-section__subheading">
        Self-hosted gives you the ultimate security advantage: your data, your rules, your infrastructure.
      </p>
    </div>
    <div class="mc-stats-section__grid">
      <div class="mc-stats-section__item">
        <span class="mc-stats-section__number">100%</span>
        <span class="mc-stats-section__label">your data</span>
        <p class="mc-stats-section__desc">Everything stays on your server &mdash; no shared infrastructure</p>
      </div>
      <div class="mc-stats-section__item">
        <span class="mc-stats-section__number">SSL/TLS</span>
        <span class="mc-stats-section__label">encryption</span>
        <p class="mc-stats-section__desc">Encrypted connections for admin panel, API, and subscriber interactions</p>
      </div>
      <div class="mc-stats-section__item">
        <span class="mc-stats-section__number">Open</span>
        <span class="mc-stats-section__label">source code</span>
        <p class="mc-stats-section__desc">Audit every line of code &mdash; no hidden data collection or tracking</p>
      </div>
      <div class="mc-stats-section__item">
        <span class="mc-stats-section__number">Regular</span>
        <span class="mc-stats-section__label">security updates</span>
        <p class="mc-stats-section__desc">Patches and updates delivered via CodeCanyon for all license holders</p>
      </div>
    </div>
  </div>
</section>

<!-- ======================================================================
     SECURITY — EMAIL AUTHENTICATION
     ====================================================================== -->
<section class="mc-feature-alt">
  <div class="mc-container">
    <div class="mc-feature-alt__grid">
      <div class="mc-feature-alt__image">
        <img src="{{ asset('images/features/integrations-auto.svg') }}" alt="Email authentication and deliverability">
      </div>
      <div class="mc-feature-alt__content">
        <h2 class="mc-feature-alt__heading">SPF, DKIM &amp; DMARC Support</h2>
        <p class="mc-feature-alt__text">
          AcelleMail gives you full control over email authentication. Set up SPF, DKIM, and DMARC records
          to verify your sending domain, protect against spoofing, and improve deliverability&mdash;all
          managed from your own server.
        </p>
        <ul style="list-style: none; padding: 0; margin: var(--space-lg) 0 0 0;">
          <li style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 12px; font-size: 15px; color: var(--mc-dark-gray);">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" style="flex-shrink: 0; margin-top: 2px;"><circle cx="10" cy="10" r="10" fill="var(--mc-yellow)"/><path d="M6 10l3 3 5-5" stroke="var(--mc-white)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            SPF, DKIM, and DMARC setup with built-in verification tools
          </li>
          <li style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 12px; font-size: 15px; color: var(--mc-dark-gray);">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" style="flex-shrink: 0; margin-top: 2px;"><circle cx="10" cy="10" r="10" fill="var(--mc-yellow)"/><path d="M6 10l3 3 5-5" stroke="var(--mc-white)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Custom sending domains with full DNS control
          </li>
          <li style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 12px; font-size: 15px; color: var(--mc-dark-gray);">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" style="flex-shrink: 0; margin-top: 2px;"><circle cx="10" cy="10" r="10" fill="var(--mc-yellow)"/><path d="M6 10l3 3 5-5" stroke="var(--mc-white)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Works with Amazon SES, SendGrid, Mailgun, or any SMTP server
          </li>
          <li style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 0; font-size: 15px; color: var(--mc-dark-gray);">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" style="flex-shrink: 0; margin-top: 2px;"><circle cx="10" cy="10" r="10" fill="var(--mc-yellow)"/><path d="M6 10l3 3 5-5" stroke="var(--mc-white)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Bounce handling and feedback loop processing for sender reputation
          </li>
        </ul>
        <a href="{{ route('email-marketing') }}" class="mc-feature-alt__link" style="margin-top: var(--space-lg); display: inline-block;">Learn about email authentication <span>&rarr;</span></a>
      </div>
    </div>
  </div>
</section>

<!-- ======================================================================
     SECURITY — COMPLIANCE GRID
     ====================================================================== -->
<section class="mc-section mc-section--cream">
  <div class="mc-container">
    <div style="text-align: center; margin-bottom: var(--space-2xl);">
      <h2 style="font-family: var(--font-serif); font-size: clamp(24px, 3vw, 36px); font-weight: 300; color: var(--mc-black);">Compliance &amp; Standards</h2>
      <p style="max-width: 600px; margin: var(--space-md) auto 0; color: var(--mc-gray); font-size: 16px; line-height: 1.6;">
        AcelleMail provides the tools you need to stay compliant with global email marketing regulations
        and data privacy standards.
      </p>
    </div>
    <div class="mc-grid mc-grid--3 mc-grid--gap-lg">

      <!-- GDPR -->
      <div class="mc-card mc-card--bordered" style="text-align: center;">
        <div class="mc-card__body mc-card__body--lg">
          <div style="margin-bottom: var(--space-md);">
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M20 3L6 10v10c0 9.2 5.7 17.8 14 20 8.3-2.2 14-10.8 14-20V10L20 3z" stroke="#241C15" stroke-width="2" fill="none"/>
              <path d="M14 20l4 4 8-8" stroke="#241C15" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <h4 class="mc-card__title">GDPR Compliant</h4>
          <p class="mc-card__desc">
            Built-in tools for consent management, data export, right to erasure, and subscriber
            preference centers. Since data stays on your server, you have direct control over
            all personal data processing.
          </p>
        </div>
      </div>

      <!-- CAN-SPAM -->
      <div class="mc-card mc-card--bordered" style="text-align: center;">
        <div class="mc-card__body mc-card__body--lg">
          <div style="margin-bottom: var(--space-md);">
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle cx="20" cy="20" r="15" stroke="#241C15" stroke-width="2"/>
              <path d="M20 11v4M20 25v4M11 20h4M25 20h4" stroke="#241C15" stroke-width="2" stroke-linecap="round"/>
              <circle cx="20" cy="20" r="5" stroke="#241C15" stroke-width="2"/>
            </svg>
          </div>
          <h4 class="mc-card__title">CAN-SPAM</h4>
          <p class="mc-card__desc">
            Automatic unsubscribe headers, physical address inclusion, and opt-out processing
            ensure your campaigns comply with the CAN-SPAM Act. AcelleMail handles the
            technical requirements automatically.
          </p>
        </div>
      </div>

      <!-- CASL -->
      <div class="mc-card mc-card--bordered" style="text-align: center;">
        <div class="mc-card__body mc-card__body--lg">
          <div style="margin-bottom: var(--space-md);">
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect x="8" y="17" width="24" height="16" rx="3" stroke="#241C15" stroke-width="2"/>
              <path d="M13 17v-4a7 7 0 0114 0v4" stroke="#241C15" stroke-width="2" stroke-linecap="round"/>
              <circle cx="20" cy="26" r="2.5" stroke="#241C15" stroke-width="2"/>
              <path d="M20 28.5v2" stroke="#241C15" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </div>
          <h4 class="mc-card__title">CASL</h4>
          <p class="mc-card__desc">
            Canada&rsquo;s Anti-Spam Legislation compliance with express consent tracking,
            implied consent expiration, and proper identification in all commercial messages.
            Built-in tools make CASL compliance straightforward.
          </p>
        </div>
      </div>

      <!-- Role-Based Access -->
      <div class="mc-card mc-card--bordered" style="text-align: center;">
        <div class="mc-card__body mc-card__body--lg">
          <div style="margin-bottom: var(--space-md);">
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect x="6" y="6" width="28" height="28" rx="4" stroke="#241C15" stroke-width="2"/>
              <path d="M16 14v12M24 14v12M16 20h8" stroke="#241C15" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </div>
          <h4 class="mc-card__title">Role-Based Access</h4>
          <p class="mc-card__desc">
            Define admin, manager, and user roles with granular permissions. Control who can
            access subscriber data, send campaigns, manage templates, and configure system
            settings across your organization.
          </p>
        </div>
      </div>

      <!-- IP Whitelisting -->
      <div class="mc-card mc-card--bordered" style="text-align: center;">
        <div class="mc-card__body mc-card__body--lg">
          <div style="margin-bottom: var(--space-md);">
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
              <rect x="6" y="10" width="28" height="20" rx="3" stroke="#241C15" stroke-width="2"/>
              <path d="M6 17h28" stroke="#241C15" stroke-width="2"/>
              <path d="M11 24h8" stroke="#241C15" stroke-width="2" stroke-linecap="round"/>
              <path d="M11 27h5" stroke="#241C15" stroke-width="2" stroke-linecap="round"/>
            </svg>
          </div>
          <h4 class="mc-card__title">IP Whitelisting &amp; Rate Limiting</h4>
          <p class="mc-card__desc">
            Restrict admin panel access to specific IP addresses and configure rate limiting
            to protect against brute-force attacks and API abuse. Your server, your firewall rules.
          </p>
        </div>
      </div>

      <!-- Open Source Audit -->
      <div class="mc-card mc-card--bordered" style="text-align: center;">
        <div class="mc-card__body mc-card__body--lg">
          <div style="margin-bottom: var(--space-md);">
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle cx="20" cy="20" r="15" stroke="#241C15" stroke-width="2"/>
              <path d="M13 20l4 4 10-10" stroke="#241C15" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
          <h4 class="mc-card__title">Open Source Transparency</h4>
          <p class="mc-card__desc">
            AcelleMail&rsquo;s source code is fully accessible. Audit every line to verify there is no
            hidden tracking, no unauthorized data collection, and no backdoors. Trust through
            transparency, not promises.
          </p>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ======================================================================
     SECURITY — SERVER SECURITY
     ====================================================================== -->
<section class="mc-feature-alt mc-feature-alt--reverse">
  <div class="mc-container">
    <div class="mc-feature-alt__grid">
      <div class="mc-feature-alt__content">
        <h2 class="mc-feature-alt__heading">Your server, your security</h2>
        <p class="mc-feature-alt__text">
          Because AcelleMail runs on your own infrastructure, you decide the security measures. Configure your
          server&rsquo;s firewall, set up fail2ban, enable two-factor authentication, and apply your organization&rsquo;s
          security policies directly&mdash;no limitations imposed by a SaaS provider.
        </p>
        <ul style="list-style: none; padding: 0; margin: var(--space-lg) 0 0 0;">
          <li style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 12px; font-size: 15px; color: var(--mc-dark-gray);">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" style="flex-shrink: 0; margin-top: 2px;"><circle cx="10" cy="10" r="10" fill="var(--mc-yellow)"/><path d="M6 10l3 3 5-5" stroke="var(--mc-white)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Full control over server firewall and network security
          </li>
          <li style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 12px; font-size: 15px; color: var(--mc-dark-gray);">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" style="flex-shrink: 0; margin-top: 2px;"><circle cx="10" cy="10" r="10" fill="var(--mc-yellow)"/><path d="M6 10l3 3 5-5" stroke="var(--mc-white)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Database encryption and backup strategies under your control
          </li>
          <li style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 12px; font-size: 15px; color: var(--mc-dark-gray);">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" style="flex-shrink: 0; margin-top: 2px;"><circle cx="10" cy="10" r="10" fill="var(--mc-yellow)"/><path d="M6 10l3 3 5-5" stroke="var(--mc-white)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Regular security updates delivered via CodeCanyon
          </li>
          <li style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 12px; font-size: 15px; color: var(--mc-dark-gray);">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" style="flex-shrink: 0; margin-top: 2px;"><circle cx="10" cy="10" r="10" fill="var(--mc-yellow)"/><path d="M6 10l3 3 5-5" stroke="var(--mc-white)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            SSH access and server-level monitoring tools
          </li>
          <li style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 0; font-size: 15px; color: var(--mc-dark-gray);">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" style="flex-shrink: 0; margin-top: 2px;"><circle cx="10" cy="10" r="10" fill="var(--mc-yellow)"/><path d="M6 10l3 3 5-5" stroke="var(--mc-white)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            No vendor lock-in &mdash; migrate your data anytime
          </li>
        </ul>
      </div>
      <div class="mc-feature-alt__image">
        <img src="{{ asset('images/features/automation-flows.svg') }}" alt="Server security">
      </div>
    </div>
  </div>
</section>

<!-- ======================================================================
     SECURITY — DATA HANDLING PRACTICES
     ====================================================================== -->
<section class="mc-section mc-section--light">
  <div class="mc-container mc-container--narrow">
    <div style="text-align: center; margin-bottom: var(--space-2xl);">
      <h2 style="font-family: var(--font-serif); font-size: clamp(24px, 3vw, 36px); font-weight: 300; color: var(--mc-black);">How your data is handled</h2>
      <p style="max-width: 600px; margin: var(--space-md) auto 0; color: var(--mc-gray); font-size: 16px; line-height: 1.6;">
        With AcelleMail, you have full visibility and control over every step of data handling.
      </p>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: var(--space-xl);">

      <!-- Collection -->
      <div style="padding: var(--space-xl); background: var(--mc-white); border-radius: var(--radius-md); border: 1px solid var(--mc-border);">
        <div style="margin-bottom: var(--space-md);">
          <svg width="32" height="32" viewBox="0 0 32 32" fill="none"><path d="M16 4v24M4 16h24" stroke="#241C15" stroke-width="2.5" stroke-linecap="round"/></svg>
        </div>
        <h4 style="font-family: var(--font-sans); font-size: 18px; font-weight: 600; margin-bottom: var(--space-sm); color: var(--mc-black);">Data Collection</h4>
        <p style="font-size: 14px; line-height: 1.6; color: var(--mc-gray); margin: 0;">
          Subscribers are collected through your own forms hosted on your server. You control
          what fields to collect, consent requirements, and double opt-in settings.
        </p>
      </div>

      <!-- Storage -->
      <div style="padding: var(--space-xl); background: var(--mc-white); border-radius: var(--radius-md); border: 1px solid var(--mc-border);">
        <div style="margin-bottom: var(--space-md);">
          <svg width="32" height="32" viewBox="0 0 32 32" fill="none"><ellipse cx="16" cy="8" rx="12" ry="4" stroke="#241C15" stroke-width="2"/><path d="M4 8v16c0 2.2 5.4 4 12 4s12-1.8 12-4V8" stroke="#241C15" stroke-width="2"/><path d="M4 16c0 2.2 5.4 4 12 4s12-1.8 12-4" stroke="#241C15" stroke-width="2"/></svg>
        </div>
        <h4 style="font-family: var(--font-sans); font-size: 18px; font-weight: 600; margin-bottom: var(--space-sm); color: var(--mc-black);">Data Storage</h4>
        <p style="font-size: 14px; line-height: 1.6; color: var(--mc-gray); margin: 0;">
          All data is stored in your MySQL database on your server. You manage backups,
          encryption at rest, and retention policies according to your own standards.
        </p>
      </div>

      <!-- Processing -->
      <div style="padding: var(--space-xl); background: var(--mc-white); border-radius: var(--radius-md); border: 1px solid var(--mc-border);">
        <div style="margin-bottom: var(--space-md);">
          <svg width="32" height="32" viewBox="0 0 32 32" fill="none"><circle cx="16" cy="16" r="12" stroke="#241C15" stroke-width="2"/><path d="M16 10v6l4 2" stroke="#241C15" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </div>
        <h4 style="font-family: var(--font-sans); font-size: 18px; font-weight: 600; margin-bottom: var(--space-sm); color: var(--mc-black);">Data Processing</h4>
        <p style="font-size: 14px; line-height: 1.6; color: var(--mc-gray); margin: 0;">
          Campaigns, automations, and analytics are processed entirely on your server.
          No data is sent to AcelleMail&rsquo;s servers&mdash;your sending service (SES, SendGrid, SMTP) is configured by you.
        </p>
      </div>

      <!-- Deletion -->
      <div style="padding: var(--space-xl); background: var(--mc-white); border-radius: var(--radius-md); border: 1px solid var(--mc-border);">
        <div style="margin-bottom: var(--space-md);">
          <svg width="32" height="32" viewBox="0 0 32 32" fill="none"><path d="M6 10h20M12 10V7a1 1 0 011-1h6a1 1 0 011 1v3M10 10v16a2 2 0 002 2h8a2 2 0 002-2V10" stroke="#241C15" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </div>
        <h4 style="font-family: var(--font-sans); font-size: 18px; font-weight: 600; margin-bottom: var(--space-sm); color: var(--mc-black);">Data Deletion</h4>
        <p style="font-size: 14px; line-height: 1.6; color: var(--mc-gray); margin: 0;">
          Delete individual subscribers or entire lists instantly. Since you have direct database access,
          data deletion is immediate and verifiable&mdash;no waiting for a third party to process your request.
        </p>
      </div>

    </div>
  </div>
</section>

<!-- ======================================================================
     SECURITY — OPEN SOURCE
     ====================================================================== -->
<section class="mc-feature-alt">
  <div class="mc-container">
    <div class="mc-feature-alt__grid">
      <div class="mc-feature-alt__image">
        <img src="{{ asset('images/features/predictive.svg') }}" alt="Open source transparency">
      </div>
      <div class="mc-feature-alt__content">
        <h2 class="mc-feature-alt__heading">Open source &mdash; audit the code yourself</h2>
        <p class="mc-feature-alt__text">
          Unlike closed-source SaaS platforms, AcelleMail gives you full access to the source code.
          Your development team can review every function, verify there are no hidden trackers or backdoors,
          and customize the platform to meet your organization&rsquo;s specific security requirements.
        </p>
        <p class="mc-feature-alt__text" style="margin-top: var(--space-md);">
          Security updates are released regularly through CodeCanyon. Apply patches on your own schedule
          after reviewing changes&mdash;you&rsquo;re never forced into an update you haven&rsquo;t tested.
        </p>
        <a href="{{ route('pricing') }}" class="mc-feature-alt__link" style="margin-top: var(--space-lg); display: inline-block;">Get AcelleMail on CodeCanyon <span>&rarr;</span></a>
      </div>
    </div>
  </div>
</section>

<!-- ======================================================================
     SECURITY — FAQ
     ====================================================================== -->
<section class="mc-faq">
  <div class="mc-container">
    <h2 class="mc-faq__heading">Frequently Asked Questions</h2>
    <div class="mc-faq__list">

      <div class="mc-faq__item">
        <button class="mc-faq__question" type="button" aria-expanded="false">
          <span>How does AcelleMail protect my data?</span>
          <svg class="mc-faq__icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
        </button>
        <div class="mc-faq__answer">
          <p>AcelleMail is self-hosted, meaning all your data stays on your own server. There is no third-party access to your subscriber lists, campaign data, or analytics. You control the server security: SSL/TLS encryption, firewall rules, database encryption, access controls, and backups. The source code is open for audit, so you can verify exactly how your data is handled.</p>
        </div>
      </div>

      <div class="mc-faq__item">
        <button class="mc-faq__question" type="button" aria-expanded="false">
          <span>Does AcelleMail support GDPR compliance?</span>
          <svg class="mc-faq__icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
        </button>
        <div class="mc-faq__answer">
          <p>Yes. AcelleMail includes GDPR compliance tools out of the box: consent checkboxes on signup forms, one-click data export for subscribers, right to erasure (delete subscriber data on request), and preference management centers. Since all data is stored on your server, you have direct control over data processing with no cross-border data transfer concerns.</p>
        </div>
      </div>

      <div class="mc-faq__item">
        <button class="mc-faq__question" type="button" aria-expanded="false">
          <span>Is AcelleMail more secure than SaaS email platforms?</span>
          <svg class="mc-faq__icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
        </button>
        <div class="mc-faq__answer">
          <p>Self-hosted platforms like AcelleMail eliminate several security risks inherent to SaaS: no shared infrastructure with other customers, no third-party data access, no vendor data breaches affecting your data, and no reliance on a provider&rsquo;s security practices. You apply your own security policies, firewall rules, and access controls directly to the server running AcelleMail.</p>
        </div>
      </div>

      <div class="mc-faq__item">
        <button class="mc-faq__question" type="button" aria-expanded="false">
          <span>How do I set up SPF, DKIM, and DMARC with AcelleMail?</span>
          <svg class="mc-faq__icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
        </button>
        <div class="mc-faq__answer">
          <p>AcelleMail provides a built-in domain verification tool that guides you through setting up SPF, DKIM, and DMARC records for your sending domain. Add the DNS records to your domain provider, then use AcelleMail&rsquo;s verification panel to confirm everything is configured correctly. This works with any sending service including Amazon SES, SendGrid, Mailgun, or direct SMTP.</p>
        </div>
      </div>

      <div class="mc-faq__item">
        <button class="mc-faq__question" type="button" aria-expanded="false">
          <span>How often are security updates released?</span>
          <svg class="mc-faq__icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
        </button>
        <div class="mc-faq__answer">
          <p>AcelleMail releases regular updates through CodeCanyon that include security patches, bug fixes, and new features. As a license holder, you receive update notifications and can apply them on your own schedule. Since you have full source code access, your team can also review each update before deploying it to your production server.</p>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ======================================================================
     SECURITY — CTA
     ====================================================================== -->
<section class="mc-section mc-section--cream" style="padding: var(--space-4xl) 0;">
  <div class="mc-container">
    <div style="text-align: center; max-width: 680px; margin: 0 auto;">
      <h2 style="font-family: var(--font-serif); font-size: clamp(28px, 4vw, 42px); font-weight: 300; color: var(--mc-black); margin-bottom: var(--space-lg);">Ready to take control of your data?</h2>
      <p style="font-size: 18px; line-height: 1.6; color: var(--mc-gray); margin-bottom: var(--space-xl);">
        Stop trusting third parties with your subscriber data. With AcelleMail, everything runs on your server&mdash;full
        privacy, full control, no compromises. Try the demo or get your license today.
      </p>
      <div style="display: flex; gap: var(--space-md); justify-content: center; flex-wrap: wrap;">
        <a href="{{ route('pricing') }}" class="mc-btn mc-btn--primary mc-btn--lg">Get AcelleMail</a>
        <a href="{{ route('contact') }}" class="mc-btn mc-btn--secondary mc-btn--lg">Try the Demo</a>
      </div>
    </div>
  </div>
</section>

@endsection
