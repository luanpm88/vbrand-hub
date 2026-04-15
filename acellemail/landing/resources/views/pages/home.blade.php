@extends('layouts.app')

@section('title', 'AcelleMail — Self-Hosted Email Marketing Platform | No Monthly Fees')
@section('meta_description', 'Self-hosted email marketing with full source code. Send unlimited emails via Amazon SES, SendGrid, or any SMTP. One-time $64 license. 50,000+ downloads.')
@section('og_title', 'AcelleMail — Own Your Email Marketing')

@section('content')

<!-- Hero Section -->
<section class="mc-hero">
    <div class="mc-container">
        <div class="mc-hero__grid">
            <div class="mc-hero__content">
                <p class="mc-hero__eyebrow">THE SELF-HOSTED EMAIL MARKETING PLATFORM FOR FULL CONTROL</p>
                <h1 class="mc-hero__heading">Own Your Email Marketing</h1>
                <p class="mc-hero__subheading">Full source code, no recurring fees. Send unlimited emails with Amazon SES, SendGrid, or any SMTP service.</p>
                <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" class="mc-btn mc-btn--primary mc-btn--lg">Buy on CodeCanyon</a>
                <div class="mc-hero__reviews">
                    <img src="{{ asset('images/icons/stars-4-5.png') }}" alt="4.6 stars" class="mc-hero__stars" fetchpriority="high">
                    <span class="mc-hero__reviews-text">Based on 1,200+ reviews on</span>
                    <div class="mc-hero__review-badges">
                        <span style="font-weight: 700; font-size: 14px; color: #82B541; letter-spacing: -0.3px;">envato</span>
                        <span style="font-weight: 600; font-size: 13px; color: var(--mc-gray); opacity: 0.7;">CodeCanyon</span>
                    </div>
                </div>
            </div>
            <div class="mc-hero__image">
                <img src="{{ $themeImg('images/hero/home-hero.svg') }}" alt="AcelleMail email marketing platform" loading="lazy">
            </div>
        </div>
    </div>
</section>

<!-- Social Proof Text -->
<section class="mc-social-proof">
    <div class="mc-container">
        <h2 class="mc-social-proof__heading">50,000+ businesses run their own email marketing &mdash; with zero monthly fees</h2>
    </div>
</section>

<!-- Feature Cards -->
<section class="mc-feature-cards">
    <div class="mc-container">
        <div class="mc-feature-cards__grid">
            <div class="mc-feature-cards__item">
                <div class="mc-feature-cards__image">
                    <img src="{{ $themeImg('images/features/email-sms.svg') }}" alt="Email marketing" loading="lazy">
                </div>
                <h3 class="mc-feature-cards__title">Drag &amp; drop email builder</h3>
                <p class="mc-feature-cards__desc">Create stunning email campaigns with a visual editor. Choose from 100+ templates or build from scratch &mdash; no coding required.</p>
                <a href="{{ route('features') }}" class="mc-feature-cards__link">Learn about the email builder &rarr;</a>
            </div>
            <div class="mc-feature-cards__item">
                <div class="mc-feature-cards__image">
                    <img src="{{ $themeImg('images/features/automations-ecom.svg') }}" alt="Marketing automation" loading="lazy">
                </div>
                <h3 class="mc-feature-cards__title">Powerful automation flows</h3>
                <p class="mc-feature-cards__desc">Set up welcome series, drip campaigns, and triggered emails. Automate your marketing and engage subscribers on autopilot.</p>
                <a href="{{ route('features') }}" class="mc-feature-cards__link">Explore automation features &rarr;</a>
            </div>
            <div class="mc-feature-cards__item">
                <div class="mc-feature-cards__image">
                    <img src="{{ $themeImg('images/features/switch-brands.svg') }}" alt="Marketing automation" loading="lazy">
                </div>
                <h3 class="mc-feature-cards__title">Your server, your data</h3>
                <p class="mc-feature-cards__desc">Install on your own server in minutes. Full source code access, complete data ownership, and no vendor lock-in ever.</p>
                <a href="{{ route('features') }}" class="mc-feature-cards__link">See why self-hosted wins &rarr;</a>
            </div>
        </div>
    </div>
