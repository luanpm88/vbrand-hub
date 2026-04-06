@extends('layouts.app')

@section('title', 'Self-Hosted Email Marketing Software — Send Unlimited Emails | AcelleMail')
@section('meta_description', 'Send unlimited email campaigns from your own server. Drag & drop builder, 100+ templates, real-time analytics. No per-subscriber fees. Full data ownership.')
@section('og_title', 'Email Marketing That You Own — AcelleMail')

@section('content')

<!-- ======================================================================
     HERO — Split Layout, Cream Background
     ====================================================================== -->
<section class="mc-hero" style="background: var(--mc-cream);">
    <div class="mc-container">
        <div class="mc-hero__grid">
            <div class="mc-hero__content">
                <p class="mc-hero__eyebrow">EMAIL MARKETING</p>
                <h1 class="mc-hero__heading">Email Marketing That You Own</h1>
                <p class="mc-hero__subheading">Send unlimited emails from your server with a beautiful drag &amp; drop builder. AcelleMail gives you complete control over your email marketing &mdash; no per-subscriber fees, no sending limits, and full data ownership. Install on your own server and start sending professional campaigns in minutes.</p>
                <div class="mc-hero__actions" style="display: flex; gap: var(--space-md); align-items: center; flex-wrap: wrap;">
                    <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" class="mc-btn mc-btn--primary mc-btn--lg">Buy on CodeCanyon</a>
                    <a href="https://demo.acellemail.com" class="mc-btn mc-btn--secondary mc-btn--lg">Try Live Demo</a>
                </div>
            </div>
            <div class="mc-hero__image">
                <img src="{{ asset('images/features/email-sms.svg') }}" alt="AcelleMail email marketing platform — design, send, and analyze email campaigns" fetchpriority="high">
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     SOCIAL PROOF — Customer Logo Strip
     ====================================================================== -->
<section class="mc-logo-strip">
    <div class="mc-container">
        <p class="mc-text-center" style="color: var(--mc-gray); font-size: 14px; font-weight: 500; text-transform: uppercase; letter-spacing: 1px; margin-bottom: var(--space-lg);">Works with your favorite sending services</p>
        <div class="mc-logo-strip__row">
            <span class="mc-logo-strip__logo" style="font-weight: 700; font-size: 15px; color: #64748B; white-space: nowrap; opacity: 0.7;">Amazon SES</span>
            <span class="mc-logo-strip__logo" style="font-weight: 700; font-size: 15px; color: #64748B; white-space: nowrap; opacity: 0.7;">SendGrid</span>
            <span class="mc-logo-strip__logo" style="font-weight: 700; font-size: 15px; color: #64748B; white-space: nowrap; opacity: 0.7;">SparkPost</span>
            <span class="mc-logo-strip__logo" style="font-weight: 700; font-size: 15px; color: #64748B; white-space: nowrap; opacity: 0.7;">Mailgun</span>
            <span class="mc-logo-strip__logo" style="font-weight: 700; font-size: 15px; color: #64748B; white-space: nowrap; opacity: 0.7;">Postmark</span>
            <span class="mc-logo-strip__logo" style="font-weight: 700; font-size: 15px; color: #64748B; white-space: nowrap; opacity: 0.7;">Elastic Email</span>
        </div>
    </div>
</section>

<!-- ======================================================================
     FEATURE ALT — Drag-and-Drop Email Builder
     ====================================================================== -->
<section class="mc-feature-alt">
    <div class="mc-container">
        <div class="mc-feature-alt__grid">
            <div class="mc-feature-alt__image">
                <img src="{{ asset('images/features/email-sms.svg') }}" alt="Drag-and-drop email builder interface" loading="lazy">
            </div>
            <div class="mc-feature-alt__content">
                <span class="mc-eyebrow">Email Builder</span>
                <h2 class="mc-feature-alt__heading">Drag-and-drop email builder</h2>
                <p class="mc-feature-alt__text">Create beautiful, professional emails without writing a single line of code. AcelleMail&rsquo;s intuitive drag-and-drop editor lets you add images, text blocks, buttons, social links, and more &mdash; all with pixel-perfect precision.</p>
                <p class="mc-feature-alt__text">Choose from pre-built content blocks, customize colors and fonts to match your brand, and preview your design on desktop and mobile before you hit send. Built on a responsive framework, your emails look great on every device.</p>
                <ul class="mc-feature-list">
                    <li class="mc-feature-list__item">Drag-and-drop blocks for images, text, buttons, videos, and more</li>
                    <li class="mc-feature-list__item">Mobile-responsive designs that look great on every device</li>
                    <li class="mc-feature-list__item">Custom brand colors, fonts, and logos across all campaigns</li>
                    <li class="mc-feature-list__item">Real-time preview across desktop, tablet, and mobile</li>
                    <li class="mc-feature-list__item">Full HTML editor for advanced users who want complete control</li>
                </ul>
                <a href="{{ route('features') }}" class="mc-feature-alt__link">Explore the email builder &rarr;</a>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     FEATURE ALT REVERSED — Template Gallery
     ====================================================================== -->
