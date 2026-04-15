@extends('layouts.app')

@section('title', 'Integrations — Amazon SES, SendGrid, Mailgun, Stripe & More | AcelleMail')
@section('meta_description', 'Connect AcelleMail to Amazon SES, SendGrid, SparkPost, Mailgun, Postmark. Accept payments via Stripe, PayPal, Braintree. REST API for custom integrations.')
@section('og_title', 'Integrations & Sending Services — AcelleMail')

@section('content')

<!-- ======================================================================
     INTEGRATIONS — HERO
     ====================================================================== -->
<section class="mc-hero mc-hero--cream" style="padding: var(--space-4xl) 0; text-align: center;">
  <div class="mc-container mc-container--narrow">
    <p class="mc-hero__eyebrow" style="font-family: var(--font-sans); font-size: 13px; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mc-teal); margin-bottom: var(--space-md);">INTEGRATIONS &amp; SENDING SERVICES</p>
    <h1 class="mc-hero__title" style="font-family: var(--font-serif); font-size: clamp(32px, 5vw, 56px); font-weight: 300; line-height: 1.15; margin-bottom: var(--space-lg);">Connect AcelleMail to your favorite sending services, payment gateways, and tools</h1>
    <p class="mc-hero__subtitle" style="font-size: 18px; color: var(--mc-gray); max-width: 640px; margin: 0 auto var(--space-2xl); line-height: 1.6;">
      Connect to any SMTP service or payment gateway. Route emails through Amazon SES, SendGrid, or Mailgun &mdash; and accept payments via Stripe, PayPal, or Braintree.
    </p>
    <div class="mc-help-search" style="max-width: 560px; margin: 0 auto; position: relative;">
      <svg class="mc-help-search__icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--mc-gray);"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" class="mc-input mc-input--lg mc-help-search__input" placeholder="Search integrations..." style="width: 100%; padding: 14px 20px 14px 48px; border: 2px solid var(--mc-border); border-radius: var(--radius-pill); font-size: 16px; transition: border-color var(--transition-base);">
    </div>
  </div>
</section>

<!-- ======================================================================
     INTEGRATIONS — CATEGORY PILLS
     ====================================================================== -->
<section style="background: var(--mc-white); border-bottom: 1px solid var(--mc-border); padding: var(--space-lg) 0; position: sticky; top: 0; z-index: var(--z-sticky);">
  <div class="mc-container">
    <div style="display: flex; gap: var(--space-sm); overflow-x: auto; padding-bottom: var(--space-xs); -webkit-overflow-scrolling: touch; scrollbar-width: none;">
      <a href="{{ route('integrations') }}" style="display: inline-block; padding: 8px 20px; border-radius: var(--radius-pill); font-family: var(--font-sans); font-size: 14px; font-weight: 600; white-space: nowrap; text-decoration: none; background: var(--mc-black); color: var(--mc-white); transition: all var(--transition-fast);">All</a>
      <a href="#sending" style="display: inline-block; padding: 8px 20px; border-radius: var(--radius-pill); font-family: var(--font-sans); font-size: 14px; font-weight: 500; white-space: nowrap; text-decoration: none; background: var(--mc-light-gray); color: var(--mc-black); transition: all var(--transition-fast);">Sending Services</a>
      <a href="#payments" style="display: inline-block; padding: 8px 20px; border-radius: var(--radius-pill); font-family: var(--font-sans); font-size: 14px; font-weight: 500; white-space: nowrap; text-decoration: none; background: var(--mc-light-gray); color: var(--mc-black); transition: all var(--transition-fast);">Payment Gateways</a>
      <a href="#cms" style="display: inline-block; padding: 8px 20px; border-radius: var(--radius-pill); font-family: var(--font-sans); font-size: 14px; font-weight: 500; white-space: nowrap; text-decoration: none; background: var(--mc-light-gray); color: var(--mc-black); transition: all var(--transition-fast);">CMS &amp; Frameworks</a>
      <a href="#developer" style="display: inline-block; padding: 8px 20px; border-radius: var(--radius-pill); font-family: var(--font-sans); font-size: 14px; font-weight: 500; white-space: nowrap; text-decoration: none; background: var(--mc-light-gray); color: var(--mc-black); transition: all var(--transition-fast);">API &amp; Developer</a>
      <a href="#verification" style="display: inline-block; padding: 8px 20px; border-radius: var(--radius-pill); font-family: var(--font-sans); font-size: 14px; font-weight: 500; white-space: nowrap; text-decoration: none; background: var(--mc-light-gray); color: var(--mc-black); transition: all var(--transition-fast);">Verification</a>
      <a href="#storage" style="display: inline-block; padding: 8px 20px; border-radius: var(--radius-pill); font-family: var(--font-sans); font-size: 14px; font-weight: 500; white-space: nowrap; text-decoration: none; background: var(--mc-light-gray); color: var(--mc-black); transition: all var(--transition-fast);">Storage</a>
      <a href="#ipwarmup" style="display: inline-block; padding: 8px 20px; border-radius: var(--radius-pill); font-family: var(--font-sans); font-size: 14px; font-weight: 500; white-space: nowrap; text-decoration: none; background: var(--mc-light-gray); color: var(--mc-black); transition: all var(--transition-fast);">IP Warmup</a>
    </div>
  </div>
