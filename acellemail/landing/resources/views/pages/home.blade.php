@extends('layouts.app')

@section('title', 'AcelleMail — Self-Hosted Mailchimp Alternative ($74 One-Time)')
@section('meta_description', 'AcelleMail is the self-hosted Mailchimp alternative. Full source code, unlimited emails, $74 one-time license. Send via Amazon SES, SendGrid, or any SMTP.')
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
                <div class="mc-hero__actions" style="display: flex; gap: var(--space-md); align-items: center; flex-wrap: wrap;">
                    <a href="https://codecanyon.net/cart/add_items?item_ids=17796082" class="mc-btn mc-btn--primary mc-btn--lg">Get AcelleMail — $74 one-time</a>
                    <a href="https://acellemail.com/demo" class="mc-btn mc-btn--secondary mc-btn--lg">Try Live Demo</a>
                </div>
                <div class="mc-hero__reviews">
                    <img src="{{ asset('images/icons/stars-5.svg') }}" alt="Rated 5 out of 5 stars" class="mc-hero__stars" fetchpriority="high" width="120" height="22" decoding="async">
                    <span class="mc-hero__reviews-text">Based on 500+ reviews on</span>
                    <div class="mc-hero__review-badges">
                        <span style="font-weight: 700; font-size: 14px; color: #82B541; letter-spacing: -0.3px;">envato</span>
                        <span style="font-weight: 600; font-size: 13px; color: var(--mc-gray); opacity: 0.7;">CodeCanyon</span>
                    </div>
                </div>
            </div>
            <div class="mc-hero__image">
                <picture>
                    <source type="image/avif"
                            srcset="{{ asset('images/acm_banner-480.avif') }} 480w,
                                    {{ asset('images/acm_banner-768.avif') }} 768w,
                                    {{ asset('images/acm_banner-1200.avif') }} 1200w,
                                    {{ asset('images/acm_banner-1536.avif') }} 1536w"
                            sizes="(min-width: 1200px) 600px, (min-width: 768px) 50vw, 100vw">
                    <source type="image/webp"
                            srcset="{{ asset('images/acm_banner-480.webp') }} 480w,
                                    {{ asset('images/acm_banner-768.webp') }} 768w,
                                    {{ asset('images/acm_banner-1200.webp') }} 1200w,
                                    {{ asset('images/acm_banner-1536.webp') }} 1536w"
                            sizes="(min-width: 1200px) 600px, (min-width: 768px) 50vw, 100vw">
                    <img src="{{ asset('images/acm_banner.png') }}"
                         alt="A marketer running her email campaigns on AcelleMail"
                         width="1536" height="1024"
                         fetchpriority="high" decoding="async"
                         style="border-radius: var(--radius-lg);">
                </picture>
            </div>
        </div>
    </div>
</section>

<!-- Social Proof Text -->
<section class="mc-social-proof">
    <div class="mc-container">
        <h2 class="mc-social-proof__heading">Thousands of businesses run their own email marketing &mdash; with zero monthly fees</h2>
    </div>
</section>