<section class="mc-feature-alt mc-feature-alt--reverse" style="background: var(--mc-cream);">
    <div class="mc-container">
        <div class="mc-feature-alt__grid">
            <div class="mc-feature-alt__content">
                <span class="mc-eyebrow">Template Gallery</span>
                <h2 class="mc-feature-alt__heading">100+ pre-designed templates</h2>
                <p class="mc-feature-alt__text">Skip the blank page. Start with one of our professionally designed email templates built for every industry and campaign type. From product launches and seasonal promotions to newsletters and event invitations, we have a template that fits.</p>
                <p class="mc-feature-alt__text">Every template is fully customizable, mobile-responsive, and tested across major email clients including Gmail, Outlook, Apple Mail, and Yahoo. Just add your content and branding &mdash; you&rsquo;ll be sending in minutes.</p>
                <ul class="mc-feature-list">
                    <li class="mc-feature-list__item">Templates for e-commerce, SaaS, nonprofits, restaurants, and more</li>
                    <li class="mc-feature-list__item">Holiday and seasonal campaign templates updated regularly</li>
                    <li class="mc-feature-list__item">Tested and optimized for all major email clients</li>
                    <li class="mc-feature-list__item">Upload and manage your own custom templates</li>
                    <li class="mc-feature-list__item">Save templates for reuse across future campaigns</li>
                </ul>
                <a href="{{ route('features') }}" class="mc-feature-alt__link">Browse all templates &rarr;</a>
            </div>
            <div class="mc-feature-alt__image">
                <img src="{{ asset('images/features/automations-ecom.svg') }}" alt="Professional email templates for every industry" loading="lazy">
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     3-COLUMN FEATURE CARDS — Personalization & A/B Testing
     ====================================================================== -->
<section class="mc-section" style="background: var(--mc-white);">
    <div class="mc-container">
        <div class="mc-hero__content--center" style="margin-bottom: var(--space-3xl);">
            <span class="mc-eyebrow">Powerful Features</span>
            <h2>Personalization, Testing &amp; Scheduling</h2>
            <p class="mc-text-lg" style="margin-top: var(--space-md);">AcelleMail gives you the tools to send the right message to the right person at the right time &mdash; from dynamic personalization to A/B testing and intelligent scheduling.</p>
        </div>
        <div class="mc-grid mc-grid--3 mc-grid--gap-lg">
            <div class="mc-card mc-card--bordered">
                <div class="mc-card__body mc-card__body--lg">
                    <div style="font-size: 40px; margin-bottom: var(--space-md);">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--mc-teal)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                    </div>
                    <h4 class="mc-card__title">Personalization</h4>
                    <p class="mc-card__desc">Use merge tags to insert subscriber names, custom fields, and dynamic content into every email. Create conditional content blocks that show different content based on subscriber attributes, making each email feel personally crafted for the recipient.</p>
                    <span class="mc-card__link">Learn more</span>
                </div>
            </div>
            <div class="mc-card mc-card--bordered">
                <div class="mc-card__body mc-card__body--lg">
                    <div style="font-size: 40px; margin-bottom: var(--space-md);">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--mc-teal)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </div>
                    <h4 class="mc-card__title">A/B Testing</h4>
                    <p class="mc-card__desc">Test subject lines, sender names, and email content to find what resonates with your audience. AcelleMail automatically splits your list, tracks results, and can send the winning version to the remaining subscribers for maximum engagement.</p>
                    <span class="mc-card__link">Learn more</span>
                </div>
            </div>
            <div class="mc-card mc-card--bordered">
                <div class="mc-card__body mc-card__body--lg">
                    <div style="font-size: 40px; margin-bottom: var(--space-md);">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--mc-teal)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                    </div>
                    <h4 class="mc-card__title">Smart Scheduling</h4>
                    <p class="mc-card__desc">Schedule campaigns to send at the optimal time for your audience. Set up recurring campaigns, drip sequences, and time-zone-aware scheduling to reach subscribers when they are most likely to engage. Queue campaigns for hands-off delivery.</p>
                    <span class="mc-card__link">Learn more</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     FEATURE ALT — Advanced Segmentation & List Management
     ====================================================================== -->
