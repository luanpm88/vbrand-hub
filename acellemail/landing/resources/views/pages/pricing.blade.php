@extends('layouts.app')

@section('title', 'AcelleMail Pricing — From $74 One-Time, No Monthly Fees')
@section('meta_description', 'AcelleMail starts at $74 — one-time payment, lifetime updates, full source code. No monthly fees, no per-subscriber charges. Buy today on CodeCanyon.')
@section('og_title', 'Simple, One-Time Pricing — AcelleMail')

@section('content')

<style>
  /* Hero price cards — clickable scroll-anchors to #pricing-plans */
  a.mc-pricing-hero__price-card { cursor: pointer; }
</style>

<!-- Hero / Tab Section -->
<section class="mc-pricing-hero" style="background-color: var(--mc-cream);">
    <div class="mc-container">
        <div class="mc-pricing-hero__tabs">
            <a href="{{ route('pricing') }}" class="mc-pricing-hero__tab mc-pricing-hero__tab--active">Web platform</a>
            <a href="{{ route('aurius') }}" class="mc-pricing-hero__tab">Add-ons &amp; subscription</a>
            <a href="{{ route('contact') }}" class="mc-pricing-hero__tab">Custom Solutions</a>
        </div>
        <div class="mc-pricing-hero__grid">
            <div class="mc-pricing-hero__content">
                <p class="mc-hero__eyebrow">NO MONTHLY FEES. NO PER-SUBSCRIBER CHARGES.</p>
                <h1 class="mc-pricing-hero__heading">Pay Once,<br>Own Forever</h1>
                <p class="mc-pricing-hero__subheading">Self-hosted on your own server with full source code included. You control everything &mdash; no recurring costs, no limits.</p>
                <div class="mc-pricing-hero__prices">
                    <a href="#pricing-plans" class="mc-pricing-hero__price-card" style="display:block;text-decoration:none;color:inherit;">
                        <span class="mc-pricing-hero__price-amount-inline">$74</span>
                        <span class="mc-pricing-hero__price-label">Regular License</span>
                        <span class="mc-pricing-hero__price-desc">Single domain, full source</span>
                    </a>
                    <a href="#pricing-plans" class="mc-pricing-hero__price-card mc-pricing-hero__price-card--featured" style="display:block;text-decoration:none;color:inherit;">
                        <span class="mc-pricing-hero__price-badge">Best Value</span>
                        <span class="mc-pricing-hero__price-amount-inline">$199</span>
                        <span class="mc-pricing-hero__price-label">Extended License</span>
                        <span class="mc-pricing-hero__price-desc">SaaS-ready, white-label</span>
                    </a>
                </div>
                <div style="display: flex; gap: var(--space-md); align-items: center; flex-wrap: wrap;">
                    <a href="https://acellemail.com/demo" class="mc-btn mc-btn--secondary mc-btn--lg">Try Live Demo</a>
                </div>
                <div class="mc-pricing-hero__trust">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <span>Lifetime updates</span>
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <span>6-month support</span>
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <span>Trusted since 2016</span>
                </div>
            </div>
            <div class="mc-pricing-hero__image">
                <img src="{{ $themeImg('images/features/pricing-hero.svg') }}" alt="AcelleMail lifetime license — full source code, unlimited subscribers, free updates" width="520" height="400" decoding="async">
            </div>
        </div>
    </div>
</section>