</section>

<!-- ======================================================================
     INTEGRATIONS — FEATURED (3 large cards)
     ====================================================================== -->
<section class="mc-section">
  <div class="mc-container">
    <h2 style="font-family: var(--font-serif); font-size: clamp(24px, 3vw, 36px); font-weight: 300; margin-bottom: var(--space-2xl);">Featured Integrations</h2>
    <div class="mc-grid mc-grid--3 mc-grid--gap-lg">

      <!-- Amazon SES -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__image mc-card__image--fixed" style="height: 200px; overflow: hidden;">
          <div style="width:100%;height:100%;background:linear-gradient(135deg,#FF9900,#FFB84D);display:flex;align-items:center;justify-content:center;"><svg width="64" height="64" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><rect width="40" height="40" rx="8" fill="rgba(255,255,255,0.2)"/><text x="20" y="26" text-anchor="middle" fill="white" font-family="Inter,sans-serif" font-weight="700" font-size="18">SES</text></svg></div>
        </div>
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-md);">
            <svg width="32" height="32" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><rect width="40" height="40" rx="6" fill="#FF9900"/><text x="20" y="22" text-anchor="middle" fill="white" font-family="Inter,sans-serif" font-weight="700" font-size="14">S</text></svg>
            <span style="font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--mc-teal); background: rgba(0,124,137,0.08); padding: 2px 8px; border-radius: var(--radius-pill);">Most Popular</span>
          </div>
          <h4 class="mc-card__title" style="font-family: var(--font-serif); font-size: 22px; font-weight: 400; margin-bottom: var(--space-sm);">Amazon SES</h4>
          <p class="mc-card__desc" style="color: var(--mc-gray); line-height: 1.6; margin-bottom: var(--space-md);">Send emails at $0.10 per 1,000 emails through Amazon Simple Email Service. AcelleMail handles the automation and list management while SES delivers at massive scale with industry-leading deliverability.</p>
          <span class="mc-card__link" style="color: var(--mc-teal); font-weight: 500;">Configure SES &rarr;</span>
        </div>
      </a>

      <!-- SendGrid -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__image mc-card__image--fixed" style="height: 200px; overflow: hidden;">
          <div style="width:100%;height:100%;background:linear-gradient(135deg,#1A82E2,#4DA6F0);display:flex;align-items:center;justify-content:center;"><svg width="64" height="64" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><rect width="40" height="40" rx="8" fill="rgba(255,255,255,0.2)"/><text x="20" y="26" text-anchor="middle" fill="white" font-family="Inter,sans-serif" font-weight="700" font-size="18">SG</text></svg></div>
        </div>
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-md);">
            <svg width="32" height="32" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><rect width="40" height="40" rx="6" fill="#1A82E2"/><text x="20" y="22" text-anchor="middle" fill="white" font-family="Inter,sans-serif" font-weight="700" font-size="14">S</text></svg>
            <span style="font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--mc-teal); background: rgba(0,124,137,0.08); padding: 2px 8px; border-radius: var(--radius-pill);">Popular</span>
          </div>
          <h4 class="mc-card__title" style="font-family: var(--font-serif); font-size: 22px; font-weight: 400; margin-bottom: var(--space-sm);">SendGrid</h4>
          <p class="mc-card__desc" style="color: var(--mc-gray); line-height: 1.6; margin-bottom: var(--space-md);">Connect AcelleMail to Twilio SendGrid for reliable email delivery with built-in analytics. Great for transactional and marketing emails with a free tier of 100 emails/day.</p>
          <span class="mc-card__link" style="color: var(--mc-teal); font-weight: 500;">Configure SendGrid &rarr;</span>
        </div>
      </a>

      <!-- Stripe -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__image mc-card__image--fixed" style="height: 200px; overflow: hidden;">
          <div style="width:100%;height:100%;background:linear-gradient(135deg,#635BFF,#8B85FF);display:flex;align-items:center;justify-content:center;"><svg width="64" height="64" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><rect width="40" height="40" rx="8" fill="rgba(255,255,255,0.2)"/><text x="20" y="26" text-anchor="middle" fill="white" font-family="Inter,sans-serif" font-weight="700" font-size="18">S</text></svg></div>
        </div>
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-sm); margin-bottom: var(--space-md);">
            <img src="{{ asset('images/integrations/stripe.png') }}" alt="Stripe" style="width: 32px; height: 32px; border-radius: var(--radius-sm);" fetchpriority="high">
            <span style="font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--mc-purple); background: rgba(107,63,160,0.08); padding: 2px 8px; border-radius: var(--radius-pill);">Payments</span>
          </div>
          <h4 class="mc-card__title" style="font-family: var(--font-serif); font-size: 22px; font-weight: 400; margin-bottom: var(--space-sm);">Stripe</h4>
          <p class="mc-card__desc" style="color: var(--mc-gray); line-height: 1.6; margin-bottom: var(--space-md);">Accept subscription payments and one-time charges through Stripe. Perfect for running AcelleMail as a SaaS email platform where your customers pay for sending plans.</p>
          <span class="mc-card__link" style="color: var(--mc-teal); font-weight: 500;">Configure Stripe &rarr;</span>
        </div>
      </a>

    </div>
  </div>