</section>

<!-- Works With — Sending Services Showcase -->
<section class="mc-services-showcase">
    <div class="mc-container">
        <div class="mc-services-showcase__header">
            <h2 class="mc-services-showcase__heading">Connect to any<br>sending service</h2>
            <p class="mc-services-showcase__desc">Plug in your favorite email provider and start sending in minutes. AcelleMail supports all major delivery services out of the box.</p>
        </div>
        <div class="mc-services-showcase__grid">
            <div class="mc-services-showcase__item"><img src="{{ asset('images/services/amazon-ses-logo.svg') }}" alt="Amazon SES" loading="lazy"></div>
            <div class="mc-services-showcase__item"><img src="{{ asset('images/services/sendgrid-logo.svg') }}" alt="SendGrid" loading="lazy"></div>
            <div class="mc-services-showcase__item"><img src="{{ asset('images/services/sparkpost-logo.svg') }}" alt="SparkPost" loading="lazy"></div>
            <div class="mc-services-showcase__item"><img src="{{ asset('images/services/mailgun-logo.svg') }}" alt="Mailgun" loading="lazy"></div>
            <div class="mc-services-showcase__item"><img src="{{ asset('images/services/postmark-logo.svg') }}" alt="Postmark" loading="lazy"></div>
            <div class="mc-services-showcase__item"><img src="{{ asset('images/services/elastic-email-logo.svg') }}" alt="Elastic Email" loading="lazy"></div>
        </div>
    </div>
</section>

<!-- ======================================================================
     NEW: Email Template Builder Feature
     ====================================================================== -->
<section class="mc-feature-alt">
    <div class="mc-container">
        <div class="mc-feature-alt__grid">
            <div class="mc-feature-alt__image">
                <img src="{{ $themeImg('images/features/email-sms.svg') }}" alt="Drag-and-drop email builder" loading="lazy">
            </div>
            <div class="mc-feature-alt__content">
                <span class="mc-eyebrow">Email Template Builder</span>
                <h2 class="mc-feature-alt__heading">Design beautiful emails in minutes</h2>
                <p class="mc-feature-alt__text">Use the built-in drag-and-drop editor to create professional emails that render perfectly on every device. Pick from 100+ responsive templates or craft your own with the flexible visual builder &mdash; all included with your license.</p>
                <a href="{{ route('features') }}" class="mc-feature-alt__link">Explore the email builder &rarr;</a>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     NEW: Marketing Automation Feature
     ====================================================================== -->
<section class="mc-feature-alt mc-feature-alt--reverse" style="background: var(--mc-cream);">
    <div class="mc-container">
        <div class="mc-feature-alt__grid">
            <div class="mc-feature-alt__image">
                <img src="{{ $themeImg('images/features/automations-ecom.svg') }}" alt="Marketing automation workflows" loading="lazy">
            </div>
            <div class="mc-feature-alt__content">
                <span class="mc-eyebrow">Marketing Automations</span>
                <h2 class="mc-feature-alt__heading">Send the right message at the right time</h2>
                <p class="mc-feature-alt__text">Build automated email sequences that nurture leads and convert subscribers. From welcome series to re-engagement flows, AcelleMail&rsquo;s automation engine runs 24/7 on your own infrastructure.</p>
                <ul class="mc-feature-list">
                    <li class="mc-feature-list__item">Welcome emails that onboard new subscribers</li>
                    <li class="mc-feature-list__item">Drip campaigns that nurture leads over time</li>
                    <li class="mc-feature-list__item">Re-engagement sequences for inactive contacts</li>
                    <li class="mc-feature-list__item">Date-based triggers for birthdays and events</li>
                </ul>
                <a href="{{ route('features') }}" class="mc-feature-alt__link">See all automations &rarr;</a>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     NEW: Advanced Analytics
     ====================================================================== -->
