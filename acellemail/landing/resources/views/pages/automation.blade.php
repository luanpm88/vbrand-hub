@extends('layouts.app')

@section('title', 'Automation | AcelleMail')

@section('content')

<!-- Hero Section -->
<section class="mc-hero mc-hero--automation">
    <div class="mc-container">
        <div class="mc-hero__grid">
            <div class="mc-hero__content">
                <p class="mc-hero__eyebrow">MARKETING AUTOMATION</p>
                <h1 class="mc-hero__heading">Set up trigger-based emails and customer journeys that run on autopilot</h1>
                <p class="mc-hero__subheading">AcelleMail's automation engine lets you create sophisticated email workflows triggered by subscriber actions, time delays, and conditional logic &mdash; all running on your own server with no sending limits.</p>
                <a href="{{ route('pricing') }}" class="mc-btn mc-btn--primary mc-btn--lg">Get Started</a>
            </div>
            <div class="mc-hero__image">
                <img src="{{ asset('images/hero/automation-hero.svg') }}" alt="AcelleMail marketing automation">
            </div>
        </div>
    </div>
</section>

<!-- Key Stats -->
<section class="mc-stats-section">
    <div class="mc-container">
        <div class="mc-stats-section__header">
            <h2 class="mc-stats-section__heading">Self-hosted automation, your way</h2>
            <p class="mc-stats-section__subheading">No rate limits, no per-email fees, no third-party data sharing. Your server, your data, your rules.</p>
        </div>
        <div class="mc-stats-section__grid">
            <div class="mc-stats-section__item">
                <span class="mc-stats-section__number">0</span>
                <span class="mc-stats-section__label">sending limits</span>
                <p class="mc-stats-section__desc">send as many automated emails as your server can handle</p>
            </div>
            <div class="mc-stats-section__item">
                <span class="mc-stats-section__number">100%</span>
                <span class="mc-stats-section__label">data ownership</span>
                <p class="mc-stats-section__desc">subscriber data never leaves your server</p>
            </div>
            <div class="mc-stats-section__item">
                <span class="mc-stats-section__number">127%</span>
                <span class="mc-stats-section__label">increase in click rates</span>
                <p class="mc-stats-section__desc">with automated emails vs. one-off campaigns</p>
            </div>
            <div class="mc-stats-section__item">
                <span class="mc-stats-section__number">6</span>
                <span class="mc-stats-section__label">trigger types</span>
                <p class="mc-stats-section__desc">open, click, subscribe, date-based, custom field, and API triggers</p>
            </div>
        </div>
    </div>
</section>

<!-- Feature Section A: Welcome Series -->
<section class="mc-feature-alt">
    <div class="mc-container">
        <div class="mc-feature-alt__grid">
            <div class="mc-feature-alt__image">
                <img src="{{ asset('images/features/predictive.svg') }}" alt="Welcome series automation">
            </div>
            <div class="mc-feature-alt__content">
                <h2 class="mc-feature-alt__heading">Welcome new subscribers with automated series</h2>
                <p class="mc-feature-alt__text">Trigger a multi-step welcome sequence the moment someone subscribes. Introduce your brand, deliver lead magnets, and nurture new contacts into engaged customers &mdash; all without lifting a finger.</p>
            </div>
        </div>
    </div>
</section>

<!-- Feature Section B: Event Triggers -->
<section class="mc-feature-alt mc-feature-alt--reverse">
    <div class="mc-container">
        <div class="mc-feature-alt__grid">
            <div class="mc-feature-alt__content">
                <h2 class="mc-feature-alt__heading">Trigger emails based on subscriber behavior</h2>
                <p class="mc-feature-alt__text">Build automated flows that fire when subscribers open an email, click a link, join a list, or match a custom field condition. Combine event triggers with time-based delays and conditional splits to create journeys that respond to how each person interacts with your emails.</p>
            </div>
            <div class="mc-feature-alt__image">
                <img src="{{ asset('images/features/automation-flows.svg') }}" alt="Automation event triggers">
            </div>
        </div>
    </div>
</section>