</section>

<!-- ======================================================================
     INTEGRATIONS — SENDING SERVICES (4-col grid)
     ====================================================================== -->
<section class="mc-section mc-section--light" id="sending">
  <div class="mc-container">
    <h2 style="font-family: var(--font-serif); font-size: clamp(24px, 3vw, 36px); font-weight: 300; margin-bottom: var(--space-sm);">Sending Services</h2>
    <p style="color: var(--mc-gray); font-size: 17px; line-height: 1.6; margin-bottom: var(--space-2xl); max-width: 600px;">Route your emails through any SMTP service or dedicated sending API. AcelleMail handles list management and automation &mdash; your sending service handles delivery.</p>
    <div class="mc-grid mc-grid--4 mc-grid--gap-md">

      <!-- Amazon SES -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><rect width="40" height="40" rx="8" fill="#FF9900"/><text x="20" y="26" text-anchor="middle" fill="white" font-family="Inter,sans-serif" font-weight="700" font-size="18">S</text></svg>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Amazon SES</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">The most cost-effective option at $0.10/1K emails. Massive scale, great deliverability, and built-in bounce/complaint handling.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Configure &rarr;</span>
        </div>
      </a>

      <!-- SendGrid -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><rect width="40" height="40" rx="8" fill="#1A82E2"/><text x="20" y="26" text-anchor="middle" fill="white" font-family="Inter,sans-serif" font-weight="700" font-size="18">S</text></svg>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">SendGrid</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Twilio SendGrid API integration with delivery analytics, dedicated IPs, and a free tier for getting started.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Configure &rarr;</span>
        </div>
      </a>

      <!-- SparkPost -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><rect width="40" height="40" rx="8" fill="#FA6423"/><text x="20" y="26" text-anchor="middle" fill="white" font-family="Inter,sans-serif" font-weight="700" font-size="18">S</text></svg>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">SparkPost</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Enterprise-grade email delivery with predictive analytics and advanced deliverability tools. Now part of MessageBird.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Configure &rarr;</span>
        </div>
      </a>

      <!-- Elastic Email -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><rect width="40" height="40" rx="8" fill="#2BAC76"/><text x="20" y="26" text-anchor="middle" fill="white" font-family="Inter,sans-serif" font-weight="700" font-size="18">E</text></svg>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Elastic Email</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Affordable email delivery API with pay-as-you-go pricing. Good option for small-to-medium volume senders.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Configure &rarr;</span>
        </div>
      </a>

      <!-- Mailgun -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><rect width="40" height="40" rx="8" fill="#F44336"/><text x="20" y="26" text-anchor="middle" fill="white" font-family="Inter,sans-serif" font-weight="700" font-size="18">M</text></svg>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Mailgun</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Developer-friendly email API by Sinch. Powerful routing, validation, and inbound processing with detailed analytics.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Configure &rarr;</span>
        </div>
      </a>

      <!-- Postmark -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><rect width="40" height="40" rx="8" fill="#FFCC00"/><text x="20" y="26" text-anchor="middle" fill="black" font-family="Inter,sans-serif" font-weight="700" font-size="18">P</text></svg>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Postmark</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Known for the fastest delivery times in the industry. Great for transactional emails that need to arrive instantly.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Configure &rarr;</span>
        </div>
      </a>

      <!-- Custom SMTP -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><rect width="40" height="40" rx="8" fill="var(--theme-service-gray)"/><text x="20" y="26" text-anchor="middle" fill="white" font-family="Inter,sans-serif" font-weight="700" font-size="18">S</text></svg>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Any SMTP Server</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Connect any standard SMTP server &mdash; Gmail, Outlook, your own mail server, or any provider that supports SMTP authentication.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Configure &rarr;</span>
        </div>
      </a>

      <!-- PHP Mail -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><rect width="40" height="40" rx="8" fill="#777BB4"/><text x="20" y="26" text-anchor="middle" fill="white" font-family="Inter,sans-serif" font-weight="700" font-size="18">P</text></svg>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">PHP Mail</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Use your server's built-in PHP mail function for sending. Zero configuration needed &mdash; works out of the box on most hosting.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Configure &rarr;</span>
        </div>
      </a>

    </div>
  </div>
</section>

<!-- ======================================================================
     INTEGRATIONS — IP WARMUP (partner section, 4-col grid)
     ====================================================================== -->