<section class="mc-feature-alt">
    <div class="mc-container">
        <div class="mc-feature-alt__grid">
            <div class="mc-feature-alt__image">
                <img src="{{ $themeImg('images/features/predictive.svg') }}" alt="Email analytics dashboard" loading="lazy">
            </div>
            <div class="mc-feature-alt__content">
                <span class="mc-eyebrow">Analytics &amp; Reporting</span>
                <h2 class="mc-feature-alt__heading">Know what&rsquo;s working. Optimize what matters.</h2>
                <p class="mc-feature-alt__text">Track opens, clicks, bounces, and conversions in real time. AcelleMail&rsquo;s built-in reporting gives you full visibility into campaign performance so you can refine your strategy with every send.</p>
                <a href="{{ route('features') }}" class="mc-feature-alt__link">Explore analytics &rarr;</a>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="mc-stats-section">
    <div class="mc-container">
        <div class="mc-stats-section__header">
            <h2 class="mc-stats-section__heading">Trusted by thousands of businesses worldwide</h2>
            <p class="mc-stats-section__subheading">Since 2016, AcelleMail has helped over 50,000 businesses take control of their email marketing.</p>
        </div>
        <div class="mc-stats-section__grid">
            <div class="mc-stats-section__item">
                <span class="mc-stats-section__number">50K+</span>
                <span class="mc-stats-section__label">downloads</span>
                <p class="mc-stats-section__desc">Over 50,000 copies sold on CodeCanyon, making it the top-rated email marketing script.</p>
            </div>
            <div class="mc-stats-section__item">
                <span class="mc-stats-section__number">4.6★</span>
                <span class="mc-stats-section__label">rating</span>
                <p class="mc-stats-section__desc">Consistently rated 4.6 out of 5 stars with over 1,200 verified reviews on Envato Market.</p>
            </div>
            <div class="mc-stats-section__item">
                <span class="mc-stats-section__number">100+</span>
                <span class="mc-stats-section__label">templates</span>
                <p class="mc-stats-section__desc">Pre-built responsive email templates included free with every license purchase.</p>
            </div>
            <div class="mc-stats-section__item">
                <span class="mc-stats-section__number">$0</span>
                <span class="mc-stats-section__label">monthly fees</span>
                <p class="mc-stats-section__desc">One-time payment starting at $64. No subscriptions, no per-email charges, no hidden costs.</p>
            </div>
        </div>
    </div>
</section>


<!-- ======================================================================
     NEW: SMTP Flexibility
     ====================================================================== -->
<section class="mc-section" style="background: var(--mc-white);">
    <div class="mc-container">
        <div class="mc-hero__content--center" style="margin-bottom: var(--space-3xl);">
            <span class="mc-eyebrow">Flexible Sending</span>
            <h2>Connect any SMTP or sending service</h2>
            <p class="mc-text-lg" style="margin-top: var(--space-md);">AcelleMail works with all major email delivery providers. Choose the service that fits your budget and scale &mdash; switch anytime without losing a single subscriber.</p>
        </div>
        <div class="mc-grid mc-grid--3 mc-grid--gap-lg">
            <div class="mc-card mc-card--bordered">
                <div class="mc-card__body mc-card__body--lg">
                    <div style="font-size: 40px; margin-bottom: var(--space-md);">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--mc-teal)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                    </div>
                    <h4 class="mc-card__title">Amazon SES</h4>
                    <p class="mc-card__desc">Send emails at just $0.10 per 1,000 emails using Amazon SES. The most cost-effective option for high-volume senders.</p>
                    <span class="mc-card__link">Learn more</span>
                </div>
            </div>
            <div class="mc-card mc-card--bordered">
                <div class="mc-card__body mc-card__body--lg">
                    <div style="font-size: 40px; margin-bottom: var(--space-md);">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--mc-teal)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    </div>
                    <h4 class="mc-card__title">SendGrid &amp; SparkPost</h4>
                    <p class="mc-card__desc">Plug in your SendGrid or SparkPost API key and start sending in minutes. Full delivery tracking and bounce handling built in.</p>
                    <span class="mc-card__link">Learn more</span>
                </div>
            </div>
            <div class="mc-card mc-card--bordered">
                <div class="mc-card__body mc-card__body--lg">
                    <div style="font-size: 40px; margin-bottom: var(--space-md);">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--mc-teal)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <h4 class="mc-card__title">Any SMTP Server</h4>
                    <p class="mc-card__desc">Use Mailgun, Elastic Email, Postmark, or your own SMTP server. AcelleMail supports any standard SMTP connection.</p>
                    <span class="mc-card__link">Learn more</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     NEW: Deliverability & Email Verification
     ====================================================================== -->