<!-- Feature Cards -->
<section class="mc-feature-cards">
    <div class="mc-container">
        <div class="mc-feature-cards__grid">
            <div class="mc-feature-cards__item">
                <div class="mc-feature-cards__image">
                    <img src="{{ $themeImg('images/features/email-sms.svg') }}" alt="Email marketing" loading="lazy" width="520" height="400" decoding="async">
                </div>
                <h3 class="mc-feature-cards__title">Drag &amp; drop email builder</h3>
                <p class="mc-feature-cards__desc">Create stunning email campaigns with a visual editor. Choose from 100+ templates or build from scratch &mdash; no coding required.</p>
                <a href="{{ route('features') }}" class="mc-feature-cards__link">Learn about the email builder &rarr;</a>
            </div>
            <div class="mc-feature-cards__item">
                <div class="mc-feature-cards__image">
                    <img src="{{ $themeImg('images/features/automations-ecom.svg') }}" alt="Marketing automation" loading="lazy" width="520" height="400" decoding="async">
                </div>
                <h3 class="mc-feature-cards__title">Powerful automation flows</h3>
                <p class="mc-feature-cards__desc">Set up welcome series, drip campaigns, and triggered emails. Automate your marketing and engage subscribers on autopilot.</p>
                <a href="{{ route('features') }}" class="mc-feature-cards__link">Explore automation features &rarr;</a>
            </div>
            <div class="mc-feature-cards__item">
                <div class="mc-feature-cards__image">
                    <img src="{{ $themeImg('images/features/switch-brands.svg') }}" alt="Marketing automation" loading="lazy" width="520" height="400" decoding="async">
                </div>
                <h3 class="mc-feature-cards__title">Your server, your data</h3>
                <p class="mc-feature-cards__desc">Install on your own server in minutes. Full source code access, complete data ownership, and no vendor lock-in ever.</p>
                <a href="{{ route('features') }}" class="mc-feature-cards__link">See why self-hosted wins &rarr;</a>
            </div>
        </div>
    </div>
</section>

<!-- Built to extend — developer-first proposition (Wave 17a) -->
<section class="mc-section" style="background: var(--theme-bg-light);">
    <div class="mc-container">
        <div style="max-width: 720px; margin-bottom: var(--space-2xl);">
            <p class="mc-eyebrow">DEVELOPER-FIRST</p>
            <h2 style="font-family: var(--font-serif); font-size: clamp(28px, 4vw, 44px); font-weight: 300; line-height: 1.15; margin-top: var(--space-md); color: var(--theme-text); letter-spacing: -0.02em;">Built to extend.<br>Without forking core.</h2>
            <p style="font-size: 17px; line-height: 1.6; color: var(--theme-text-secondary); margin-top: var(--space-md);">A typed Hook system. A plugin host that loads vendor packages from disk. A REST API for everything. AcelleMail isn't just self-hosted &mdash; it's built to be the email marketing platform <em>your</em> developers extend.</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 320px), 1fr)); gap: var(--space-xl);">
            {{-- Plugins --}}
            <div class="mc-card mc-card--bordered" style="padding: var(--space-xl);">
                <div style="font-family: 'IBM Plex Mono', monospace; font-size: 11px; font-weight: 700; letter-spacing: 1.5px; color: var(--theme-primary); margin-bottom: var(--space-sm);">EXTEND</div>
                <h3 style="font-family: var(--font-serif); font-size: 22px; font-weight: 500; color: var(--theme-text); margin: 0 0 var(--space-sm); letter-spacing: -0.01em;">Plugins</h3>
                <p style="font-size: 15px; line-height: 1.65; color: var(--theme-text-secondary); margin-bottom: var(--space-md);">Drop a Composer-shaped folder under <code style="background: var(--theme-bg-warm); padding: 1px 5px; border-radius: 3px; font-size: 0.9em;">storage/app/plugins/</code> &mdash; the app autoloads it. Add sending drivers, payment gateways, AI assistants, custom UI, admin pages, REST APIs. Activate / deactivate / delete cleanly without touching core.</p>
                <a href="{{ route('for.developers') }}#hello-world" style="font-family: 'IBM Plex Mono', monospace; font-size: 13px; font-weight: 600; color: var(--theme-primary); text-decoration: none;">Read the plugin guide &rarr;</a>
            </div>

            {{-- REST API --}}
            <div class="mc-card mc-card--bordered" style="padding: var(--space-xl);">
                <div style="font-family: 'IBM Plex Mono', monospace; font-size: 11px; font-weight: 700; letter-spacing: 1.5px; color: var(--theme-primary); margin-bottom: var(--space-sm);">INTEGRATE</div>
                <h3 style="font-family: var(--font-serif); font-size: 22px; font-weight: 500; color: var(--theme-text); margin: 0 0 var(--space-sm); letter-spacing: -0.01em;">REST API</h3>
                <p style="font-size: 15px; line-height: 1.65; color: var(--theme-text-secondary); margin-bottom: var(--space-md);">Token-authenticated CRUD for campaigns, lists, subscribers, templates, automations. Webhook events for every domain happening. Drop-in for SaaS frontends, mobile apps, or other Laravel services. Same API plugins use is the same API external clients use &mdash; no second-class endpoints.</p>
                <a href="{{ route('api') }}" style="font-family: 'IBM Plex Mono', monospace; font-size: 13px; font-weight: 600; color: var(--theme-primary); text-decoration: none;">API reference &rarr;</a>
            </div>

            {{-- Hook system --}}
            <div class="mc-card mc-card--bordered" style="padding: var(--space-xl);">
                <div style="font-family: 'IBM Plex Mono', monospace; font-size: 11px; font-weight: 700; letter-spacing: 1.5px; color: var(--theme-primary); margin-bottom: var(--space-sm);">BUILD</div>
                <h3 style="font-family: var(--font-serif); font-size: 22px; font-weight: 500; color: var(--theme-text); margin: 0 0 var(--space-sm); letter-spacing: -0.01em;">Hook System</h3>
                <p style="font-size: 15px; line-height: 1.65; color: var(--theme-text-secondary); margin-bottom: var(--space-md);">Four typed extension patterns: REGISTRY, EVENT, BEHAVIOR, FILTER. Plugins listen, react, override, and transform &mdash; without touching core. Conflicts throw immediately, no silent surprises. The same hook system the core itself uses internally.</p>
                <a href="{{ route('for.developers') }}#hook-system" style="font-family: 'IBM Plex Mono', monospace; font-size: 13px; font-weight: 600; color: var(--theme-primary); text-decoration: none;">See the patterns &rarr;</a>
            </div>
        </div>

        <div style="margin-top: var(--space-2xl); text-align: center;">
            <a href="{{ route('for.developers') }}" class="mc-btn mc-btn--secondary">For developers &rarr; full plugin SDK</a>
        </div>
    </div>