<section class="mc-section" id="ipwarmup">
  <div class="mc-container">
    <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-sm);">
      <h2 style="font-family: var(--font-serif); font-size: clamp(24px, 3vw, 36px); font-weight: 300;">IP Warmup</h2>
      <span style="font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--mc-teal); background: rgba(0,124,137,0.08); padding: 3px 10px; border-radius: var(--radius-pill);">Partner</span>
    </div>
    <p style="color: var(--mc-gray); font-size: 17px; line-height: 1.6; margin-bottom: var(--space-2xl); max-width: 600px;">Maximize your email deliverability with <a href="https://ipwarmup.com" target="_blank" style="color: var(--mc-teal); font-weight: 500;">IPwarmup.com</a> &mdash; AcelleMail's trusted partner for IP and domain reputation management. Warm up your sending infrastructure before going full volume.</p>
    <div class="mc-grid mc-grid--4 mc-grid--gap-md">

      <!-- Pre-warmed SMTP Server -->
      <a href="https://ipwarmup.com/pre-warmed-smtp-servers" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;" target="_blank">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><rect width="40" height="40" rx="8" fill="#0891B2"/><text x="20" y="26" text-anchor="middle" fill="white" font-family="Inter,sans-serif" font-weight="700" font-size="18">S</text></svg>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Pre-warmed SMTP Server</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Start sending at high volume right away with pre-warmed SMTP servers. Skip the warm-up period and get straight to your audience's inbox.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Learn more &rarr;</span>
        </div>
      </a>

      <!-- Email Warmup Service -->
      <a href="https://ipwarmup.com/email-warmup-service" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;" target="_blank">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><rect width="40" height="40" rx="8" fill="#F59E0B"/><text x="20" y="26" text-anchor="middle" fill="white" font-family="Inter,sans-serif" font-weight="700" font-size="18">E</text></svg>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Email Warmup Service</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Gradually build your sender reputation with automated email warm-up. Increase engagement signals and improve inbox placement rates over time.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Learn more &rarr;</span>
        </div>
      </a>

      <!-- Domain Warmup Service -->
      <a href="https://ipwarmup.com/domain-warmup-service" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;" target="_blank">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><rect width="40" height="40" rx="8" fill="#8B5CF6"/><text x="20" y="26" text-anchor="middle" fill="white" font-family="Inter,sans-serif" font-weight="700" font-size="18">D</text></svg>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Domain Warmup Service</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Establish domain trust with major email providers before launching campaigns. Protect your brand's domain reputation from day one.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Learn more &rarr;</span>
        </div>
      </a>

      <!-- IP Warmup Service -->
      <a href="https://ipwarmup.com/ip-warmup-service" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;" target="_blank">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><rect width="40" height="40" rx="8" fill="#10B981"/><text x="20" y="26" text-anchor="middle" fill="white" font-family="Inter,sans-serif" font-weight="700" font-size="18">IP</text></svg>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">IP Warmup Service</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Safely ramp up sending volume on new dedicated IPs. Automated scheduling ensures ISPs recognize your IP as a legitimate sender.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Learn more &rarr;</span>
        </div>
      </a>

    </div>
  </div>
</section>

<!-- ======================================================================
     INTEGRATIONS — PAYMENT GATEWAYS (4-col grid)
     ====================================================================== -->
<section class="mc-section" id="payments">
  <div class="mc-container">
    <h2 style="font-family: var(--font-serif); font-size: clamp(24px, 3vw, 36px); font-weight: 300; margin-bottom: var(--space-sm);">Payment Gateways</h2>
    <p style="color: var(--mc-gray); font-size: 17px; line-height: 1.6; margin-bottom: var(--space-2xl); max-width: 600px;">Accept payments for subscription plans when running AcelleMail as a SaaS email marketing platform for your customers.</p>
    <div class="mc-grid mc-grid--4 mc-grid--gap-md">

      <!-- PayPal -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><rect width="40" height="40" rx="8" fill="#003087"/><text x="20" y="26" text-anchor="middle" fill="white" font-family="Inter,sans-serif" font-weight="700" font-size="18">P</text></svg>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">PayPal</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Accept recurring subscription payments via PayPal. Customers can pay with their PayPal balance, bank account, or credit card.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Configure &rarr;</span>
        </div>
      </a>

      <!-- Stripe -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <img src="{{ asset('images/integrations/stripe.png') }}" alt="Stripe" style="width: 40px; height: 40px; border-radius: var(--radius-sm);" loading="lazy">
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Stripe</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Process credit card payments and manage subscriptions through Stripe. Supports recurring billing, invoicing, and automatic plan upgrades.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Configure &rarr;</span>
        </div>
      </a>

      <!-- Braintree -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><rect width="40" height="40" rx="8" fill="#4B3263"/><text x="20" y="26" text-anchor="middle" fill="white" font-family="Inter,sans-serif" font-weight="700" font-size="18">B</text></svg>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Braintree</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">PayPal's full-stack payment platform. Accept cards, PayPal, Venmo, and Apple Pay in a single integration.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Configure &rarr;</span>
        </div>
      </a>

      <!-- Paddle -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><rect width="40" height="40" rx="8" fill="#3B3B3B"/><text x="20" y="26" text-anchor="middle" fill="white" font-family="Inter,sans-serif" font-weight="700" font-size="18">P</text></svg>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Paddle</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Merchant of record for SaaS. Paddle handles sales tax, VAT, and global compliance so you can focus on your email platform.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Configure &rarr;</span>
        </div>
      </a>

      <!-- Razorpay -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: #2B84EA; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 18px; font-family: var(--font-sans);">R</div>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Razorpay</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Popular payment gateway for India and Southeast Asia. Accept UPI, net banking, cards, and wallets for subscription billing.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Configure &rarr;</span>
        </div>
      </a>

      <!-- Coinbase -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: #0052FF; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 18px; font-family: var(--font-sans);">C</div>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Coinbase Commerce</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Accept cryptocurrency payments for subscription plans. Support Bitcoin, Ethereum, and other major cryptocurrencies.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Configure &rarr;</span>
        </div>
      </a>

    </div>
  </div>