<!-- Pricing Plans -->
<section class="mc-pricing-plans" id="pricing-plans">
    <div class="mc-container">
        <div class="mc-pricing-plans__grid">

            <!-- Regular License -->
            <div class="mc-pricing-plans__card">
                <div class="mc-pricing-plans__card-header">
                    <h3 class="mc-pricing-plans__plan-name">Regular License</h3>
                    <p class="mc-pricing-plans__sends">Single domain, full source code included</p>
                </div>
                <div class="mc-pricing-plans__card-body">
                    <div class="mc-pricing-plans__price">
                        <span class="mc-pricing-plans__price-amount">$74.00</span>
                        <span class="mc-pricing-plans__price-period">one-time</span>
                    </div>
                    <ul class="mc-pricing-plans__features">
                        <li class="mc-pricing-plans__feature">
                            <svg class="mc-pricing-plans__check" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            Full PHP source code included
                        </li>
                        <li class="mc-pricing-plans__feature">
                            <svg class="mc-pricing-plans__check" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            Use on a single end product
                        </li>
                        <li class="mc-pricing-plans__feature">
                            <svg class="mc-pricing-plans__check" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            Lifetime free updates included
                        </li>
                        <li class="mc-pricing-plans__feature">
                            <svg class="mc-pricing-plans__check" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            6 months of technical support
                        </li>
                        <li class="mc-pricing-plans__feature">
                            <svg class="mc-pricing-plans__check" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            Unlimited subscribers &amp; emails
                        </li>
                        <li class="mc-pricing-plans__feature">
                            <svg class="mc-pricing-plans__check" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            Self-hosted on your own server
                        </li>
                        <li class="mc-pricing-plans__feature">
                            <svg class="mc-pricing-plans__check" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            Cannot charge end users for access
                        </li>
                    </ul>
                </div>
                <div class="mc-pricing-plans__card-footer">
                    <a href="https://codecanyon.net/cart/add_items?item_ids=17796082" class="mc-btn mc-btn--primary">Buy Regular License — $74</a>
                    <a href="{{ route('features') }}" class="mc-btn mc-btn--outline">See All Features</a>
                </div>
            </div>

            <!-- Extended License (Featured) -->
            <div class="mc-pricing-plans__card mc-pricing-plans__card--featured">
                <div class="mc-pricing-plans__badge">Best for SaaS</div>
                <div class="mc-pricing-plans__card-header">
                    <h3 class="mc-pricing-plans__plan-name">Extended License</h3>
                    <p class="mc-pricing-plans__sends">Build your own SaaS email platform</p>
                </div>
                <div class="mc-pricing-plans__card-body">
                    <div class="mc-pricing-plans__price">
                        <span class="mc-pricing-plans__price-amount">$199.00</span>
                        <span class="mc-pricing-plans__price-period">one-time</span>
                    </div>
                    <ul class="mc-pricing-plans__features">
                        <li class="mc-pricing-plans__feature">
                            <svg class="mc-pricing-plans__check" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            Everything in Regular License
                        </li>
                        <li class="mc-pricing-plans__feature">
                            <svg class="mc-pricing-plans__check" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            SaaS framework &mdash; charge end users
                        </li>
                        <li class="mc-pricing-plans__feature">
                            <svg class="mc-pricing-plans__check" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            Multi-tenant user management
                        </li>
                        <li class="mc-pricing-plans__feature">
                            <svg class="mc-pricing-plans__check" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            Subscription plans &amp; billing built-in
                        </li>
                        <li class="mc-pricing-plans__feature">
                            <svg class="mc-pricing-plans__check" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            White-label ready out of the box
                        </li>
                    </ul>
                    <p style="font-size: 13px; color: var(--theme-text-tertiary); margin-top: var(--space-md); padding-top: var(--space-md); border-top: 1px dashed var(--theme-border);">Building plugins for SaaS resale? <a href="{{ route('for.developers') }}" style="color: var(--theme-primary); font-weight: 600;">See what developers can build &rarr;</a></p>
                </div>
                <div class="mc-pricing-plans__card-footer">
                    <a href="https://codecanyon.net/cart/add_items?item_ids=17796082" class="mc-btn mc-btn--primary">Buy Extended License — $199</a>
                    <a href="{{ route('features') }}" class="mc-btn mc-btn--outline">Compare Licenses</a>
                </div>
            </div>

            <!-- Free 6-month support (included with every license) -->
            <div class="mc-pricing-plans__card">
                <div class="mc-pricing-plans__card-header">
                    <h3 class="mc-pricing-plans__plan-name">Free 6-month support</h3>
                    <p class="mc-pricing-plans__sends">Included with every license</p>
                </div>
                <div class="mc-pricing-plans__card-body">
                    <p class="mc-pricing-plans__limits">No extra cost &mdash; starts the day you buy</p>
                    <ul class="mc-pricing-plans__features">
                        <li class="mc-pricing-plans__feature">
                            <svg class="mc-pricing-plans__check" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            Email ticket support
                        </li>
                        <li class="mc-pricing-plans__feature">
                            <svg class="mc-pricing-plans__check" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            Installation &amp; setup guidance
                        </li>
                        <li class="mc-pricing-plans__feature">
                            <svg class="mc-pricing-plans__check" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            Bug fixes &amp; product updates
                        </li>
                    </ul>
                </div>
                <div class="mc-pricing-plans__card-footer">
                    <a href="{{ route('contact') }}" class="mc-btn mc-btn--outline">Contact support</a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Sending Services — Logo Row -->
