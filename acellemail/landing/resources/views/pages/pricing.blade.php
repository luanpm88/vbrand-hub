@extends('layouts.app')

@section('title', 'Pricing | AcelleMail')

@section('content')

<!-- Hero / Tab Section -->
<section class="mc-pricing-hero" style="background-color: var(--mc-cream);">
    <div class="mc-container">
        <div class="mc-pricing-hero__tabs">
            <a href="{{ route('pricing') }}" class="mc-pricing-hero__tab mc-pricing-hero__tab--active">One-Time Pricing</a>
            <a href="{{ route('features') }}" class="mc-pricing-hero__tab">All Features</a>
            <a href="{{ route('contact') }}" class="mc-pricing-hero__tab">Custom Solutions</a>
        </div>
        <div class="mc-pricing-hero__grid">
            <div class="mc-pricing-hero__content">
                <h1 class="mc-pricing-hero__heading">Simple, One-Time Pricing</h1>
                <p class="mc-pricing-hero__subheading">No monthly fees. No per-subscriber charges. Pay once, own forever. Self-hosted on your own server with full source code included &mdash; you control everything.</p>
            </div>
            <div class="mc-pricing-hero__image">
                <img src="{{ asset('images/features/pricing-hero.svg') }}" alt="Simple, One-Time Pricing" style="width:100%;border-radius:var(--radius-lg);">
            </div>
        </div>
    </div>
</section>

<!-- Promotional Cards -->
<section class="mc-promo-cards">
    <div class="mc-container">
        <div class="mc-promo-cards__grid">
            <div class="mc-promo-cards__item">
                <h3 class="mc-promo-cards__title">Regular License &mdash; Just $64</h3>
                <p class="mc-promo-cards__price">One-time payment, lifetime updates</p>
                <span class="mc-promo-cards__badge">Full Source Code / Single Domain</span>
                <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" class="mc-btn mc-btn--primary">Buy on CodeCanyon</a>
            </div>
            <div class="mc-promo-cards__item">
                <h3 class="mc-promo-cards__title">Extended License &mdash; Best Value</h3>
                <p class="mc-promo-cards__desc">Save thousands vs monthly SaaS tools &mdash; build your own email platform</p>
                <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" class="mc-btn mc-btn--primary">Buy Now</a>
            </div>
        </div>
    </div>
</section>