</section>

<!-- ======================================================================
     INTEGRATIONS — CMS & FRAMEWORKS (4-col grid)
     ====================================================================== -->
<section class="mc-section mc-section--light" id="cms">
  <div class="mc-container">
    <h2 style="font-family: var(--font-serif); font-size: clamp(24px, 3vw, 36px); font-weight: 300; margin-bottom: var(--space-sm);">CMS &amp; Frameworks</h2>
    <p style="color: var(--mc-gray); font-size: 17px; line-height: 1.6; margin-bottom: var(--space-2xl); max-width: 600px;">Embed subscription forms, sync contacts, and trigger automations from your website or web application.</p>
    <div class="mc-grid mc-grid--4 mc-grid--gap-md">

      <!-- WordPress -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <img src="{{ asset('images/integrations/wordpress.png') }}" alt="WordPress" style="width: 40px; height: 40px; border-radius: var(--radius-sm);" loading="lazy">
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">WordPress</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Add AcelleMail subscription forms to any WordPress site. Embed forms via shortcode or widget, sync subscribers automatically.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- Laravel -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: #FF2D20; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 18px; font-family: var(--font-sans);">L</div>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Laravel</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">AcelleMail is built on Laravel. Integrate directly with your Laravel app via the API, or extend AcelleMail with custom plugins.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- Any PHP App -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: #777BB4; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 18px; font-family: var(--font-sans);">P</div>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Any PHP Application</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Use the RESTful API from any PHP application. Add subscribers, trigger automations, and manage lists programmatically.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- WooCommerce -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <img src="{{ asset('images/integrations/woocommerce.png') }}" alt="WooCommerce" style="width: 40px; height: 40px; border-radius: var(--radius-sm);" loading="lazy">
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">WooCommerce</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Sync WooCommerce customers to AcelleMail lists. Trigger post-purchase emails, abandoned cart reminders, and product recommendations.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- Joomla -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: #5091CD; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 18px; font-family: var(--font-sans);">J</div>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Joomla</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Integrate AcelleMail with Joomla sites using the API. Add subscription forms and sync registered users to your mailing lists.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- Drupal -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: #0678BE; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 18px; font-family: var(--font-sans);">D</div>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Drupal</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Connect Drupal to AcelleMail via the REST API. Embed signup forms and sync user registrations to your subscriber lists.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

    </div>
  </div>
</section>

<!-- ======================================================================
     INTEGRATIONS — API & DEVELOPER (4-col grid)
     ====================================================================== -->
<section class="mc-section" id="developer">
  <div class="mc-container">
    <h2 style="font-family: var(--font-serif); font-size: clamp(24px, 3vw, 36px); font-weight: 300; margin-bottom: var(--space-sm);">API &amp; Developer Tools</h2>
    <p style="color: var(--mc-gray); font-size: 17px; line-height: 1.6; margin-bottom: var(--space-2xl); max-width: 600px;">Build custom integrations with AcelleMail's RESTful API. Manage subscribers, trigger automations, and sync data from any application.</p>
    <div class="mc-grid mc-grid--4 mc-grid--gap-md">

      <!-- REST API -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: var(--mc-teal); display: flex; align-items: center; justify-content: center;">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
            </div>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">AcelleMail API</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Full RESTful API access to lists, subscribers, campaigns, and automations. Add subscribers, trigger events, and manage your platform programmatically.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">View docs &rarr;</span>
        </div>
      </a>

      <!-- Webhooks -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: var(--mc-dark-gray); display: flex; align-items: center; justify-content: center;">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>
            </div>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Webhooks</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Receive real-time notifications when subscribers join, unsubscribe, open, or click. Push events to your own systems instantly.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Set up &rarr;</span>
        </div>
      </a>

      <!-- Zapier -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <img src="{{ asset('images/integrations/zapier.png') }}" alt="Zapier" style="width: 40px; height: 40px; border-radius: var(--radius-sm);" loading="lazy">
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Zapier</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Connect AcelleMail to 5,000+ apps with no code via Zapier. Automate subscriber management and trigger campaigns from any event.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Connect &rarr;</span>
        </div>
      </a>

      <!-- Embed Forms -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: #52BD94; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 18px; font-family: var(--font-sans);">E</div>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Embed Forms</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Generate embeddable HTML subscription forms for any website. Copy-paste the code and start collecting subscribers immediately.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Learn more &rarr;</span>
        </div>
      </a>

    </div>
  </div>