<section class="mc-services-bar">
    <div class="mc-container">
        <h3 class="mc-services-bar__title">Works with your favorite sending services</h3>
        <div class="mc-services-bar__logos">
            <div class="mc-services-bar__item"><img src="{{ asset('images/services/amazon-ses.svg') }}" alt="Amazon SES" width="36" height="36" loading="lazy"><span>Amazon SES</span></div>
            <div class="mc-services-bar__item"><img src="{{ asset('images/services/sendgrid.svg') }}" alt="SendGrid" width="36" height="36" loading="lazy"><span>SendGrid</span></div>
            <div class="mc-services-bar__item"><img src="{{ asset('images/services/sparkpost.svg') }}" alt="SparkPost" width="36" height="36" loading="lazy"><span>SparkPost</span></div>
            <div class="mc-services-bar__item"><img src="{{ asset('images/services/mailgun.svg') }}" alt="Mailgun" width="36" height="36" loading="lazy"><span>Mailgun</span></div>
            <div class="mc-services-bar__item"><img src="{{ asset('images/services/postmark.svg') }}" alt="Postmark" width="36" height="36" loading="lazy"><span>Postmark</span></div>
            <div class="mc-services-bar__item"><img src="{{ asset('images/services/elastic-email.svg') }}" alt="Elastic Email" width="36" height="36" loading="lazy"><span>Elastic Email</span></div>
        </div>
    </div>
</section>

<!-- Not Sure Section -->
<section class="mc-pricing-help">
    <div class="mc-container">
        <div class="mc-pricing-help__inner">
            <h2 class="mc-pricing-help__heading">Not sure which license to pick?</h2>
            <p class="mc-pricing-help__desc">If you plan to use AcelleMail for your own business, choose Regular. If you want to resell email marketing as a service and charge your users, choose Extended.</p>
            <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" class="mc-btn mc-btn--primary mc-btn--lg">View on CodeCanyon</a>
        </div>
    </div>
</section>

<!-- ======================================================================
     Feature Comparison Table
     ====================================================================== -->
<section class="mc-section mc-section--lg">
    <div class="mc-container">
        <h2 style="font-family: var(--font-serif); font-size: clamp(28px, 3.5vw, 40px); font-weight: 300; text-align: center; color: var(--mc-black); margin-bottom: var(--space-sm);">Compare licenses in detail</h2>
        <p style="text-align: center; color: var(--mc-gray); font-size: 16px; margin-bottom: var(--space-2xl);">See exactly what&rsquo;s included in each license so you can choose the right one for your project.</p>
        <div class="mc-comparison">
            <table>
                <thead>
                    <tr>
                        <th>Feature</th>
                        <th>Regular ($74)</th>
                        <th>Extended ($199)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Full PHP source code</td>
                        <td><span class="mc-check">&check;</span></td>
                        <td><span class="mc-check">&check;</span></td>
                    </tr>
                    <tr>
                        <td>Lifetime updates</td>
                        <td><span class="mc-check">&check;</span></td>
                        <td><span class="mc-check">&check;</span></td>
                    </tr>
                    <tr>
                        <td>6 months support</td>
                        <td><span class="mc-check">&check;</span></td>
                        <td><span class="mc-check">&check;</span></td>
                    </tr>
                    <tr>
                        <td>Unlimited subscribers</td>
                        <td><span class="mc-check">&check;</span></td>
                        <td><span class="mc-check">&check;</span></td>
                    </tr>
                    <tr>
                        <td>Unlimited email sends</td>
                        <td><span class="mc-check">&check;</span></td>
                        <td><span class="mc-check">&check;</span></td>
                    </tr>
                    <tr>
                        <td>Self-hosted deployment</td>
                        <td><span class="mc-check">&check;</span></td>
                        <td><span class="mc-check">&check;</span></td>
                    </tr>
                    <tr>
                        <td>Charge end users (SaaS)</td>
                        <td><span class="mc-cross">&mdash;</span></td>
                        <td><span class="mc-check">&check;</span></td>
                    </tr>
                    <tr>
                        <td>Multi-tenant management</td>
                        <td><span class="mc-cross">&mdash;</span></td>
                        <td><span class="mc-check">&check;</span></td>
                    </tr>
                    <tr>
                        <td>Subscription &amp; billing</td>
                        <td><span class="mc-cross">&mdash;</span></td>
                        <td><span class="mc-check">&check;</span></td>
                    </tr>
                    <tr>
                        <td>White-label branding</td>
                        <td><span class="mc-cross">&mdash;</span></td>
                        <td><span class="mc-check">&check;</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- ======================================================================
     Sending Service Cost Comparison
     ====================================================================== -->