<section class="mc-feature-alt" style="background: var(--mc-light-gray);">
    <div class="mc-container">
        <div class="mc-feature-alt__grid">
            <div class="mc-feature-alt__image">
                <img src="{{ asset('images/features/predictive.svg') }}" alt="Advanced audience segmentation tools" loading="lazy">
            </div>
            <div class="mc-feature-alt__content">
                <span class="mc-eyebrow">Segmentation &amp; List Management</span>
                <h2 class="mc-feature-alt__heading">Advanced Segmentation &amp; List Management</h2>
                <p class="mc-feature-alt__text">Send the right message to the right person at the right time. AcelleMail&rsquo;s powerful segmentation engine lets you filter your audience by custom fields, tags, engagement level, subscription date, and more.</p>
                <p class="mc-feature-alt__text">Combine segmentation with dynamic content blocks to create one email that personalizes itself for each recipient. Import contacts from CSV, manage double opt-in, and maintain clean lists with built-in verification and blacklisting tools.</p>
                <ul class="mc-feature-list">
                    <li class="mc-feature-list__item">Segment by custom fields, tags, and subscriber behavior</li>
                    <li class="mc-feature-list__item">Merge tags for personalized greetings and dynamic content</li>
                    <li class="mc-feature-list__item">Import from CSV, Excel, or copy-paste with field mapping</li>
                    <li class="mc-feature-list__item">Double opt-in with customizable confirmation emails</li>
                    <li class="mc-feature-list__item">Blacklisting by email address or domain</li>
                    <li class="mc-feature-list__item">Automatic bounce handling and list hygiene</li>
                </ul>
                <a href="{{ route('features') }}" class="mc-feature-alt__link">Explore list management &rarr;</a>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     FEATURE ALT REVERSED — A/B Testing & Optimization
     ====================================================================== -->
<section class="mc-feature-alt mc-feature-alt--reverse">
    <div class="mc-container">
        <div class="mc-feature-alt__grid">
            <div class="mc-feature-alt__content">
                <span class="mc-eyebrow">Testing &amp; Optimization</span>
                <h2 class="mc-feature-alt__heading">A/B Testing &amp; Campaign Optimization</h2>
                <p class="mc-feature-alt__text">Stop guessing what works. AcelleMail&rsquo;s A/B testing lets you test subject lines, email content, and sender names to discover what drives the best results. Run controlled experiments and let the platform automatically send the winning version to the rest of your audience.</p>
                <p class="mc-feature-alt__text">With detailed analytics on every campaign, you can track opens, clicks, bounces, and unsubscribes in real time. Use click maps to see exactly where subscribers engage, and compare campaigns side by side to continuously improve your email performance.</p>
                <ul class="mc-feature-list">
                    <li class="mc-feature-list__item">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="var(--mc-teal)" stroke-width="2.5" style="flex-shrink: 0;"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Test subject lines, sender names, and email content
                    </li>
                    <li class="mc-feature-list__item">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="var(--mc-teal)" stroke-width="2.5" style="flex-shrink: 0;"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Automatic winner selection based on open or click rates
                    </li>
                    <li class="mc-feature-list__item">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="var(--mc-teal)" stroke-width="2.5" style="flex-shrink: 0;"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Configurable test sample size and duration
                    </li>
                    <li class="mc-feature-list__item">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="var(--mc-teal)" stroke-width="2.5" style="flex-shrink: 0;"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Real-time results tracking during test period
                    </li>
                    <li class="mc-feature-list__item">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="var(--mc-teal)" stroke-width="2.5" style="flex-shrink: 0;"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Campaign comparison reports across all metrics
                    </li>
                    <li class="mc-feature-list__item">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="var(--mc-teal)" stroke-width="2.5" style="flex-shrink: 0;"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Click map heatmaps for every campaign
                    </li>
                </ul>
                <a href="{{ route('features') }}" class="mc-feature-alt__link">Learn about testing &rarr;</a>
            </div>
            <div class="mc-feature-alt__image">
                <img src="{{ asset('images/features/automations-ecom.svg') }}" alt="A/B testing dashboard showing campaign variations and results" loading="lazy">
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     STATS SECTION — Cost Savings: Self-Hosted vs SaaS
     ====================================================================== -->
<section class="mc-stats-section">
    <div class="mc-container">
        <div class="mc-stats-section__header">
            <h2 class="mc-stats-section__heading">Self-hosted email marketing saves you thousands</h2>
            <p class="mc-stats-section__subheading">Stop paying per-subscriber fees. With AcelleMail + Amazon SES, send 100,000 emails for just $10 instead of $299+/month on SaaS platforms.</p>
        </div>
        <div class="mc-stats-section__grid">
            <div class="mc-stats-section__item">
                <span class="mc-stats-section__number">$10</span>
                <span class="mc-stats-section__label">100K emails via SES</span>
                <p class="mc-stats-section__desc">Send 100,000 emails through Amazon SES for just $10 &mdash; compared to $299+/month on typical SaaS platforms.</p>
            </div>
            <div class="mc-stats-section__item">
                <span class="mc-stats-section__number">97%</span>
                <span class="mc-stats-section__label">cost savings</span>
                <p class="mc-stats-section__desc">Businesses switching from SaaS email platforms to AcelleMail save up to 97% on email marketing costs.</p>
            </div>
            <div class="mc-stats-section__item">
                <span class="mc-stats-section__number">0</span>
                <span class="mc-stats-section__label">subscriber limits</span>
                <p class="mc-stats-section__desc">No per-contact fees. Grow your list to millions without your costs going up. Pay only for what you send.</p>
            </div>
            <div class="mc-stats-section__item">
                <span class="mc-stats-section__number">100%</span>
                <span class="mc-stats-section__label">data ownership</span>
                <p class="mc-stats-section__desc">Your subscriber data, campaign history, and analytics stay on your server. No third-party access, ever.</p>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     FEATURE ALT — Deliverability & Sending Servers
     ====================================================================== -->