</section>

<!-- See it in action — 3 product walkthrough videos -->
<section class="mc-video-showcase" style="padding: var(--space-4xl) 0; background: var(--theme-bg-warm);">
    <div class="mc-container">
        <div class="mc-video-showcase__header" style="text-align: center; max-width: 720px; margin: 0 auto var(--space-3xl);">
            <p class="mc-eyebrow" style="margin-bottom: var(--space-md);">SEE IT IN ACTION</p>
            <h2 class="mc-video-showcase__heading" style="margin-bottom: var(--space-md);">Watch AcelleMail at work</h2>
            <p style="color: var(--theme-text-secondary);">From building your first list to sending your first campaign &mdash; here&rsquo;s the full flow in under ten minutes.</p>
        </div>
        <div class="mc-video-showcase__grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: var(--space-xl);">
            <article class="mc-video-card" style="background: var(--theme-bg); border: 1px solid var(--theme-border); border-radius: var(--radius-lg); overflow: hidden; transition: all 240ms ease;">
                <div class="mc-feature-alt__embed" style="border-radius: 0; box-shadow: none;">
                    <lite-youtube videoid="wRlfC-jccys" playlabel="Create and send an email campaign in 3 minutes"></lite-youtube>
                </div>
                <div style="padding: var(--space-lg);">
                    <p class="mc-eyebrow" style="margin-bottom: var(--space-sm); color: var(--theme-text-tertiary);">CAMPAIGNS</p>
                    <h3 style="font-size: 18px; font-weight: 600; line-height: 1.3; letter-spacing: -0.015em; margin-bottom: var(--space-xs);">Send your first campaign in 3 minutes</h3>
                    <p style="font-size: 14px; color: var(--theme-text-secondary); line-height: 1.5; margin: 0;">Compose, design, schedule and send &mdash; the core flow end-to-end.</p>
                </div>
            </article>
            <article class="mc-video-card" style="background: var(--theme-bg); border: 1px solid var(--theme-border); border-radius: var(--radius-lg); overflow: hidden; transition: all 240ms ease;">
                <div class="mc-feature-alt__embed" style="border-radius: 0; box-shadow: none;">
                    <lite-youtube videoid="1u-D6LJSk80" playlabel="Create a mail list and add subscribers in 2 minutes"></lite-youtube>
                </div>
                <div style="padding: var(--space-lg);">
                    <p class="mc-eyebrow" style="margin-bottom: var(--space-sm); color: var(--theme-text-tertiary);">LISTS</p>
                    <h3 style="font-size: 18px; font-weight: 600; line-height: 1.3; letter-spacing: -0.015em; margin-bottom: var(--space-xs);">Build a subscriber list in 2 minutes</h3>
                    <p style="font-size: 14px; color: var(--theme-text-secondary); line-height: 1.5; margin: 0;">Create lists, import contacts and add custom fields fast.</p>
                </div>
            </article>
            <article class="mc-video-card" style="background: var(--theme-bg); border: 1px solid var(--theme-border); border-radius: var(--radius-lg); overflow: hidden; transition: all 240ms ease;">
                <div class="mc-feature-alt__embed" style="border-radius: 0; box-shadow: none;">
                    <lite-youtube videoid="uqMyS9tEZnw" playlabel="Install self-hosted email marketing in 5 minutes"></lite-youtube>
                </div>
                <div style="padding: var(--space-lg);">
                    <p class="mc-eyebrow" style="margin-bottom: var(--space-sm); color: var(--theme-text-tertiary);">SETUP</p>
                    <h3 style="font-size: 18px; font-weight: 600; line-height: 1.3; letter-spacing: -0.015em; margin-bottom: var(--space-xs);">Install + connect SMTP in 5 minutes</h3>
                    <p style="font-size: 14px; color: var(--theme-text-secondary); line-height: 1.5; margin: 0;">From server to first send &mdash; one-click installer plus SMTP wiring.</p>
                </div>
            </article>
        </div>
        <div style="text-align: center; margin-top: var(--space-2xl);">
            <a href="{{ route('features') }}" class="mc-btn mc-btn--secondary">See all walkthroughs</a>
        </div>
    </div>