<!-- Feature Section C: Integrations -->
<section class="mc-feature-alt">
    <div class="mc-container">
        <div class="mc-feature-alt__grid">
            <div class="mc-feature-alt__image">
                <img src="{{ asset('images/features/integrations-auto.svg') }}" alt="Sending service integrations">
            </div>
            <div class="mc-feature-alt__content">
                <h2 class="mc-feature-alt__heading">Connect any SMTP or sending service</h2>
                <p class="mc-feature-alt__text">Route your automated emails through Amazon SES, SendGrid, SparkPost, Mailgun, or any SMTP server. AcelleMail handles the automation logic while your preferred delivery service handles the sending.</p>
                <a href="{{ route('integrations') }}" class="mc-feature-alt__link">Explore sending services <span>&rarr;</span></a>
            </div>
        </div>
    </div>
</section>

<!-- Case Study Section -->
<section class="mc-case-study">
    <div class="mc-container">
        <div class="mc-case-study__inner">
            <div class="mc-case-study__image">
                <img src="{{ asset('images/features/case-study.png') }}" alt="AcelleMail automation success story">
            </div>
            <div class="mc-case-study__content">
                <blockquote class="mc-case-study__quote">
                    &ldquo;Switching to AcelleMail cut our email costs by 90%. We send 500K automated emails per month through Amazon SES at a fraction of what we paid for hosted platforms.&rdquo;
                </blockquote>
                <div class="mc-case-study__attribution">
                    <strong>SaaS Founder</strong> &mdash; Self-hosted email marketing
                </div>
                <div class="mc-case-study__stats">
                    <div class="mc-case-study__stat">
                        <span class="mc-case-study__stat-number">90%</span>
                        <span class="mc-case-study__stat-label">cost reduction</span>
                    </div>
                    <div class="mc-case-study__stat-divider"></div>
                    <div class="mc-case-study__stat">
                        <span class="mc-case-study__stat-number">500K</span>
                        <span class="mc-case-study__stat-label">automated emails/month</span>
                    </div>
                </div>
                <a href="{{ route('about') }}" class="mc-case-study__link">Learn more <span>&rarr;</span></a>
            </div>
        </div>
    </div>
</section>

<!-- Feature Section D: Drip Campaigns -->
<section class="mc-feature-alt mc-feature-alt--reverse">
    <div class="mc-container">
        <div class="mc-feature-alt__grid">
            <div class="mc-feature-alt__content">
                <h2 class="mc-feature-alt__heading">Build drip campaigns that nurture leads over time</h2>
                <p class="mc-feature-alt__text">Design multi-step drip sequences with precise time delays between each email. Educate prospects, onboard new users, or run course-style email sequences that deliver the right content at the right time &mdash; automatically.</p>
                <a href="{{ route('features') }}" class="mc-feature-alt__link">Explore automation features <span>&rarr;</span></a>
            </div>
            <div class="mc-feature-alt__image">
                <img src="{{ asset('images/features/campaign-manager.svg') }}" alt="Drip campaign automation builder">
            </div>
        </div>
    </div>
</section>