<!-- Pricing Plans -->
<section class="mc-pricing-plans">
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
                        <span class="mc-pricing-plans__price-amount">$64.00</span>
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
                    <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" class="mc-btn mc-btn--primary">Buy Now</a>
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
                            Full PHP source code included
                        </li>
                        <li class="mc-pricing-plans__feature">
                            <svg class="mc-pricing-plans__check" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            6 months of technical support
                        </li>
                        <li class="mc-pricing-plans__feature">
                            <svg class="mc-pricing-plans__check" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            Lifetime free updates included
                        </li>
                        <li class="mc-pricing-plans__feature">
                            <svg class="mc-pricing-plans__check" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            White-label ready out of the box
                        </li>
                    </ul>
                </div>
                <div class="mc-pricing-plans__card-footer">
                    <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" class="mc-btn mc-btn--primary">Buy Extended</a>
                    <a href="{{ route('features') }}" class="mc-btn mc-btn--outline">Compare Licenses</a>
                </div>
            </div>

            <!-- Installation Service -->
            <div class="mc-pricing-plans__card">
                <div class="mc-pricing-plans__card-header">
                    <h3 class="mc-pricing-plans__plan-name">Installation Service</h3>
                </div>
                <div class="mc-pricing-plans__card-body">
                    <div class="mc-pricing-plans__price">
                        <span class="mc-pricing-plans__price-amount">$49.00</span>
                        <span class="mc-pricing-plans__price-period">one-time</span>
                    </div>
                    <ul class="mc-pricing-plans__features">
                        <li class="mc-pricing-plans__feature">
                            <svg class="mc-pricing-plans__check" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            Professional setup on your server
                        </li>
                        <li class="mc-pricing-plans__feature">
                            <svg class="mc-pricing-plans__check" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            SMTP &amp; sending domain configured
                        </li>
                        <li class="mc-pricing-plans__feature">
                            <svg class="mc-pricing-plans__check" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            SSL certificate setup included
                        </li>
                        <li class="mc-pricing-plans__feature">
                            <svg class="mc-pricing-plans__check" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            Cron jobs &amp; queue workers set up
                        </li>
                        <li class="mc-pricing-plans__feature">
                            <svg class="mc-pricing-plans__check" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            Done by our expert team, hassle-free
                        </li>
                        <li class="mc-pricing-plans__feature">
                            <svg class="mc-pricing-plans__check" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            Typically completed within 24 hours
                        </li>
                    </ul>
                </div>
                <div class="mc-pricing-plans__card-footer">
                    <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" class="mc-btn mc-btn--primary">Add to Order</a>
                    <a href="{{ route('contact') }}" class="mc-btn mc-btn--outline">Ask a Question</a>
                </div>
            </div>

            <!-- Annual Support -->
            <div class="mc-pricing-plans__card">
                <div class="mc-pricing-plans__card-header">
                    <h3 class="mc-pricing-plans__plan-name">Annual Support</h3>
                    <p class="mc-pricing-plans__sends">Extended priority support package</p>
                </div>
                <div class="mc-pricing-plans__card-body">
                    <p class="mc-pricing-plans__limits">Renews yearly, cancel anytime you want</p>
                    <ul class="mc-pricing-plans__features">
                        <li class="mc-pricing-plans__feature">
                            <svg class="mc-pricing-plans__check" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            Priority ticket support
                        </li>
                        <li class="mc-pricing-plans__feature">
                            <svg class="mc-pricing-plans__check" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            Server migration assistance
                        </li>
                        <li class="mc-pricing-plans__feature">
                            <svg class="mc-pricing-plans__check" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            Custom configuration help
                        </li>
                    </ul>
                </div>
                <div class="mc-pricing-plans__card-footer">
                    <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" class="mc-btn mc-btn--outline">Learn More</a>
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
            <div class="mc-services-bar__item"><img src="{{ asset('images/services/amazon-ses.svg') }}" alt="Amazon SES" width="36" height="36"><span>Amazon SES</span></div>
            <div class="mc-services-bar__item"><img src="{{ asset('images/services/sendgrid.svg') }}" alt="SendGrid" width="36" height="36"><span>SendGrid</span></div>
            <div class="mc-services-bar__item"><img src="{{ asset('images/services/sparkpost.svg') }}" alt="SparkPost" width="36" height="36"><span>SparkPost</span></div>
            <div class="mc-services-bar__item"><img src="{{ asset('images/services/mailgun.svg') }}" alt="Mailgun" width="36" height="36"><span>Mailgun</span></div>
            <div class="mc-services-bar__item"><img src="{{ asset('images/services/postmark.svg') }}" alt="Postmark" width="36" height="36"><span>Postmark</span></div>
            <div class="mc-services-bar__item"><img src="{{ asset('images/services/elastic-email.svg') }}" alt="Elastic Email" width="36" height="36"><span>Elastic Email</span></div>
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
                        <th>Regular ($64)</th>
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
                <img src="{{ asset('images/features/email-sms.svg') }}" alt="Sending cost comparison chart">
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
                <img src="{{ asset('images/features/case-study-savings.svg') }}" alt="Save $12,000+ per year" style="width:100%;border-radius:var(--radius-lg);">
            </div>
            <div class="mc-case-study__content">
                <blockquote class="mc-case-study__quote">
                    &ldquo;We switched from paying $500/month on Mailchimp to AcelleMail with a one-time $199 Extended License. Now we run our own email marketing SaaS and the only recurring cost is $15/month for Amazon SES &mdash; saving us thousands every single year.&rdquo;
                </blockquote>
                <p class="mc-case-study__attribution">&mdash; David Chen, Founder of CloudReach Marketing Agency</p>
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
                <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" class="mc-case-study__link">Get AcelleMail on CodeCanyon &rarr;</a>
            </div>
        </div>
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
                    <p>You can extend support directly through CodeCanyon, or purchase our Annual Support package ($149/year) for priority ticket support, server migration assistance, and custom configuration help. Our community forum is always free and our documentation covers most common setup and usage questions.</p>
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
                    <circle cx="32" cy="32" r="30" stroke="#241C15" stroke-width="2.5" fill="none"/>
                    <path d="M32 12C32 12 22 18 22 30C22 42 32 52 32 52C32 52 42 42 42 30C42 18 32 12 32 12Z" stroke="#241C15" stroke-width="2" fill="none"/>
                    <path d="M26 32L30 36L38 28" stroke="#241C15" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                </svg>
            </div>
            <h2 style="font-family: var(--font-serif); font-size: clamp(24px, 3vw, 36px); font-weight: 300; color: var(--mc-black); margin-bottom: var(--space-md); line-height: 1.2;">Pay once, own it forever</h2>
            <p style="font-size: 17px; color: var(--mc-gray); line-height: 1.7; margin-bottom: var(--space-lg);">No monthly subscriptions, no per-email fees, no subscriber limits. Buy AcelleMail once and run your email marketing platform on your own terms &mdash; with full source code, lifetime updates, and complete control over your data and infrastructure.</p>
            <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" class="mc-btn mc-btn--primary mc-btn--lg">Buy on CodeCanyon</a>
        </div>
    </div>
</section>

@endsection