<section class="mc-feature-alt" style="background: var(--mc-light-gray);">
    <div class="mc-container">
        <div class="mc-feature-alt__grid">
            <div class="mc-feature-alt__image">
                <img src="{{ $themeImg('images/features/reach-inboxes.svg') }}" alt="Deliverability and verification" loading="lazy">
            </div>
            <div class="mc-feature-alt__content">
                <span class="mc-eyebrow">Deliverability &amp; Verification</span>
                <h2 class="mc-feature-alt__heading">Reach inboxes, not spam folders</h2>
                <p class="mc-feature-alt__text">AcelleMail includes built-in email verification to clean your lists before sending. Combined with automatic bounce handling and feedback loop processing, your sender reputation stays healthy.</p>
                <ul class="mc-feature-list">
                    <li class="mc-feature-list__item">Built-in email verification service</li>
                    <li class="mc-feature-list__item">SPF, DKIM, and DMARC configuration guides</li>
                    <li class="mc-feature-list__item">Automatic bounce and complaint handling</li>
                    <li class="mc-feature-list__item">IP warmup scheduling for new senders</li>
                </ul>
                <a href="{{ route('features') }}" class="mc-feature-alt__link">Learn about deliverability &rarr;</a>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     NEW: List Management & Segmentation
     ====================================================================== -->
<section class="mc-feature-alt mc-feature-alt--reverse">
    <div class="mc-container">
        <div class="mc-feature-alt__grid">
            <div class="mc-feature-alt__image">
                <img src="{{ $themeImg('images/features/predictive.svg') }}" alt="List management and segmentation" loading="lazy">
            </div>
            <div class="mc-feature-alt__content">
                <span class="mc-eyebrow">List Management</span>
                <h2 class="mc-feature-alt__heading">Organize subscribers. Target with precision.</h2>
                <p class="mc-feature-alt__text">Import unlimited subscribers, segment by custom fields, and manage multiple lists from one dashboard. AcelleMail&rsquo;s segmentation engine lets you deliver the right content to the right audience every time.</p>
                <a href="{{ route('features') }}" class="mc-feature-alt__link">Explore list management &rarr;</a>
            </div>
        </div>
    </div>
</section>

<!-- Pricing CTA -->
<section class="mc-plan-cta" style="background: var(--mc-cream);">
    <div class="mc-container">
        <div class="mc-plan-cta__inner">
            <div class="mc-plan-cta__image">
                <img src="{{ $themeImg('images/features/pricing-hero.svg') }}" alt="AcelleMail pricing — one-time purchase" loading="lazy">
            </div>
            <div class="mc-plan-cta__content">
                <h2 class="mc-plan-cta__heading">One-time purchase. Lifetime ownership.</h2>
                <p class="mc-plan-cta__text">Get AcelleMail for just $64 (Regular License) or $199 (Extended License for SaaS). Full source code, free updates, and six months of support included with every purchase.</p>
                <ul class="mc-plan-cta__features">
                    <li class="mc-plan-cta__feature">
                        <svg class="mc-plan-cta__check" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Full PHP/Laravel source code
                    </li>
                    <li class="mc-plan-cta__feature">
                        <svg class="mc-plan-cta__check" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Unlimited subscribers and emails
                    </li>
                    <li class="mc-plan-cta__feature">
                        <svg class="mc-plan-cta__check" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Marketing automation engine
                    </li>
                    <li class="mc-plan-cta__feature">
                        <svg class="mc-plan-cta__check" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Drag-and-drop email builder
                    </li>
                    <li class="mc-plan-cta__feature">
                        <svg class="mc-plan-cta__check" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        GDPR-compliant data handling
                    </li>
                    <li class="mc-plan-cta__feature">
                        <svg class="mc-plan-cta__check" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Free lifetime updates included
                    </li>
                </ul>
                <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" class="mc-btn mc-btn--primary mc-btn--lg">Buy on CodeCanyon</a>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     NEW: SaaS Framework
     ====================================================================== -->