<!-- Feature Section E: What's New -->
<section class="mc-feature-alt">
    <div class="mc-container">
        <div class="mc-feature-alt__grid">
            <div class="mc-feature-alt__image">
                <img src="{{ asset('images/features/whats-new.svg') }}" alt="What's new in AcelleMail">
            </div>
            <div class="mc-feature-alt__content">
                <h2 class="mc-feature-alt__heading">What's new in AcelleMail?</h2>
                <p class="mc-feature-alt__text">Discover the latest automation features, sending service integrations, and platform improvements. AcelleMail is open-source and actively developed &mdash; new capabilities ship regularly.</p>
                <a href="{{ route('features') }}" class="mc-feature-alt__link">Check out what's new <span>&rarr;</span></a>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="mc-faq">
    <div class="mc-container">
        <h2 class="mc-faq__heading">Frequently Asked Questions</h2>
        <div class="mc-faq__list">

            <div class="mc-faq__item">
                <button class="mc-faq__question" type="button" aria-expanded="false">
                    <span>Does AcelleMail support marketing automation?</span>
                    <svg class="mc-faq__icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="mc-faq__answer">
                    <p>Yes. AcelleMail includes a full automation engine that lets you create trigger-based email workflows. You can set up welcome series, re-engagement campaigns, birthday emails, drip sequences, and more &mdash; all running on your own server with no per-email fees.</p>
                </div>
            </div>

            <div class="mc-faq__item">
                <button class="mc-faq__question" type="button" aria-expanded="false">
                    <span>What triggers can I use to start an automation?</span>
                    <svg class="mc-faq__icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="mc-faq__answer">
                    <p>AcelleMail supports multiple trigger types: subscriber joins a list, opens an email, clicks a specific link, a date-based field matches (like birthdays or anniversaries), a custom field changes, or an external event fires via the API. You can combine triggers with time delays and conditional splits.</p>
                </div>
            </div>

            <div class="mc-faq__item">
                <button class="mc-faq__question" type="button" aria-expanded="false">
                    <span>Are there sending limits on automated emails?</span>
                    <svg class="mc-faq__icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="mc-faq__answer">
                    <p>No. Since AcelleMail is self-hosted, there are no platform-imposed sending limits. Your throughput depends on your server capacity and your sending service (Amazon SES, SendGrid, etc.). Most users send hundreds of thousands of automated emails per month at minimal cost.</p>
                </div>
            </div>

            <div class="mc-faq__item">
                <button class="mc-faq__question" type="button" aria-expanded="false">
                    <span>Can I use automation for abandoned cart emails?</span>
                    <svg class="mc-faq__icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="mc-faq__answer">
                    <p>Yes. For SaaS applications and web apps, you can trigger abandoned cart or abandoned signup automations using AcelleMail's API. When a user starts a process but doesn't complete it, your app calls the AcelleMail API to enroll them in a recovery automation sequence.</p>
                </div>
            </div>

            <div class="mc-faq__item">
                <button class="mc-faq__question" type="button" aria-expanded="false">
                    <span>How does self-hosted automation compare to cloud platforms?</span>
                    <svg class="mc-faq__icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="mc-faq__answer">
                    <p>Self-hosted automation gives you full control over your data, no per-subscriber pricing, no sending limits, and complete customization. You own your subscriber data, can modify the source code, and pay only for server hosting and sending service fees &mdash; typically 10-20x cheaper than cloud platforms at scale.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ======================================================================
     Pre-built Templates Gallery
     ====================================================================== -->
<section class="mc-section">
    <div class="mc-container">
        <div class="mc-hero__content--center" style="margin-bottom: var(--space-3xl);">
            <h2>Start with proven automation templates</h2>
            <p class="mc-text-lg" style="margin-top: var(--space-md);">Launch faster with pre-built automation workflows designed to engage subscribers from day one.</p>
        </div>
        <div class="mc-grid mc-grid--3 mc-grid--gap-lg">
            <div class="mc-card mc-card--bordered">
                <div class="mc-card__body mc-card__body--lg">
                    <h4 class="mc-card__title">Welcome Series</h4>
                    <p class="mc-card__desc">Greet new subscribers with a multi-step onboarding sequence that introduces your brand, delivers lead magnets, and drives first conversions.</p>
                    <a href="{{ route('pricing') }}" class="mc-card__link">Use template <span>&rarr;</span></a>
                </div>
            </div>
            <div class="mc-card mc-card--bordered">
                <div class="mc-card__body mc-card__body--lg">
                    <h4 class="mc-card__title">Abandoned Cart / Signup</h4>
                    <p class="mc-card__desc">For SaaS and web apps: recover users who started a trial, signup, or checkout but didn't finish. Trigger via API when the event fires in your app.</p>
                    <a href="{{ route('pricing') }}" class="mc-card__link">Use template <span>&rarr;</span></a>
                </div>
            </div>
            <div class="mc-card mc-card--bordered">
                <div class="mc-card__body mc-card__body--lg">
                    <h4 class="mc-card__title">Re-Engagement</h4>
                    <p class="mc-card__desc">Win back inactive subscribers with targeted campaigns that reignite interest through special offers, surveys, and compelling content.</p>
                    <a href="{{ route('pricing') }}" class="mc-card__link">Use template <span>&rarr;</span></a>
                </div>
            </div>
            <div class="mc-card mc-card--bordered">
                <div class="mc-card__body mc-card__body--lg">
                    <h4 class="mc-card__title">Birthday / Anniversary</h4>
                    <p class="mc-card__desc">Celebrate subscribers on their special days with automated date-field triggers that send personalized greetings, discounts, and rewards.</p>
                    <a href="{{ route('pricing') }}" class="mc-card__link">Use template <span>&rarr;</span></a>
                </div>
            </div>
            <div class="mc-card mc-card--bordered">
                <div class="mc-card__body mc-card__body--lg">
                    <h4 class="mc-card__title">Drip Campaign</h4>
                    <p class="mc-card__desc">Deliver a timed sequence of educational or promotional emails over days or weeks. Perfect for onboarding, courses, and lead nurturing.</p>
                    <a href="{{ route('pricing') }}" class="mc-card__link">Use template <span>&rarr;</span></a>
                </div>
            </div>
            <div class="mc-card mc-card--bordered">
                <div class="mc-card__body mc-card__body--lg">
                    <h4 class="mc-card__title">Recurring Schedule</h4>
                    <p class="mc-card__desc">Set up automations that run on a recurring schedule &mdash; weekly digests, monthly newsletters, or periodic check-ins sent automatically.</p>
                    <a href="{{ route('pricing') }}" class="mc-card__link">Use template <span>&rarr;</span></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     Automation Capabilities
     ====================================================================== -->