<section class="mc-feature-alt" style="background: var(--mc-light-gray);">
    <div class="mc-container">
        <div class="mc-feature-alt__grid">
            <div class="mc-feature-alt__image">
                <img src="{{ asset('images/features/sending-servers.svg') }}" alt="Sending server management" loading="lazy">
            </div>
            <div class="mc-feature-alt__content">
                <span class="mc-eyebrow">Deliverability &amp; Sending Servers</span>
                <h2 class="mc-feature-alt__heading">Connect Any Sending Service</h2>
                <p class="mc-feature-alt__text">AcelleMail connects to all major email sending services so you can choose the best option for your budget and volume. Use Amazon SES for the lowest cost, SendGrid for advanced analytics, or your own SMTP server for complete independence.</p>
                <p class="mc-feature-alt__text">Configure multiple sending servers and rotate between them for higher deliverability and throughput. Set sending limits, throttle rates, and domain authentication (SPF, DKIM, DMARC) per server &mdash; all from a simple admin interface.</p>
                <ul class="mc-feature-list">
                    <li class="mc-feature-list__item">Amazon SES, SendGrid, SparkPost, Mailgun, Elastic Email</li>
                    <li class="mc-feature-list__item">Any custom SMTP server or your own mail server</li>
                    <li class="mc-feature-list__item">Multiple sending servers with automatic rotation</li>
                    <li class="mc-feature-list__item">Per-server sending limits and throttling controls</li>
                    <li class="mc-feature-list__item">SPF, DKIM, and DMARC authentication per domain</li>
                    <li class="mc-feature-list__item">Automatic bounce handling and feedback loop integration</li>
                    <li class="mc-feature-list__item">Real-time sending logs and delivery status tracking</li>
                </ul>
                <a href="{{ route('features') }}" class="mc-feature-alt__link">Learn about sending servers &rarr;</a>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     COMPARISON TABLE — Self-Hosted vs SaaS Platforms
     ====================================================================== -->
<section class="mc-section" style="background: var(--mc-white);">
    <div class="mc-container">
        <div class="mc-hero__content--center" style="margin-bottom: var(--space-3xl);">
            <span class="mc-eyebrow">How We Compare</span>
            <h2>Why AcelleMail vs SaaS platforms</h2>
            <p class="mc-text-lg" style="margin-top: var(--space-md);">See how self-hosted AcelleMail compares to SaaS email marketing platforms on the features that matter most.</p>
        </div>
        <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
            <table style="width: 100%; border-collapse: collapse; min-width: 600px; font-family: var(--font-sans); font-size: 15px;">
                <thead>
                    <tr style="border-bottom: 2px solid var(--mc-black);">
                        <th style="text-align: left; padding: var(--space-md) var(--space-lg); font-weight: 600; color: var(--mc-black); width: 40%;">Feature</th>
                        <th style="text-align: center; padding: var(--space-md) var(--space-lg); font-weight: 700; color: var(--mc-teal); width: 20%;">AcelleMail</th>
                        <th style="text-align: center; padding: var(--space-md) var(--space-lg); font-weight: 600; color: var(--mc-gray); width: 20%;">SaaS Platform A</th>
                        <th style="text-align: center; padding: var(--space-md) var(--space-lg); font-weight: 600; color: var(--mc-gray); width: 20%;">SaaS Platform B</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid var(--mc-border);">
                        <td style="padding: var(--space-md) var(--space-lg); font-weight: 500;">Unlimited subscribers</td>
                        <td style="padding: var(--space-md) var(--space-lg); text-align: center;">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="var(--mc-teal)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </td>
                        <td style="padding: var(--space-md) var(--space-lg); text-align: center; color: var(--mc-gray);">Per-contact pricing</td>
                        <td style="padding: var(--space-md) var(--space-lg); text-align: center; color: var(--mc-gray);">Per-contact pricing</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--mc-border); background: var(--mc-cream);">
                        <td style="padding: var(--space-md) var(--space-lg); font-weight: 500;">Full data ownership</td>
                        <td style="padding: var(--space-md) var(--space-lg); text-align: center;">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="var(--mc-teal)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </td>
                        <td style="padding: var(--space-md) var(--space-lg); text-align: center;">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="var(--mc-jasper)" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </td>
                        <td style="padding: var(--space-md) var(--space-lg); text-align: center;">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="var(--mc-jasper)" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--mc-border);">
                        <td style="padding: var(--space-md) var(--space-lg); font-weight: 500;">Choose your sending service</td>
                        <td style="padding: var(--space-md) var(--space-lg); text-align: center;">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="var(--mc-teal)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </td>
                        <td style="padding: var(--space-md) var(--space-lg); text-align: center;">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="var(--mc-jasper)" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </td>
                        <td style="padding: var(--space-md) var(--space-lg); text-align: center;">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="var(--mc-jasper)" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--mc-border); background: var(--mc-cream);">
                        <td style="padding: var(--space-md) var(--space-lg); font-weight: 500;">One-time license fee</td>
                        <td style="padding: var(--space-md) var(--space-lg); text-align: center;">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="var(--mc-teal)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </td>
                        <td style="padding: var(--space-md) var(--space-lg); text-align: center; color: var(--mc-gray);">$299+/mo</td>
                        <td style="padding: var(--space-md) var(--space-lg); text-align: center; color: var(--mc-gray);">$79+/mo</td>
                    </tr>
                    <tr style="border-bottom: 1px solid var(--mc-border);">
                        <td style="padding: var(--space-md) var(--space-lg); font-weight: 500;">Multi-tenant / reseller support</td>
                        <td style="padding: var(--space-md) var(--space-lg); text-align: center;">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="var(--mc-teal)" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </td>
                        <td style="padding: var(--space-md) var(--space-lg); text-align: center;">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="var(--mc-jasper)" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </td>
                        <td style="padding: var(--space-md) var(--space-lg); text-align: center;">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="var(--mc-jasper)" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="mc-text-center" style="margin-top: var(--space-xl);">
            <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" class="mc-btn mc-btn--primary">Buy on CodeCanyon</a>
        </p>
    </div>