</section>

<style>
.mc-video-card:hover {
    border-color: var(--theme-text) !important;
    transform: translateY(-2px);
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.06);
}
@media (max-width: 900px) {
    .mc-video-showcase__grid {
        grid-template-columns: 1fr !important;
    }
}
</style>

<!-- Works With — Sending Services Showcase -->
<section class="mc-services-showcase">
    <div class="mc-container">
        <div class="mc-services-showcase__header">
            <h2 class="mc-services-showcase__heading">Connect to any<br>sending service</h2>
            <p class="mc-services-showcase__desc">Plug in your favorite email provider and start sending in minutes. AcelleMail supports all major delivery services out of the box.</p>
        </div>
        <div class="mc-services-showcase__grid">
            <div class="mc-services-showcase__item"><img src="{{ asset('images/services/amazon-ses-logo.svg') }}" alt="Amazon SES" loading="lazy" width="180" height="48" decoding="async"></div>
            <div class="mc-services-showcase__item"><img src="{{ asset('images/services/sendgrid-logo.svg') }}" alt="SendGrid" loading="lazy" width="160" height="48" decoding="async"></div>
            <div class="mc-services-showcase__item"><img src="{{ asset('images/services/sparkpost-logo.svg') }}" alt="SparkPost" loading="lazy" width="170" height="48" decoding="async"></div>
            <div class="mc-services-showcase__item"><img src="{{ asset('images/services/mailgun-logo.svg') }}" alt="Mailgun" loading="lazy" width="170" height="48" decoding="async"></div>
            <div class="mc-services-showcase__item"><img src="{{ asset('images/services/postmark-logo.svg') }}" alt="Postmark" loading="lazy" width="170" height="48" decoding="async"></div>
            <div class="mc-services-showcase__item"><img src="{{ asset('images/services/elastic-email-logo.svg') }}" alt="Elastic Email" loading="lazy" width="210" height="48" decoding="async"></div>
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
                <img src="{{ $themeImg('images/features/email-sms.svg') }}" alt="Drag-and-drop email builder" loading="lazy" width="520" height="400" decoding="async">
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
                <img src="{{ $themeImg('images/features/automations-ecom.svg') }}" alt="Marketing automation workflows" loading="lazy" width="520" height="400" decoding="async">
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
                <img src="{{ $themeImg('images/features/predictive.svg') }}" alt="Email analytics dashboard" loading="lazy" width="520" height="400" decoding="async">
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
            <p class="mc-stats-section__subheading">Since 2016, thousands of businesses around the world have used AcelleMail to take control of their email marketing.</p>
        </div>
        <div class="mc-stats-section__grid">
            <div class="mc-stats-section__item">
                <span class="mc-stats-section__number">6,000+</span>
                <span class="mc-stats-section__label">sales</span>
                <p class="mc-stats-section__desc">Sold on CodeCanyon since 2016 — one of the longest-running and most trusted email marketing scripts.</p>
            </div>
            <div class="mc-stats-section__item">
                <span class="mc-stats-section__number">4.8★</span>
                <span class="mc-stats-section__label">rating</span>
                <p class="mc-stats-section__desc">Rated 4.8 out of 5 stars across 500+ verified reviews on Envato Market.</p>
            </div>
            <div class="mc-stats-section__item">
                <span class="mc-stats-section__number">100+</span>
                <span class="mc-stats-section__label">templates</span>
                <p class="mc-stats-section__desc">Pre-built responsive email templates included free with every license purchase.</p>
            </div>
            <div class="mc-stats-section__item">
                <span class="mc-stats-section__number">$0</span>
                <span class="mc-stats-section__label">monthly fees</span>
                <p class="mc-stats-section__desc">One-time payment starting at $74. No subscriptions, no per-email charges, no hidden costs.</p>
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
                <img src="{{ $themeImg('images/features/reach-inboxes.svg') }}" alt="Deliverability and verification" loading="lazy" width="520" height="400" decoding="async">
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
                <img src="{{ $themeImg('images/features/predictive.svg') }}" alt="List management and segmentation" loading="lazy" width="520" height="400" decoding="async">
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
                <img src="{{ $themeImg('images/features/pricing-hero.svg') }}" alt="AcelleMail pricing — one-time purchase" loading="lazy" width="520" height="400" decoding="async">
            </div>
            <div class="mc-plan-cta__content">
                <h2 class="mc-plan-cta__heading">One-time purchase. Lifetime ownership.</h2>
                <p class="mc-plan-cta__text">Get AcelleMail for just $74 (Regular License) or $199 (Extended License for SaaS). Full source code, free updates, and six months of support included with every purchase.</p>
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
                <a href="https://codecanyon.net/cart/add_items?item_ids=17796082" class="mc-btn mc-btn--primary mc-btn--lg">Get AcelleMail — $74 one-time</a>
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
                    <img src="{{ $themeImg('images/features/automations-ecom.svg') }}" alt="Multi-tenant SaaS platform" loading="lazy" width="520" height="400" decoding="async">
                </div>
                <div class="mc-card__body">
                    <h4 class="mc-card__title">Multi-Tenant Architecture</h4>
                    <p class="mc-card__desc">Each customer gets their own workspace with separate lists, campaigns, automations, and sending servers. Full white-label support.</p>
                    <span class="mc-card__link">Learn about SaaS features</span>
                </div>
            </a>
            <a href="{{ route('features') }}" class="mc-card mc-card--bordered">
                <div class="mc-card__image mc-card__image--fixed">
                    <img src="{{ $themeImg('images/features/predictive.svg') }}" alt="Subscription billing and plans" loading="lazy" width="520" height="400" decoding="async">
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
     NEWSLETTER BAND (mid-page) — sits between product proof and the
     "developer friendly" section. Mid-funnel: visitor has seen value
     props, hasn't decided to buy, but might commit to a low-friction
     monthly subscription. Source = home-band.
     ====================================================================== -->