<section class="mc-feature-alt">
    <div class="mc-container">
        <div class="mc-feature-alt__grid">
            <div class="mc-feature-alt__image">
                <img src="{{ asset('images/features/email-sms.svg') }}" alt="AcelleMail automation capabilities">
            </div>
            <div class="mc-feature-alt__content">
                <h2 class="mc-feature-alt__heading">Powerful automation building blocks</h2>
                <p class="mc-feature-alt__text">Combine triggers, delays, conditions, and actions to build email journeys as simple or complex as you need. Everything runs on your server &mdash; no external dependencies.</p>
                <ul class="mc-feature-list">
                    <li class="mc-feature-list__item">Event triggers: open, click, subscribe, custom field</li>
                    <li class="mc-feature-list__item">Time-based delays: minutes, hours, days, weeks</li>
                    <li class="mc-feature-list__item">Conditional splits: segment by field, tag, or engagement</li>
                    <li class="mc-feature-list__item">Recurring schedules: daily, weekly, monthly sends</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     A/B Testing for Automations
     ====================================================================== -->
<section class="mc-feature-alt mc-feature-alt--reverse">
    <div class="mc-container">
        <div class="mc-feature-alt__grid">
            <div class="mc-feature-alt__content">
                <h2 class="mc-feature-alt__heading">Optimize every step with A/B testing</h2>
                <p class="mc-feature-alt__text">Don't just set it and forget it &mdash; improve it. Test subject lines, send times, and content variations within your automation workflows. AcelleMail's built-in A/B testing lets you experiment so every automated message performs at its peak.</p>
                <a href="{{ route('features') }}" class="mc-feature-alt__link">Learn about A/B testing <span>&rarr;</span></a>
            </div>
            <div class="mc-feature-alt__image">
                <img src="{{ asset('images/features/predictive.svg') }}" alt="A/B testing within automation workflows">
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     Automation Stats
     ====================================================================== -->
<section class="mc-stats-section">
    <div class="mc-container">
        <div class="mc-stats-section__header">
            <h2 class="mc-stats-section__heading">Self-hosted advantages that add up</h2>
            <p class="mc-stats-section__subheading">See why thousands of businesses choose to run their own email automation platform.</p>
        </div>
        <div class="mc-stats-section__grid">
            <div class="mc-stats-section__item">
                <span class="mc-stats-section__number">$0</span>
                <span class="mc-stats-section__label">per-subscriber fees</span>
                <p class="mc-stats-section__desc">pay only for hosting and sending &mdash; not per contact</p>
            </div>
            <div class="mc-stats-section__item">
                <span class="mc-stats-section__number">73%</span>
                <span class="mc-stats-section__label">open rate</span>
                <p class="mc-stats-section__desc">for welcome emails &mdash; your best first impression</p>
            </div>
            <div class="mc-stats-section__item">
                <span class="mc-stats-section__number">100%</span>
                <span class="mc-stats-section__label">your data</span>
                <p class="mc-stats-section__desc">subscriber data stays on your server, under your control</p>
            </div>
            <div class="mc-stats-section__item">
                <span class="mc-stats-section__number">10-20x</span>
                <span class="mc-stats-section__label">cheaper at scale</span>
                <p class="mc-stats-section__desc">compared to hosted email platforms with per-email pricing</p>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     Use Cases
     ====================================================================== -->