</section>

<!-- ======================================================================
     CASE STUDY — Cost Savings
     ====================================================================== -->
<section class="mc-case-study">
    <div class="mc-container">
        <div class="mc-case-study__inner">
            <div class="mc-case-study__image">
                <img src="{{ asset('images/features/case-study.png') }}" alt="Cost savings case study — self-hosted email marketing" loading="lazy">
            </div>
            <div class="mc-case-study__content">
                <blockquote class="mc-case-study__quote">
                    &ldquo;We switched from a $299/month SaaS email platform to AcelleMail with Amazon SES. Our 100,000-subscriber list now costs us $10 per campaign instead of $299/month. That&rsquo;s over $3,400 saved in the first year alone. The drag &amp; drop builder is just as good, and we have complete control over our data.&rdquo;
                </blockquote>
                <div class="mc-case-study__attribution">
                    <strong>GreenLeaf Agency</strong> &mdash; Michael Chen, Marketing Director
                </div>
                <div class="mc-case-study__stats">
                    <div class="mc-case-study__stat">
                        <span class="mc-case-study__stat-number">97%</span>
                        <span class="mc-case-study__stat-label">cost reduction</span>
                    </div>
                    <div class="mc-case-study__stat-divider"></div>
                    <div class="mc-case-study__stat">
                        <span class="mc-case-study__stat-number">$3,400+</span>
                        <span class="mc-case-study__stat-label">saved per year</span>
                    </div>
                    <div class="mc-case-study__stat-divider"></div>
                    <div class="mc-case-study__stat">
                        <span class="mc-case-study__stat-number">100K</span>
                        <span class="mc-case-study__stat-label">subscribers managed</span>
                    </div>
                </div>
                <a href="{{ route('about') }}" class="mc-case-study__link">Read the full case study <span>&rarr;</span></a>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     3-COLUMN CARDS — More Email Features
     ====================================================================== -->