</section>

<!-- ======================================================================
     INTEGRATIONS — BUILD YOUR OWN (Feature Row with API Example)
     ====================================================================== -->
<section class="mc-section mc-section--cream">
  <div class="mc-container">
    <div class="mc-feature-row">
      <div class="mc-feature-row__image">
        <div style="background: var(--mc-black); border-radius: var(--radius-lg); padding: var(--space-2xl); color: var(--mc-white); font-family: 'Courier New', monospace; font-size: 13px; line-height: 1.7; overflow: hidden;">
          <div style="margin-bottom: var(--space-md);">
            <span style="color: var(--theme-code-method);">POST</span> <span style="color: var(--theme-code-keyword);">/api/v1/lists/{list_uid}/subscribers</span>
          </div>
          <div style="color: var(--theme-code-comment); margin-bottom: var(--space-sm);">// Add a subscriber via AcelleMail API</div>
          <div>{</div>
          <div>&nbsp;&nbsp;<span style="color: var(--theme-text);">"api_token"</span>: <span style="color: var(--theme-code-keyword);">"YOUR_API_TOKEN"</span>,</div>
          <div>&nbsp;&nbsp;<span style="color: var(--theme-text);">"EMAIL"</span>: <span style="color: var(--theme-code-keyword);">"user@example.com"</span>,</div>
          <div>&nbsp;&nbsp;<span style="color: var(--theme-text);">"FIRST_NAME"</span>: <span style="color: var(--theme-code-keyword);">"Jane"</span>,</div>
          <div>&nbsp;&nbsp;<span style="color: var(--theme-text);">"LAST_NAME"</span>: <span style="color: var(--theme-code-keyword);">"Doe"</span>,</div>
          <div>&nbsp;&nbsp;<span style="color: var(--theme-text);">"tag"</span>: <span style="color: var(--theme-code-keyword);">"vip, newsletter"</span></div>
          <div>}</div>
          <div style="margin-top: var(--space-md); color: var(--theme-code-keyword);">// Response: 200 OK — subscriber added</div>
        </div>
      </div>
      <div class="mc-feature-row__content">
        <p style="font-family: var(--font-sans); font-size: 13px; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase; color: var(--mc-teal); margin-bottom: var(--space-md);">FOR DEVELOPERS</p>
        <h2 class="mc-feature-row__title" style="font-family: var(--font-serif); font-size: clamp(24px, 3vw, 36px); font-weight: 300;">Build custom integrations with the AcelleMail API</h2>
        <p class="mc-feature-row__desc" style="font-size: 17px; line-height: 1.7; color: var(--mc-gray); margin-bottom: var(--space-lg);">
          AcelleMail's RESTful API gives you full control over lists, subscribers, campaigns, and automations. Build exactly the integration your business needs &mdash; from your own server.
        </p>
        <ul style="list-style: none; padding: 0; margin: 0 0 var(--space-xl) 0;">
          <li style="display: flex; align-items: flex-start; gap: var(--space-sm); margin-bottom: var(--space-md); font-size: 15px; color: var(--mc-dark-gray);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--mc-teal)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;"><polyline points="20 6 9 17 4 12"/></svg>
            <span><strong>Token-based authentication</strong> &mdash; simple API token auth for quick integration</span>
          </li>
          <li style="display: flex; align-items: flex-start; gap: var(--space-sm); margin-bottom: var(--space-md); font-size: 15px; color: var(--mc-dark-gray);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--mc-teal)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;"><polyline points="20 6 9 17 4 12"/></svg>
            <span><strong>Subscriber management</strong> &mdash; add, update, tag, and remove subscribers programmatically</span>
          </li>
          <li style="display: flex; align-items: flex-start; gap: var(--space-sm); margin-bottom: var(--space-md); font-size: 15px; color: var(--mc-dark-gray);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--mc-teal)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;"><polyline points="20 6 9 17 4 12"/></svg>
            <span><strong>Campaign API</strong> &mdash; create, schedule, and send campaigns via API calls</span>
          </li>
          <li style="display: flex; align-items: flex-start; gap: var(--space-sm); font-size: 15px; color: var(--mc-dark-gray);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--mc-teal)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;"><polyline points="20 6 9 17 4 12"/></svg>
            <span><strong>Open source</strong> &mdash; inspect, modify, and extend any API endpoint to fit your needs</span>
          </li>
        </ul>
        <a href="{{ route('features') }}" class="mc-btn mc-btn--primary">View API Documentation</a>
      </div>
    </div>
  </div>