<section class="mc-section">
    <div class="mc-container">
        <div class="mc-hero__content--center" style="margin-bottom: var(--space-3xl);">
            <h2>Built for every use case</h2>
            <p class="mc-text-lg" style="margin-top: var(--space-md);">Whether you run a SaaS product, an agency, or a content business &mdash; AcelleMail automation adapts to your workflow.</p>
        </div>
        <div class="mc-grid mc-grid--2 mc-grid--gap-lg">
            <div class="mc-card mc-card--bordered">
                <div class="mc-card__body mc-card__body--lg">
                    <div style="font-size: 40px; margin-bottom: var(--space-md);">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--mc-teal)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                    </div>
                    <h4 class="mc-card__title">SaaS Onboarding</h4>
                    <p class="mc-card__desc">Automate user onboarding sequences triggered by signup, trial start, or feature usage. Guide new users through your product with perfectly timed educational emails.</p>
                </div>
            </div>
            <div class="mc-card mc-card--bordered">
                <div class="mc-card__body mc-card__body--lg">
                    <div style="font-size: 40px; margin-bottom: var(--space-md);">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--mc-teal)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    </div>
                    <h4 class="mc-card__title">Trial-to-Paid Conversion</h4>
                    <p class="mc-card__desc">Set up automated sequences that nudge trial users toward paid plans. Trigger emails based on usage milestones, approaching expiry dates, and engagement signals.</p>
                </div>
            </div>
            <div class="mc-card mc-card--bordered">
                <div class="mc-card__body mc-card__body--lg">
                    <div style="font-size: 40px; margin-bottom: var(--space-md);">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--mc-teal)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3h7l2 7H5z"/><path d="M14 3h7l-2 7h-7z"/><path d="M5 10v8a2 2 0 002 2h10a2 2 0 002-2v-8"/><line x1="12" y1="14" x2="12" y2="18"/></svg>
                    </div>
                    <h4 class="mc-card__title">Win-Back Campaigns</h4>
                    <p class="mc-card__desc">Automatically identify inactive subscribers and re-engage them with targeted offers, content updates, and personalized messages that bring them back.</p>
                </div>
            </div>
            <div class="mc-card mc-card--bordered">
                <div class="mc-card__body mc-card__body--lg">
                    <div style="font-size: 40px; margin-bottom: var(--space-md);">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--mc-teal)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                    </div>
                    <h4 class="mc-card__title">Content & Newsletter</h4>
                    <p class="mc-card__desc">Automate recurring newsletters, content digests, and RSS-to-email campaigns. Deliver fresh content to your audience on a schedule without manual work.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     API & Webhooks
     ====================================================================== -->
<section class="mc-feature-alt" style="background: var(--mc-light-gray);">
    <div class="mc-container">
        <div class="mc-feature-alt__grid">
            <div class="mc-feature-alt__image">
                <img src="{{ asset('images/features/api-custom.svg') }}" alt="AcelleMail REST API">
            </div>
            <div class="mc-feature-alt__content">
                <h2 class="mc-feature-alt__heading">Build custom automations with our API</h2>
                <p class="mc-feature-alt__text">Go beyond pre-built workflows with AcelleMail's RESTful API. Trigger automations from your app, sync subscriber data programmatically, and build custom integrations with any system. Whether you're connecting a Laravel app, a WordPress site, or a custom SaaS product &mdash; the API gives you full control.</p>
                <a href="{{ route('integrations') }}" class="mc-btn mc-btn--primary">View API docs</a>
            </div>
        </div>
    </div>
</section>
@endsection