<section class="mc-section" style="background: var(--mc-cream);">
    <div class="mc-container">
        <div class="mc-hero__content--center" style="margin-bottom: var(--space-3xl);">
            <h2>More features to grow your email marketing</h2>
            <p class="mc-text-lg" style="margin-top: var(--space-md);">Beyond core email campaigns, AcelleMail includes a full suite of tools to help you reach, engage, and convert your audience.</p>
        </div>
        <div class="mc-grid mc-grid--3 mc-grid--gap-lg">
            <a href="{{ route('features') }}" class="mc-card mc-card--bordered" style="background: var(--mc-white);">
                <div class="mc-card__body mc-card__body--lg">
                    <div style="font-size: 40px; margin-bottom: var(--space-md);">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--mc-teal)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                    </div>
                    <h4 class="mc-card__title">Transactional Emails</h4>
                    <p class="mc-card__desc">Send password resets, order confirmations, and account notifications through your AcelleMail installation. Use the same templates and tracking as your marketing campaigns for a consistent brand experience.</p>
                    <span class="mc-card__link">Learn more</span>
                </div>
            </a>
            <a href="{{ route('features') }}" class="mc-card mc-card--bordered" style="background: var(--mc-white);">
                <div class="mc-card__body mc-card__body--lg">
                    <div style="font-size: 40px; margin-bottom: var(--space-md);">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--mc-teal)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 11a9 9 0 0 1 9 9"/><path d="M4 4a16 16 0 0 1 16 16"/><circle cx="5" cy="19" r="1"/></svg>
                    </div>
                    <h4 class="mc-card__title">RSS-to-Email</h4>
                    <p class="mc-card__desc">Automatically turn your blog posts and content feeds into beautifully formatted email campaigns. Set your schedule and let AcelleMail pull new content and send it to your subscribers without any manual effort.</p>
                    <span class="mc-card__link">Learn more</span>
                </div>
            </a>
            <a href="{{ route('features') }}" class="mc-card mc-card--bordered" style="background: var(--mc-white);">
                <div class="mc-card__body mc-card__body--lg">
                    <div style="font-size: 40px; margin-bottom: var(--space-md);">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--mc-teal)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <h4 class="mc-card__title">Email Verification</h4>
                    <p class="mc-card__desc">Built-in email verification service checks your list for invalid, disposable, and catch-all addresses before you send. Reduce bounces, protect your sender reputation, and improve deliverability across all campaigns.</p>
                    <span class="mc-card__link">Learn more</span>
                </div>
            </a>
            <a href="{{ route('features') }}" class="mc-card mc-card--bordered" style="background: var(--mc-white);">
                <div class="mc-card__body mc-card__body--lg">
                    <div style="font-size: 40px; margin-bottom: var(--space-md);">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--mc-teal)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M13.8 12H3"/></svg>
                    </div>
                    <h4 class="mc-card__title">Landing Pages</h4>
                    <p class="mc-card__desc">Build subscription landing pages with a drag-and-drop builder. Capture leads with embedded forms and pop-ups, and automatically add subscribers to your mailing lists with tags and custom field mapping.</p>
                    <span class="mc-card__link">Learn more</span>
                </div>
            </a>
            <a href="{{ route('features') }}" class="mc-card mc-card--bordered" style="background: var(--mc-white);">
                <div class="mc-card__body mc-card__body--lg">
                    <div style="font-size: 40px; margin-bottom: var(--space-md);">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--mc-teal)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </div>
                    <h4 class="mc-card__title">Signup Forms</h4>
                    <p class="mc-card__desc">Grow your audience with embedded forms, pop-ups, and hosted signup pages. Customize every field, add tags automatically, and trigger welcome emails instantly. GDPR-compliant with double opt-in and consent tracking built in.</p>
                    <span class="mc-card__link">Learn more</span>
                </div>
            </a>
            <a href="{{ route('features') }}" class="mc-card mc-card--bordered" style="background: var(--mc-white);">
                <div class="mc-card__body mc-card__body--lg">
                    <div style="font-size: 40px; margin-bottom: var(--space-md);">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--mc-teal)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                    </div>
                    <h4 class="mc-card__title">Multi-Tenant Support</h4>
                    <p class="mc-card__desc">Run AcelleMail as a SaaS business for your clients. Create sub-accounts with their own sending limits, mailing lists, and billing plans. Perfect for agencies and resellers who want to offer white-label email marketing.</p>
                    <span class="mc-card__link">Learn more</span>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- ======================================================================
     ANALYTICS & REPORTING DEEP DIVE
     ====================================================================== -->
<section class="mc-feature-alt">
    <div class="mc-container">
        <div class="mc-feature-alt__grid">
            <div class="mc-feature-alt__image">
                <img src="{{ asset('images/features/predictive.svg') }}" alt="Email marketing analytics and reporting dashboard" loading="lazy">
            </div>
            <div class="mc-feature-alt__content">
                <span class="mc-eyebrow">Analytics &amp; Reporting</span>
                <h2 class="mc-feature-alt__heading">Comprehensive Email Analytics</h2>
                <p class="mc-feature-alt__text">Track every metric that matters with AcelleMail&rsquo;s real-time analytics dashboard. Monitor open rates, click-through rates, bounce rates, unsubscribes, and more &mdash; all in one place. Compare campaigns side by side to identify trends and optimize future sends.</p>
                <p class="mc-feature-alt__text">Drill down into individual subscriber engagement, see geographic and device breakdowns, and export detailed reports for stakeholders. Click maps show you exactly where subscribers engage in every email, helping you refine your layout and CTA placement.</p>
                <ul class="mc-feature-list">
                    <li class="mc-feature-list__item">Real-time campaign performance dashboards</li>
                    <li class="mc-feature-list__item">Open, click, bounce, and unsubscribe tracking</li>
                    <li class="mc-feature-list__item">Click maps showing exactly where subscribers engage</li>
                    <li class="mc-feature-list__item">Geographic and device breakdown reports</li>
                    <li class="mc-feature-list__item">Campaign comparison reports across time periods</li>
                </ul>
                <a href="{{ route('features') }}" class="mc-feature-alt__link">Explore analytics &rarr;</a>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     AUTOMATION SECTION
     ====================================================================== -->