</section>

<!-- ======================================================================
     INTEGRATIONS — STATS
     ====================================================================== -->
<section class="mc-stats-section">
  <div class="mc-container">
    <div class="mc-stats-section__header">
      <h2 class="mc-stats-section__heading">Trusted by thousands of self-hosted installations worldwide</h2>
      <p class="mc-stats-section__subheading">AcelleMail powers email marketing for businesses, agencies, and SaaS providers who want full control over their platform.</p>
    </div>
    <div class="mc-stats-section__grid">
      <div class="mc-stats-section__item">
        <span class="mc-stats-section__number">50K+</span>
        <span class="mc-stats-section__label">Installations</span>
        <p class="mc-stats-section__desc">Self-hosted AcelleMail instances running on servers around the world.</p>
      </div>
      <div class="mc-stats-section__item">
        <span class="mc-stats-section__number">7+</span>
        <span class="mc-stats-section__label">Sending Services</span>
        <p class="mc-stats-section__desc">Built-in support for Amazon SES, SendGrid, SparkPost, Mailgun, Postmark, Elastic Email, and any SMTP.</p>
      </div>
      <div class="mc-stats-section__item">
        <span class="mc-stats-section__number">100%</span>
        <span class="mc-stats-section__label">Open Source</span>
        <p class="mc-stats-section__desc">Full source code access. Customize, extend, and integrate with anything you need.</p>
      </div>
      <div class="mc-stats-section__item">
        <span class="mc-stats-section__number">$0</span>
        <span class="mc-stats-section__label">Per-email fees</span>
        <p class="mc-stats-section__desc">Pay only for your server and sending service. No per-subscriber or per-email platform charges.</p>
      </div>
    </div>
  </div>
</section>

<!-- ======================================================================
     INTEGRATIONS — CTA (Don't see your tool?)
     ====================================================================== -->
<section class="mc-section" style="padding: var(--space-4xl) 0;">
  <div class="mc-container">
    <div style="max-width: 700px; margin: 0 auto; text-align: center;">
      <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--mc-cream); display: flex; align-items: center; justify-content: center; margin: 0 auto var(--space-lg);">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--mc-teal)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
      </div>
      <h2 style="font-family: var(--font-serif); font-size: clamp(24px, 3.5vw, 40px); font-weight: 300; margin-bottom: var(--space-md);">Need a custom integration?</h2>
      <p style="font-size: 17px; color: var(--mc-gray); line-height: 1.7; margin-bottom: var(--space-lg); max-width: 560px; margin-left: auto; margin-right: auto;">
        AcelleMail is open source and built on Laravel. Use the RESTful API to connect any tool, or modify the source code directly to add custom sending drivers and payment gateways.
      </p>
      <div style="display: flex; gap: var(--space-md); justify-content: center; flex-wrap: wrap;">
        <a href="{{ route('contact') }}" class="mc-btn mc-btn--primary">Request an Integration</a>
        <a href="{{ route('features') }}" class="mc-btn mc-btn--secondary" style="border: 2px solid var(--mc-border); border-radius: var(--radius-btn); padding: 12px 28px; font-family: var(--font-sans); font-size: 15px; font-weight: 600; text-decoration: none; color: var(--mc-black); transition: all var(--transition-fast);">Explore the API</a>
      </div>
    </div>
  </div>
</section>

<!-- ======================================================================
     INTEGRATIONS — MORE INTEGRATIONS (Additional grid)
     ====================================================================== -->