<section class="mc-section mc-newsletter-band">
    <div class="mc-container mc-container--narrow">
        <x-newsletter.inline
            source="home-band"
            variant="band"
            title="Want self-hosting playbooks in your inbox?"
            subtitle="One monthly email: release notes, deliverability tips, real-world AcelleMail setups. No spam — unsubscribe in a single click."
            cta="Get the newsletter"
        />
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
                    <a href="https://acellemail.com/demo" class="mc-btn mc-btn--primary">Try Live Demo</a>
                    <a href="{{ route('pricing') }}" class="mc-btn mc-btn--secondary">See pricing</a>
                </div>
            </div>
            <div class="mc-feature-row__image">
                <img src="{{ $themeImg('images/features/email-sms.svg') }}" alt="Laravel PHP source code" loading="lazy" width="520" height="400" decoding="async">
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
                <a href="/kb/category/installation-setup" class="mc-btn mc-btn--primary" target="_blank">View documentation</a>
            </div>
            <div class="mc-feature-row__image">
                <img src="{{ $themeImg('images/features/installation_setup.svg') }}" alt="Easy installation on any server" loading="lazy" width="520" height="400" decoding="async">
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
                <img src="{{ $themeImg('images/features/case-study-savings.svg') }}" alt="Savings case study" loading="lazy" width="520" height="300" decoding="async">
            </div>
            <div class="mc-case-study__content">
                <blockquote class="mc-case-study__quote">
                    Switch from Mailchimp to AcelleMail and your math changes overnight. Send 500K emails per month through Amazon SES for under $50 &mdash; instead of $400+/month on a SaaS platform &mdash; and own all your data.
                </blockquote>
                <p class="mc-case-study__attribution">&mdash; Real-world numbers from AcelleMail customers running on Amazon SES</p>
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
                <a href="{{ route('compare.show', ['slug' => 'mailchimp']) }}" class="mc-case-study__link" style="margin-left: var(--space-lg);">Compare AcelleMail to Mailchimp &rarr;</a>
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
                {{-- -light variants: dark wordmark text flipped to white so it pops on the
                     black .mc-providers-banner BG. Brand-colored marks (orange Amazon swoosh,
                     blue SendGrid dots, etc.) kept untouched. The non-light versions stay
                     correct on the light .mc-services-showcase + integrations sections. --}}
                <img src="{{ asset('images/services/amazon-ses-logo-light.svg') }}" alt="Amazon SES" loading="lazy" width="180" height="48" decoding="async">
                <img src="{{ asset('images/services/sendgrid-logo-light.svg') }}" alt="SendGrid" loading="lazy" width="160" height="48" decoding="async">
                <img src="{{ asset('images/services/sparkpost-logo-light.svg') }}" alt="SparkPost" loading="lazy" width="170" height="48" decoding="async">
                <img src="{{ asset('images/services/mailgun-logo-light.svg') }}" alt="Mailgun" loading="lazy" width="170" height="48" decoding="async">
                <img src="{{ asset('images/services/postmark-logo-light.svg') }}" alt="Postmark" loading="lazy" width="170" height="48" decoding="async">
                <img src="{{ asset('images/services/elastic-email-logo-light.svg') }}" alt="Elastic Email" loading="lazy" width="210" height="48" decoding="async">
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
            <a href="/kb/category/installation-setup" class="mc-card mc-card--bordered" target="_blank">
                <div class="mc-card__image mc-card__image--fixed">
                    <img src="{{ asset('images/about/newsroom.jpg') }}" alt="Installation Guide" loading="lazy" width="540" height="304" decoding="async">
                </div>
                <div class="mc-card__body">
                    <span class="mc-card__eyebrow">Guide</span>
                    <h4 class="mc-card__title">Installation &amp; Setup Guide</h4>
                    <p class="mc-card__desc">Step-by-step instructions to install AcelleMail on your server, configure SMTP, and send your first campaign.</p>
                    <span class="mc-card__link">Read guide</span>
                </div>
            </a>
            <a href="https://acellemail.com/demo" class="mc-card mc-card--bordered">
                <div class="mc-card__image mc-card__image--fixed">
                    <img src="{{ asset('images/about/live-demo.svg') }}" alt="Live Demo" loading="lazy" width="540" height="320" decoding="async">
                </div>
                <div class="mc-card__body">
                    <span class="mc-card__eyebrow">Demo</span>
                    <h4 class="mc-card__title">Try the Live Demo</h4>
                    <p class="mc-card__desc">Explore all features in a fully working demo environment. No signup required &mdash; just click and start testing.</p>
                    <span class="mc-card__link">Launch demo</span>
                </div>
            </a>
            <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" class="mc-card mc-card--bordered" target="_blank">
                <div class="mc-card__image mc-card__image--fixed">
                    <img src="{{ asset('images/about/release-notes.svg') }}" alt="Changelog" loading="lazy" width="540" height="320" decoding="async">
                </div>
                <div class="mc-card__body">
                    <span class="mc-card__eyebrow">Changelog</span>
                    <h4 class="mc-card__title">Version 4.2.0 LTS Release Notes</h4>
                    <p class="mc-card__desc">See the latest improvements, bug fixes, and new features in the current long-term support release.</p>
                    <span class="mc-card__link">View changelog</span>
                </div>
            </a>
            <a href="/kb" class="mc-card mc-card--bordered" target="_blank">
                <div class="mc-card__image mc-card__image--fixed">
                    <img src="{{ asset('images/about/knowledge-base.svg') }}" alt="Knowledge Base" loading="lazy" width="540" height="320" decoding="async">
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
     TEMP: hidden until forum.acellemail.com reopens
     ====================================================================== -->