<section class="mc-feature-alt mc-feature-alt--reverse" style="background: var(--mc-cream);">
    <div class="mc-container">
        <div class="mc-feature-alt__grid">
            <div class="mc-feature-alt__content">
                <span class="mc-eyebrow">Marketing Automations</span>
                <h2 class="mc-feature-alt__heading">Automated Email Journeys</h2>
                <p class="mc-feature-alt__text">Combine email marketing with powerful automation to create sophisticated customer journeys. From welcome series and follow-up sequences to trigger-based campaigns, AcelleMail&rsquo;s automation builder lets you map out every touchpoint.</p>
                <p class="mc-feature-alt__text">Set triggers based on subscriber actions, dates, custom fields, or engagement levels. Add time delays and conditional logic to create journeys that feel personal and drive conversions on autopilot.</p>
                <ul class="mc-feature-list">
                    <li class="mc-feature-list__item">Visual automation builder with drag-and-drop workflow design</li>
                    <li class="mc-feature-list__item">Trigger-based emails: subscription, opens, clicks, dates</li>
                    <li class="mc-feature-list__item">Time delays and conditional branching</li>
                    <li class="mc-feature-list__item">Welcome series, drip campaigns, and re-engagement flows</li>
                    <li class="mc-feature-list__item">Automation performance analytics and reporting</li>
                </ul>
                <a href="{{ route('automation') }}" class="mc-feature-alt__link">Explore automations &rarr;</a>
            </div>
            <div class="mc-feature-alt__image">
                <img src="{{ asset('images/features/automations-ecom.svg') }}" alt="Email automation builder with triggers and workflows" loading="lazy">
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     PRICING CTA
     ====================================================================== -->
<section class="mc-plan-cta" style="background: var(--mc-light-gray);">
    <div class="mc-container">
        <div class="mc-plan-cta__inner">
            <div class="mc-plan-cta__content">
                <h2 class="mc-plan-cta__heading">One-time purchase. No monthly fees.</h2>
                <p class="mc-plan-cta__text">Get AcelleMail from CodeCanyon with a one-time license fee. Install on your own server and connect any sending service &mdash; Amazon SES, SendGrid, SparkPost, Mailgun, or your own SMTP. No per-subscriber fees, no sending limits, and lifetime updates included.</p>
                <ul class="mc-plan-cta__features">
                    <li class="mc-plan-cta__feature">
                        <svg class="mc-plan-cta__check" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        One-time license fee &mdash; no recurring charges
                    </li>
                    <li class="mc-plan-cta__feature">
                        <svg class="mc-plan-cta__check" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Unlimited subscribers and mailing lists
                    </li>
                    <li class="mc-plan-cta__feature">
                        <svg class="mc-plan-cta__check" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Send 100,000 emails for $10 via Amazon SES
                    </li>
                    <li class="mc-plan-cta__feature">
                        <svg class="mc-plan-cta__check" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Full source code &mdash; customize anything
                    </li>
                    <li class="mc-plan-cta__feature">
                        <svg class="mc-plan-cta__check" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        6 months of support included
                    </li>
                    <li class="mc-plan-cta__feature">
                        <svg class="mc-plan-cta__check" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        Self-hosted &mdash; 100% data ownership
                    </li>
                </ul>
                <div style="display: flex; gap: var(--space-md); align-items: center; flex-wrap: wrap;">
                    <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" class="mc-btn mc-btn--primary mc-btn--lg">Buy on CodeCanyon</a>
                    <a href="https://demo.acellemail.com" class="mc-btn mc-btn--secondary mc-btn--lg">Try Live Demo</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     FAQ — Self-Hosted Email Marketing Questions
     ====================================================================== -->