<section class="mc-section mc-section--cream" id="verification">
  <div class="mc-container">
    <h2 style="font-family: var(--font-serif); font-size: clamp(24px, 3vw, 36px); font-weight: 300; margin-bottom: var(--space-sm); text-align: center;">More Integrations &amp; Tools</h2>
    <p style="color: var(--mc-gray); font-size: 17px; line-height: 1.6; margin-bottom: var(--space-2xl); max-width: 600px; text-align: center; margin-left: auto; margin-right: auto;">Verification services, storage providers, and more tools to extend your AcelleMail installation.</p>
    <div class="mc-grid mc-grid--4 mc-grid--gap-md">

      <!-- Email Verification -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><rect width="40" height="40" rx="8" fill="#10B981"/><text x="20" y="26" text-anchor="middle" fill="white" font-family="Inter,sans-serif" font-weight="700" font-size="18">V</text></svg>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Email Verification</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Built-in email verification to clean your lists. Remove invalid addresses, reduce bounces, and protect your sender reputation.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Learn more &rarr;</span>
        </div>
      </a>

      <!-- AWS S3 Storage -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;" id="storage">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><rect width="40" height="40" rx="8" fill="#569A31"/><text x="20" y="26" text-anchor="middle" fill="white" font-family="Inter,sans-serif" font-weight="700" font-size="18">S</text></svg>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">AWS S3 Storage</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Store email templates, images, and attachments on Amazon S3. Offload file storage from your server for better performance.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Configure &rarr;</span>
        </div>
      </a>

      <!-- reCAPTCHA -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><rect width="40" height="40" rx="8" fill="#4285F4"/><text x="20" y="26" text-anchor="middle" fill="white" font-family="Inter,sans-serif" font-weight="700" font-size="18">R</text></svg>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Google reCAPTCHA</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Protect subscription forms from bots and spam signups with Google reCAPTCHA. Keep your lists clean from day one.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Configure &rarr;</span>
        </div>
      </a>

      <!-- DKIM/SPF -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><rect width="40" height="40" rx="8" fill="#F59E0B"/><text x="20" y="26" text-anchor="middle" fill="black" font-family="Inter,sans-serif" font-weight="700" font-size="18">D</text></svg>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">DKIM &amp; SPF Setup</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Built-in tools to configure DKIM signing and SPF records. Authenticate your emails and improve deliverability across all sending services.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Learn more &rarr;</span>
        </div>
      </a>

      <!-- Tracking Domain -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <svg width="40" height="40" viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg"><rect width="40" height="40" rx="8" fill="#6366F1"/><text x="20" y="26" text-anchor="middle" fill="white" font-family="Inter,sans-serif" font-weight="700" font-size="18">T</text></svg>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Custom Tracking Domain</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Use your own domain for tracking links and open pixels. Improve brand trust and avoid shared-domain blacklisting.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Configure &rarr;</span>
        </div>
      </a>

      <!-- Google Analytics -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <img src="{{ asset('images/integrations/google-analytics.png') }}" alt="Google Analytics" style="width: 40px; height: 40px; border-radius: var(--radius-sm);" loading="lazy">
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Google Analytics</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Automatically append UTM parameters to all campaign links. Track email-driven traffic and conversions in Google Analytics.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Configure &rarr;</span>
        </div>
      </a>

      <!-- Multi-Tenant -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: #000000; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 18px; font-family: var(--font-sans);">M</div>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Multi-Tenant / SaaS</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Run AcelleMail as a multi-tenant SaaS platform. Create customer accounts with their own plans, sending limits, and branding.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Learn more &rarr;</span>
        </div>
      </a>

      <!-- Cron Jobs -->
      <a href="{{ route('integrations') }}" class="mc-card mc-card--bordered" style="text-decoration: none; color: inherit;">
        <div class="mc-card__body" style="padding: var(--space-lg);">
          <div style="display: flex; align-items: center; gap: var(--space-md); margin-bottom: var(--space-md);">
            <div style="width: 40px; height: 40px; border-radius: var(--radius-sm); background: #00C4CC; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 18px; font-family: var(--font-sans);">C</div>
            <h4 style="font-family: var(--font-sans); font-size: 16px; font-weight: 600;">Cron &amp; Queue Workers</h4>
          </div>
          <p style="color: var(--mc-gray); font-size: 14px; line-height: 1.5; margin-bottom: var(--space-md);">Configure cron jobs and queue workers for reliable campaign delivery. Process automations and scheduled sends in the background.</p>
          <span style="color: var(--mc-teal); font-size: 14px; font-weight: 500;">Set up &rarr;</span>
        </div>
      </a>

    </div>
  </div>
</section>

<!-- ======================================================================
     INTEGRATIONS — BROWSE ALL CTA
     ====================================================================== -->
<section class="mc-section" style="padding: var(--space-3xl) 0;">
  <div class="mc-container">
    <div style="display: flex; align-items: center; justify-content: space-between; background: var(--mc-light-gray); border-radius: var(--radius-lg); padding: var(--space-2xl) var(--space-3xl); flex-wrap: wrap; gap: var(--space-lg);">
      <div style="flex: 1; min-width: 280px;">
        <h3 style="font-family: var(--font-serif); font-size: 24px; font-weight: 400; margin-bottom: var(--space-sm);">Ready to take control of your email marketing?</h3>
        <p style="color: var(--mc-gray); font-size: 15px; line-height: 1.6;">Download AcelleMail, connect your sending service, and start sending &mdash; setup takes minutes.</p>
      </div>
      <div style="display: flex; gap: var(--space-md); flex-wrap: wrap;">
        <a href="{{ route('pricing') }}" class="mc-btn mc-btn--primary">Get Started</a>
        <a href="{{ route('features') }}" class="mc-link mc-link--arrow" style="font-size: 16px; font-weight: 500; display: flex; align-items: center; color: var(--mc-teal); text-decoration: none;">View all features &rarr;</a>
      </div>
    </div>
  </div>
</section>
@endsection

@push('jsonld')
@include('partials.seo.jsonld-breadcrumb', ['breadcrumbTitle' => 'Integrations'])
@endpush