<section class="mc-section">
    <div class="mc-container">
        <div class="mc-hero__content--center" style="margin-bottom: var(--space-3xl);">
            <h2>Launch your own email marketing SaaS</h2>
            <p class="mc-text-lg" style="margin-top: var(--space-md);">The Extended License includes a complete multi-tenant SaaS framework. Create subscription plans, accept payments via PayPal, Stripe, Braintree, or Paddle, and start selling email marketing as a service.</p>
        </div>
        <div class="mc-grid mc-grid--2 mc-grid--gap-lg">
            <a href="{{ route('features') }}" class="mc-card mc-card--bordered">
                <div class="mc-card__image mc-card__image--fixed">
                    <img src="{{ $themeImg('images/features/automations-ecom.svg') }}" alt="Multi-tenant SaaS platform" loading="lazy">
                </div>
                <div class="mc-card__body">
                    <h4 class="mc-card__title">Multi-Tenant Architecture</h4>
                    <p class="mc-card__desc">Each customer gets their own workspace with separate lists, campaigns, automations, and sending servers. Full white-label support.</p>
                    <span class="mc-card__link">Learn about SaaS features</span>
                </div>
            </a>
            <a href="{{ route('features') }}" class="mc-card mc-card--bordered">
                <div class="mc-card__image mc-card__image--fixed">
                    <img src="{{ $themeImg('images/features/predictive.svg') }}" alt="Subscription billing and plans" loading="lazy">
                </div>
                <div class="mc-card__body">
                    <h4 class="mc-card__title">Built-in Subscription Billing</h4>
                    <p class="mc-card__desc">Create flexible pricing plans with subscriber limits, sending quotas, and feature gates. Integrated with PayPal, Stripe, Braintree, and Paddle.</p>
                    <span class="mc-card__link">See billing options</span>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- ======================================================================
     NEW: Open Source & Developer Friendly
     ====================================================================== -->
<section class="mc-section mc-section--cream">
    <div class="mc-container">
        <div class="mc-feature-row">
            <div class="mc-feature-row__content">
                <span class="mc-eyebrow">Full Source Code</span>
                <h2 class="mc-feature-row__title">Built on Laravel &mdash; customize everything</h2>
                <p class="mc-feature-row__desc">AcelleMail is built with PHP and Laravel, the most popular PHP framework. Developers can extend, modify, and white-label the entire platform. Add custom integrations, build new features, or rebrand it as your own product.</p>
                <div class="mc-hero__actions">
                    <a href="https://demo.acellemail.com" class="mc-btn mc-btn--primary">Try Live Demo</a>
                    <a href="{{ route('pricing') }}" class="mc-btn mc-btn--secondary">See pricing</a>
                </div>
            </div>
            <div class="mc-feature-row__image">
                <img src="{{ $themeImg('images/features/email-sms.svg') }}" alt="Laravel PHP source code" loading="lazy">
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     NEW: Installation & Hosting
     ====================================================================== -->