<section class="mc-faq">
    <div class="mc-container">
        <h2 class="mc-faq__heading">Frequently Asked Questions</h2>
        <div class="mc-faq__list">

            <div class="mc-faq__item">
                <button class="mc-faq__question" type="button" aria-expanded="false">
                    <span>What is AcelleMail and how is it different from SaaS email platforms?</span>
                    <svg class="mc-faq__icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="mc-faq__answer">
                    <p>AcelleMail is a self-hosted email marketing web application that you install on your own server. Unlike SaaS platforms like Mailchimp or Sendinblue that charge monthly fees based on subscriber count, AcelleMail is a one-time purchase. You own the software and all your data stays on your server.</p>
                    <p>You connect your own sending service (Amazon SES, SendGrid, SparkPost, etc.) and pay only for what you send. For example, Amazon SES charges $0.10 per 1,000 emails &mdash; meaning you can send 100,000 emails for just $10, compared to $299+/month on most SaaS platforms for the same volume.</p>
                </div>
            </div>

            <div class="mc-faq__item">
                <button class="mc-faq__question" type="button" aria-expanded="false">
                    <span>How much does it cost to send emails with AcelleMail?</span>
                    <svg class="mc-faq__icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="mc-faq__answer">
                    <p>AcelleMail itself is a one-time purchase from CodeCanyon. Your ongoing cost is only the sending service you choose. With Amazon SES, sending costs just $0.10 per 1,000 emails. That means sending to a list of 100,000 subscribers costs approximately $10 per campaign &mdash; a fraction of what SaaS platforms charge.</p>
                    <p>Other supported services like SendGrid, SparkPost, and Elastic Email offer competitive pricing as well. You can even use your own SMTP server for zero additional sending costs if you manage your own mail infrastructure.</p>
                </div>
            </div>

            <div class="mc-faq__item">
                <button class="mc-faq__question" type="button" aria-expanded="false">
                    <span>What are the server requirements to run AcelleMail?</span>
                    <svg class="mc-faq__icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="mc-faq__answer">
                    <p>AcelleMail runs on any standard Linux web server with PHP 8.0+, MySQL 5.7+ (or MariaDB), and a web server like Apache or Nginx. A VPS with 2GB RAM is sufficient for most installations. You can use any hosting provider including DigitalOcean, AWS, Linode, or Vultr.</p>
                    <p>Installation is straightforward with our step-by-step guide, or you can use our installation service to have our team set everything up for you. The application includes a built-in installer that walks you through database setup, sending server configuration, and initial admin account creation.</p>
                </div>
            </div>

            <div class="mc-faq__item">
                <button class="mc-faq__question" type="button" aria-expanded="false">
                    <span>How do I ensure good email deliverability with AcelleMail?</span>
                    <svg class="mc-faq__icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="mc-faq__answer">
                    <p>AcelleMail includes built-in tools for domain authentication (SPF, DKIM, DMARC), bounce handling, and feedback loop integration. When you use a reputable sending service like Amazon SES or SendGrid, you benefit from their established IP reputation and infrastructure.</p>
                    <p>AcelleMail also includes email verification to clean your lists before sending, reducing bounces and protecting your sender reputation. You can set per-server sending limits and throttling to warm up new sending domains gradually. The built-in blacklisting feature lets you block problematic addresses and domains.</p>
                </div>
            </div>

            <div class="mc-faq__item">
                <button class="mc-faq__question" type="button" aria-expanded="false">
                    <span>Can I use AcelleMail to run an email marketing service for my clients?</span>
                    <svg class="mc-faq__icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="mc-faq__answer">
                    <p>Yes, AcelleMail has built-in multi-tenant support designed for agencies and resellers. You can create sub-accounts for each client with their own sending limits, mailing lists, templates, and campaigns. Each client gets their own dashboard while you maintain full administrative control.</p>
                    <p>You can define billing plans with different sending quotas and feature access levels, making it easy to offer tiered email marketing services. The platform supports white-labeling so your clients see your brand, not AcelleMail&rsquo;s. This makes it ideal for starting your own email marketing SaaS business.</p>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection

@push('jsonld')
@include('partials.seo.jsonld-breadcrumb', ['breadcrumbTitle' => 'Email Marketing'])
@include('partials.seo.jsonld-faq', ['faqs' => [
    ['question' => 'What is AcelleMail and how is it different from SaaS email platforms?', 'answer' => 'AcelleMail is a self-hosted email marketing web application that you install on your own server. Unlike SaaS platforms like Mailchimp or Sendinblue that charge monthly fees based on subscriber count, AcelleMail is a one-time purchase. You own the software and all your data stays on your server. You connect your own sending service (Amazon SES, SendGrid, SparkPost, etc.) and pay only for what you send.'],
    ['question' => 'How much does it cost to send emails with AcelleMail?', 'answer' => 'AcelleMail itself is a one-time purchase from CodeCanyon. Your ongoing cost is only the sending service you choose. With Amazon SES, sending costs just $0.10 per 1,000 emails. That means sending to a list of 100,000 subscribers costs approximately $10 per campaign.'],
    ['question' => 'What are the server requirements to run AcelleMail?', 'answer' => 'AcelleMail runs on any standard Linux web server with PHP 8.0+, MySQL 5.7+ (or MariaDB), and a web server like Apache or Nginx. A VPS with 2GB RAM is sufficient for most installations.'],
    ['question' => 'How do I ensure good email deliverability with AcelleMail?', 'answer' => 'AcelleMail includes built-in tools for domain authentication (SPF, DKIM, DMARC), bounce handling, and feedback loop integration. It also includes email verification to clean your lists before sending, reducing bounces and protecting your sender reputation.'],
    ['question' => 'Can I use AcelleMail to run an email marketing service for my clients?', 'answer' => 'Yes, AcelleMail has built-in multi-tenant support designed for agencies and resellers. You can create sub-accounts for each client with their own sending limits, mailing lists, templates, and campaigns. The platform supports white-labeling so your clients see your brand.'],
]])
@endpush