<section class="mc-community-cta" style="display: none;">
    <div class="mc-container">
        <div class="mc-community-cta__inner">
            <div class="mc-community-cta__content">
                <span class="mc-eyebrow" style="color: var(--mc-yellow);">Community</span>
                <h2 class="mc-community-cta__heading">Join the AcelleMail community forum</h2>
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
@include('partials.seo.jsonld-video', ['videos' => [
    [
        'name'        => 'Create and send an email campaign in 3 minutes',
        'description' => 'Step-by-step walkthrough of creating an email campaign in AcelleMail — from new campaign to send, with the drag-and-drop builder.',
        'ytId'        => 'wRlfC-jccys',
        'uploadDate'  => '2024-08-15',
        'duration'    => 'PT3M12S',
    ],
    [
        'name'        => 'Build a subscriber list in 2 minutes',
        'description' => 'How to create a mailing list, add subscribers, and segment them in AcelleMail.',
        'ytId'        => '1u-D6LJSk80',
        'uploadDate'  => '2024-08-15',
        'duration'    => 'PT2M08S',
    ],
    [
        'name'        => 'Install self-hosted email marketing in 5 minutes',
        'description' => 'End-to-end installation guide for AcelleMail on a Linux server with PHP/MySQL — including SMTP connection.',
        'ytId'        => 'uqMyS9tEZnw',
        'uploadDate'  => '2024-08-15',
        'duration'    => 'PT5M03S',
    ],
]])
@endpush