<section class="mc-section">
    <div class="mc-container">
        <div class="mc-feature-row mc-feature-row--reverse">
            <div class="mc-feature-row__content">
                <span class="mc-eyebrow">Easy Installation</span>
                <h2 class="mc-feature-row__title">Install on any server &mdash; up and running in minutes</h2>
                <p class="mc-feature-row__desc">AcelleMail runs on any Linux server with PHP 8.x and MySQL. Use the one-click installer or follow the step-by-step guide. Works perfectly on shared hosting, VPS, or dedicated servers.</p>
                <ul class="mc-feature-list">
                    <li class="mc-feature-list__item">One-click web-based installer</li>
                    <li class="mc-feature-list__item">Works on shared hosting, VPS, or cloud</li>
                    <li class="mc-feature-list__item">Detailed documentation and video guides</li>
                    <li class="mc-feature-list__item">Active community and priority support</li>
                </ul>
                <a href="https://knowledge.acellemail.com/category/installation-setup" class="mc-btn mc-btn--primary" target="_blank">View documentation</a>
            </div>
            <div class="mc-feature-row__image">
                <img src="{{ $themeImg('images/features/installation_setup.svg') }}" alt="Easy installation on any server" loading="lazy">
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     NEW: Testimonial / Case Study
     ====================================================================== -->
<section class="mc-case-study">
    <div class="mc-container">
        <div class="mc-case-study__inner">
            <div class="mc-case-study__image">
                <img src="{{ $themeImg('images/features/case-study-savings.svg') }}" alt="Savings case study" loading="lazy">
            </div>
            <div class="mc-case-study__content">
                <blockquote class="mc-case-study__quote">
                    &ldquo;Switching from Mailchimp to AcelleMail saved us over $12,000 per year. We send 500K emails monthly through Amazon SES for under $50 &mdash; and we own all our data.&rdquo;
                </blockquote>
                <p class="mc-case-study__attribution">&mdash; David Park, CTO at ScaleUp Digital Agency</p>
                <div class="mc-case-study__stats">
                    <div class="mc-case-study__stat">
                        <span class="mc-case-study__stat-number">$12K+</span>
                        <span class="mc-case-study__stat-label">Saved per year</span>
                    </div>
                    <div class="mc-case-study__stat-divider"></div>
                    <div class="mc-case-study__stat">
                        <span class="mc-case-study__stat-number">500K</span>
                        <span class="mc-case-study__stat-label">Emails per month</span>
                    </div>
                    <div class="mc-case-study__stat-divider"></div>
                    <div class="mc-case-study__stat">
                        <span class="mc-case-study__stat-number">$50</span>
                        <span class="mc-case-study__stat-label">Monthly sending cost</span>
                    </div>
                </div>
                <a href="{{ route('about') }}" class="mc-case-study__link">Read the full story &rarr;</a>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     NEW: Security & Compliance
     ====================================================================== -->
<section class="mc-section">
    <div class="mc-container">
        <div class="mc-hero__content--center" style="margin-bottom: var(--space-2xl);">
            <h2>Your data stays on your server &mdash; always</h2>
            <p class="mc-text-lg" style="margin-top: var(--space-md);">AcelleMail is self-hosted, so your subscriber data never leaves your infrastructure. Built-in GDPR tools, CAN-SPAM compliance, and role-based access control keep you protected.</p>
        </div>
        <div class="mc-grid mc-grid--4 mc-grid--gap-md">
            <div class="mc-card mc-card--bordered" style="text-align: center;">
                <div class="mc-card__body mc-card__body--lg">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--mc-teal)" stroke-width="1.5" style="margin: 0 auto var(--space-md);"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <h5 style="font-family: var(--font-sans); font-weight: 600; margin-bottom: var(--space-xs);">Self-Hosted</h5>
                    <p class="mc-text-sm" style="color: var(--mc-gray);">Complete data ownership on your own server</p>
                </div>
            </div>
            <div class="mc-card mc-card--bordered" style="text-align: center;">
                <div class="mc-card__body mc-card__body--lg">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--mc-teal)" stroke-width="1.5" style="margin: 0 auto var(--space-md);"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <h5 style="font-family: var(--font-sans); font-weight: 600; margin-bottom: var(--space-xs);">Source Code</h5>
                    <p class="mc-text-sm" style="color: var(--mc-gray);">Full PHP/Laravel source code with every license</p>
                </div>
            </div>
            <div class="mc-card mc-card--bordered" style="text-align: center;">
                <div class="mc-card__body mc-card__body--lg">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--mc-teal)" stroke-width="1.5" style="margin: 0 auto var(--space-md);"><circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                    <h5 style="font-family: var(--font-sans); font-weight: 600; margin-bottom: var(--space-xs);">GDPR Ready</h5>
                    <p class="mc-text-sm" style="color: var(--mc-gray);">Built-in consent forms and data export tools</p>
                </div>
            </div>
            <div class="mc-card mc-card--bordered" style="text-align: center;">
                <div class="mc-card__body mc-card__body--lg">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--mc-teal)" stroke-width="1.5" style="margin: 0 auto var(--space-md);"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <h5 style="font-family: var(--font-sans); font-weight: 600; margin-bottom: var(--space-xs);">CAN-SPAM</h5>
                    <p class="mc-text-sm" style="color: var(--mc-gray);">Automatic unsubscribe links and compliance tools</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Sending Services — Dark Providers Banner -->