<section class="mc-feature-alt" style="background: var(--mc-light-gray);">
    <div class="mc-container">
        <div class="mc-feature-alt__grid">
            <div class="mc-feature-alt__image">
                <img src="{{ $themeImg('images/features/email-sms.svg') }}" alt="Sending cost comparison chart" loading="lazy" width="520" height="400" decoding="async">
            </div>
            <div class="mc-feature-alt__content">
                <span class="mc-eyebrow">Cost Comparison</span>
                <h2 class="mc-feature-alt__heading">See how much you save with self-hosting</h2>
                <p class="mc-feature-alt__text">With AcelleMail you bring your own SMTP service &mdash; no per-subscriber pricing, no monthly platform fees. Using Amazon SES at just $0.10 per 1,000 emails, sending 100K emails costs only $10 compared to $350+/month on Mailchimp or similar SaaS platforms.</p>
                <ul class="mc-feature-list">
                    <li class="mc-feature-list__item">Amazon SES: ~$0.10 per 1,000 emails sent</li>
                    <li class="mc-feature-list__item">SendGrid Free: 100 emails/day at no cost</li>
                    <li class="mc-feature-list__item">Mailgun: $0.80 per 1,000 emails sent</li>
                    <li class="mc-feature-list__item">Your own SMTP server: completely free</li>
                    <li class="mc-feature-list__item">No per-subscriber fees &mdash; grow without limits</li>
                </ul>
                <a href="{{ route('features') }}" class="mc-feature-alt__link">See all supported sending services &rarr;</a>
                <p style="margin-top: var(--space-md); font-size: 14px; color: var(--mc-gray);">
                    Comparing email marketing tools? See the full <a href="{{ route('compare.show', ['slug' => 'mailchimp']) }}" style="color: var(--mc-teal); text-decoration: underline;">AcelleMail vs Mailchimp side-by-side breakdown</a>.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     Testimonial / Case Study
     ====================================================================== -->
<section class="mc-case-study">
    <div class="mc-container">
        <div class="mc-case-study__inner">
            <div class="mc-case-study__image">
                <img src="{{ $themeImg('images/features/case-study-savings.svg') }}" alt="Save $12,000+ per year" style="width:100%;border-radius:var(--radius-lg);" loading="lazy" width="520" height="300" decoding="async">
            </div>
            <div class="mc-case-study__content">
                <blockquote class="mc-case-study__quote">
                    Trade $500/month on Mailchimp for a one-time $199 Extended License. Run your own email marketing SaaS, charge end users, and pay only $15/month for Amazon SES &mdash; saving thousands every year.
                </blockquote>
                <p class="mc-case-study__attribution">&mdash; The math behind AcelleMail&rsquo;s SaaS framework, used by agencies worldwide</p>
                <div class="mc-case-study__stats">
                    <div class="mc-case-study__stat">
                        <span class="mc-case-study__stat-number">97%</span>
                        <span class="mc-case-study__stat-label">Cost savings vs SaaS</span>
                    </div>
                    <div class="mc-case-study__stat-divider"></div>
                    <div class="mc-case-study__stat">
                        <span class="mc-case-study__stat-number">$199</span>
                        <span class="mc-case-study__stat-label">One-time investment</span>
                    </div>
                    <div class="mc-case-study__stat-divider"></div>
                    <div class="mc-case-study__stat">
                        <span class="mc-case-study__stat-number">500K+</span>
                        <span class="mc-case-study__stat-label">Emails sent per month</span>
                    </div>
                </div>
                <a href="https://codecanyon.net/cart/add_items?item_ids=17796082" class="mc-case-study__link">Get AcelleMail on CodeCanyon &rarr;</a>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     NEWSLETTER BAND (mid-page) — between pricing table and enterprise
     CTA. Visitor compared plans, isn't ready to checkout — staying in
     touch via newsletter is the next-best step. Source = pricing-band.
     ====================================================================== -->