<section class="mc-providers-banner">
    <div class="mc-container">
        <div class="mc-providers-banner__inner">
            <div class="mc-providers-banner__text">
                <h3 class="mc-providers-banner__heading">Your infrastructure,<br>your choice</h3>
                <p class="mc-providers-banner__desc">Switch providers anytime. No lock-in, no migration headaches.</p>
            </div>
            <div class="mc-providers-banner__logos">
                <img src="{{ asset('images/services/amazon-ses-logo.svg') }}" alt="Amazon SES" loading="lazy">
                <img src="{{ asset('images/services/sendgrid-logo.svg') }}" alt="SendGrid" loading="lazy">
                <img src="{{ asset('images/services/sparkpost-logo.svg') }}" alt="SparkPost" loading="lazy">
                <img src="{{ asset('images/services/mailgun-logo.svg') }}" alt="Mailgun" loading="lazy">
                <img src="{{ asset('images/services/postmark-logo.svg') }}" alt="Postmark" loading="lazy">
                <img src="{{ asset('images/services/elastic-email-logo.svg') }}" alt="Elastic Email" loading="lazy">
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     NEW: Resources & Documentation
     ====================================================================== -->
<section class="mc-section">
    <div class="mc-container">
        <div class="mc-hero__content--center" style="margin-bottom: var(--space-2xl);">
            <h2>Everything you need to get started</h2>
            <p class="mc-text-lg" style="margin-top: var(--space-md);">From installation guides to advanced configuration, our documentation covers every step of your journey.</p>
        </div>
        <div class="mc-grid mc-grid--3 mc-grid--gap-lg">
            <a href="https://knowledge.acellemail.com/category/installation-setup" class="mc-card mc-card--bordered" target="_blank">
                <div class="mc-card__image mc-card__image--fixed">
                    <img src="{{ asset('images/about/newsroom.jpg') }}" alt="Installation Guide" loading="lazy">
                </div>
                <div class="mc-card__body">
                    <span class="mc-card__eyebrow">Guide</span>
                    <h4 class="mc-card__title">Installation &amp; Setup Guide</h4>
                    <p class="mc-card__desc">Step-by-step instructions to install AcelleMail on your server, configure SMTP, and send your first campaign.</p>
                    <span class="mc-card__link">Read guide</span>
                </div>
            </a>
            <a href="https://demo.acellemail.com" class="mc-card mc-card--bordered">
                <div class="mc-card__image mc-card__image--fixed">
                    <img src="{{ asset('images/about/why-acellemail.jpg') }}" alt="Live Demo" loading="lazy">
                </div>
                <div class="mc-card__body">
                    <span class="mc-card__eyebrow">Demo</span>
                    <h4 class="mc-card__title">Try the Live Demo</h4>
                    <p class="mc-card__desc">Explore all features in a fully working demo environment. No signup required &mdash; just click and start testing.</p>
                    <span class="mc-card__link">Launch demo</span>
                </div>
            </a>
            <a href="https://knowledge.acellemail.com/category/acellemail-updates" class="mc-card mc-card--bordered" target="_blank">
                <div class="mc-card__image mc-card__image--fixed">
                    <img src="{{ asset('images/about/whats-new.png') }}" alt="Changelog" loading="lazy">
                </div>
                <div class="mc-card__body">
                    <span class="mc-card__eyebrow">Changelog</span>
                    <h4 class="mc-card__title">Version 4.1.5 LTS Release Notes</h4>
                    <p class="mc-card__desc">See the latest improvements, bug fixes, and new features in the current long-term support release.</p>
                    <span class="mc-card__link">View changelog</span>
                </div>
            </a>
            <a href="https://knowledge.acellemail.com" class="mc-card mc-card--bordered" target="_blank">
                <div class="mc-card__image mc-card__image--fixed">
                    <img src="{{ asset('images/about/newsroom.jpg') }}" alt="Knowledge Base" loading="lazy">
                </div>
                <div class="mc-card__body">
                    <span class="mc-card__eyebrow">Knowledge</span>
                    <h4 class="mc-card__title">Knowledge Base</h4>
                    <p class="mc-card__desc">Tutorials, guides, and references covering email marketing, deliverability, automation, and more.</p>
                    <span class="mc-card__link">Browse KB</span>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- ======================================================================
     NEW: Community Forum CTA
     ====================================================================== -->