<section class="mc-section mc-newsletter-band">
    <div class="mc-container mc-container--narrow">
        <x-newsletter.inline
            source="pricing-band"
            variant="band"
            title="Not ready to buy yet?"
            subtitle="Subscribe and we'll send you release notes, deliverability tips, and self-hosting playbooks once a month."
            cta="Stay in touch"
        />
    </div>
</section>

<!-- ======================================================================
     Enterprise / Custom Solution
     ====================================================================== -->
<section class="mc-section mc-section--cream mc-section--lg">
    <div class="mc-container">
        <div style="max-width: 680px; margin: 0 auto; text-align: center;">
            <span class="mc-eyebrow" style="display: block; margin-bottom: var(--space-sm);">Enterprise</span>
            <h2 style="font-family: var(--font-serif); font-size: clamp(28px, 3.5vw, 40px); font-weight: 300; color: var(--mc-black); margin-bottom: var(--space-md); line-height: 1.2;">Need white-label or custom development?</h2>
            <p style="font-size: 17px; color: var(--mc-gray); line-height: 1.7; margin-bottom: var(--space-lg);">For agencies and enterprises that need a fully branded email marketing platform, we offer custom development services. White-label the entire application, add custom integrations, build bespoke features, and get priority support from the core development team.</p>
            <p style="font-size: 15px; color: var(--mc-gray); line-height: 1.7; margin-bottom: var(--space-xl);">Includes complete brand customization, custom SMTP integration, dedicated deployment assistance, performance optimization, and a direct line to our senior engineers.</p>
            <a href="{{ route('contact') }}" class="mc-btn mc-btn--primary mc-btn--lg">Contact Our Team</a>
        </div>
    </div>
</section>

<!-- ======================================================================
     Pricing FAQ
     ====================================================================== -->
<section class="mc-faq">
    <div class="mc-container">
        <h2 class="mc-faq__heading">Licensing questions, answered</h2>
        <div class="mc-faq__list">

            <div class="mc-faq__item">
                <button class="mc-faq__question" type="button" aria-expanded="false">
                    <span>What&rsquo;s included in my purchase?</span>
                    <svg class="mc-faq__icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="mc-faq__answer">
                    <p>Every purchase includes the complete AcelleMail source code (PHP/Laravel), lifetime free updates, 6 months of technical support from our team, full documentation, and access to our community forum. You can install it on any Linux server with PHP and MySQL &mdash; no additional licensing fees ever.</p>
                </div>
            </div>

            <div class="mc-faq__item">
                <button class="mc-faq__question" type="button" aria-expanded="false">
                    <span>Can I modify the source code?</span>
                    <svg class="mc-faq__icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="mc-faq__answer">
                    <p>Yes, absolutely. You receive the full unencrypted PHP source code and are free to modify, customize, and extend it to fit your exact needs. Add custom features, change the design, integrate with your existing tools &mdash; it&rsquo;s your code. The only restriction is redistribution of the source code itself.</p>
                </div>
            </div>

            <div class="mc-faq__item">
                <button class="mc-faq__question" type="button" aria-expanded="false">
                    <span>Can I build a SaaS with AcelleMail?</span>
                    <svg class="mc-faq__icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="mc-faq__answer">
                    <p>Yes &mdash; with the Extended License ($199). The Extended License allows you to charge end users for access to your email marketing platform. It includes built-in multi-tenant management, subscription plans with Stripe/PayPal billing, and white-label branding. The Regular License does not permit charging end users.</p>
                </div>
            </div>

            <div class="mc-faq__item">
                <button class="mc-faq__question" type="button" aria-expanded="false">
                    <span>How do updates work after purchase?</span>
                    <svg class="mc-faq__icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="mc-faq__answer">
                    <p>All updates are free for life &mdash; no recurring fee required. When we release a new version, you can download it from CodeCanyon and update your installation. We regularly release updates with new features, security patches, and performance improvements. Your 6-month support period is separate from updates.</p>
                </div>
            </div>

            <div class="mc-faq__item">
                <button class="mc-faq__question" type="button" aria-expanded="false">
                    <span>What if I need support after 6 months?</span>
                    <svg class="mc-faq__icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="mc-faq__answer">
                    <p>Every license includes 6 months of free support out of the box. After that, you can extend support directly through CodeCanyon, or reach out to us via the contact form for one-off assistance. Our documentation covers most common setup and usage questions, and our community forum is always free.</p>
                </div>
            </div>

            <div class="mc-faq__item">
                <button class="mc-faq__question" type="button" aria-expanded="false">
                    <span>Is there a refund policy?</span>
                    <svg class="mc-faq__icon" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <div class="mc-faq__answer">
                    <p>Purchases are covered by Envato&rsquo;s refund policy. If the item is significantly different from its description or doesn&rsquo;t work as advertised, you can request a refund through CodeCanyon. We also offer free pre-purchase support &mdash; contact us with any questions before buying to make sure AcelleMail is the right fit for you.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ======================================================================
     Money-back Guarantee
     ====================================================================== -->