<section class="mc-community-cta">
    <div class="mc-container">
        <div class="mc-community-cta__inner">
            <div class="mc-community-cta__content">
                <span class="mc-eyebrow" style="color: var(--mc-yellow);">Community</span>
                <h2 class="mc-community-cta__heading">Join 10,000+ email marketers on our forum</h2>
                <p class="mc-community-cta__desc">Get answers, share tips, and connect with other AcelleMail users. From installation help to advanced automation strategies &mdash; the community has you covered.</p>
                <div class="mc-community-cta__features">
                    <div class="mc-community-cta__feature">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        <span>Ask questions &amp; get help</span>
                    </div>
                    <div class="mc-community-cta__feature">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <span>Connect with other users</span>
                    </div>
                    <div class="mc-community-cta__feature">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        <span>Share tips &amp; best practices</span>
                    </div>
                </div>
                <a href="https://forum.acellemail.com" class="mc-btn mc-btn--primary mc-btn--lg" target="_blank">Visit the Forum</a>
            </div>
            <div class="mc-community-cta__visual">
                <div class="mc-community-cta__card">
                    <div class="mc-community-cta__avatar">Q</div>
                    <div class="mc-community-cta__card-text">
                        <strong>How to configure Amazon SES with AcelleMail?</strong>
                        <span>12 replies &middot; Solved</span>
                    </div>
                </div>
                <div class="mc-community-cta__card">
                    <div class="mc-community-cta__avatar">A</div>
                    <div class="mc-community-cta__card-text">
                        <strong>Best practices for email warmup strategy</strong>
                        <span>24 replies &middot; Popular</span>
                    </div>
                </div>
                <div class="mc-community-cta__card">
                    <div class="mc-community-cta__avatar">T</div>
                    <div class="mc-community-cta__card-text">
                        <strong>Setting up automation for abandoned cart emails</strong>
                        <span>8 replies &middot; Solved</span>
                    </div>
                </div>
                <div class="mc-community-cta__card">
                    <div class="mc-community-cta__avatar">S</div>
                    <div class="mc-community-cta__card-text">
                        <strong>Running AcelleMail as SaaS &mdash; tips from my experience</strong>
                        <span>31 replies &middot; Pinned</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('jsonld')
@include('partials.seo.jsonld-organization')
@include('partials.seo.jsonld-software')
@include('partials.seo.jsonld-website')
@endpush