<section class="mc-section mc-section--lg" style="background: var(--mc-light-gray);">
    <div class="mc-container">
        <div style="max-width: 600px; margin: 0 auto; text-align: center;">
            <div style="margin-bottom: var(--space-lg);">
                <svg width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: inline-block;">
                    <circle cx="32" cy="32" r="30" stroke="var(--theme-text)" stroke-width="2.5" fill="none"/>
                    <path d="M32 12C32 12 22 18 22 30C22 42 32 52 32 52C32 52 42 42 42 30C42 18 32 12 32 12Z" stroke="var(--theme-text)" stroke-width="2" fill="none"/>
                    <path d="M26 32L30 36L38 28" stroke="var(--theme-text)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                </svg>
            </div>
            <h2 style="font-family: var(--font-serif); font-size: clamp(24px, 3vw, 36px); font-weight: 300; color: var(--mc-black); margin-bottom: var(--space-md); line-height: 1.2;">Pay once, own it forever</h2>
            <p style="font-size: 17px; color: var(--mc-gray); line-height: 1.7; margin-bottom: var(--space-lg);">No monthly subscriptions, no per-email fees, no subscriber limits. Buy AcelleMail once and run your email marketing platform on your own terms &mdash; with full source code, lifetime updates, and complete control over your data and infrastructure.</p>
            <a href="https://codecanyon.net/cart/add_items?item_ids=17796082" class="mc-btn mc-btn--primary mc-btn--lg">Get AcelleMail — $74 one-time</a>
        </div>
    </div>
</section>

@endsection

@push('jsonld')
@include('partials.seo.jsonld-breadcrumb', ['breadcrumbTitle' => 'Pricing'])
@include('partials.seo.jsonld-product')
@include('partials.seo.jsonld-faq', ['faqs' => [
    ['question' => 'What is included in my purchase?', 'answer' => 'Every purchase includes the complete AcelleMail source code (PHP/Laravel), lifetime free updates, 6 months of technical support, full documentation, and access to our community forum. You can install it on any Linux server with PHP and MySQL — no additional licensing fees ever.'],
    ['question' => 'Can I modify the source code?', 'answer' => 'Yes, absolutely. You receive the full unencrypted PHP source code and are free to modify, customize, and extend it to fit your exact needs. The only restriction is redistribution of the source code itself.'],
    ['question' => 'Can I build a SaaS with AcelleMail?', 'answer' => 'Yes — with the Extended License ($199). The Extended License allows you to charge end users for access to your email marketing platform. It includes built-in multi-tenant management, subscription plans with Stripe/PayPal billing, and white-label branding.'],
    ['question' => 'How do updates work after purchase?', 'answer' => 'All updates are free for life — no recurring fee required. When we release a new version, you can download it from CodeCanyon and update your installation.'],
    ['question' => 'What if I need support after 6 months?', 'answer' => 'Every license includes 6 months of free support out of the box. After that, you can extend support directly through CodeCanyon, or reach out via the contact form for one-off assistance. Our documentation covers most common setup and usage questions, and our community forum is always free.'],
    ['question' => 'Is there a refund policy?', 'answer' => 'Purchases are covered by Envato\'s refund policy. If the item is significantly different from its description or doesn\'t work as advertised, you can request a refund through CodeCanyon.'],
]])
@endpush
