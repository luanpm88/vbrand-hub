@extends('layouts.app')

@section('title', 'Email Marketing Features — Builder, Automation & Analytics | AcelleMail')
@section('meta_description', 'Explore AcelleMail features: drag & drop email builder, marketing automation, A/B testing, list segmentation, analytics, and 100+ templates. Self-hosted.')
@section('og_title', 'All Features — AcelleMail Email Marketing Platform')

@section('content')

<!-- ======================================================================
     1. HERO (2-col: text LEFT, photo RIGHT)
     ====================================================================== -->
<section class="mc-features-hero">
    <div class="mc-container">
        <div class="mc-feature-alt__grid">
            <div>
                <p class="mc-features-hero__eyebrow">ACELLEMAIL FEATURES</p>
                <h1 class="mc-features-hero__heading" style="text-align:left;">Everything you need to run professional email marketing campaigns from your own server</h1>
                <div style="display:flex;gap:var(--space-md);flex-wrap:wrap;">
                    <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" class="mc-btn mc-btn--primary mc-btn--lg" target="_blank">Download AcelleMail</a>
                    <a href="#video-campaign" class="mc-btn mc-btn--secondary mc-btn--lg">Watch demo &darr;</a>
                </div>
            </div>
            <div>
                <svg viewBox="0 0 560 420" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:100%;height:auto;" aria-label="AcelleMail email campaign dashboard">
                  <!-- Laptop body -->
                  <rect x="40" y="30" width="480" height="310" rx="16" fill="#fff" stroke="var(--theme-border)" stroke-width="1.5"/>
                  <!-- Title bar -->
                  <rect x="40" y="30" width="480" height="40" rx="16" fill="var(--theme-text)"/>
                  <rect x="40" y="54" width="480" height="16" fill="var(--theme-text)"/>
                  <circle cx="64" cy="50" r="5" fill="var(--theme-primary)"/>
                  <circle cx="82" cy="50" r="5" fill="var(--theme-border)" opacity="0.4"/>
                  <circle cx="100" cy="50" r="5" fill="var(--theme-border)" opacity="0.4"/>
                  <text x="280" y="54" text-anchor="middle" font-family="IBM Plex Sans,sans-serif" font-size="12" font-weight="600" fill="#fff">AcelleMail — Campaign Dashboard</text>

                  <!-- Sidebar -->
                  <rect x="40" y="70" width="120" height="270" fill="var(--theme-bg-light)"/>
                  <rect x="56" y="88" width="88" height="10" rx="5" fill="var(--theme-text)" opacity="0.15"/>
                  <rect x="56" y="110" width="88" height="28" rx="6" fill="var(--theme-primary)"/>
                  <text x="100" y="128" text-anchor="middle" font-family="IBM Plex Sans,sans-serif" font-size="10" font-weight="600" fill="#fff">+ Campaign</text>
                  <rect x="56" y="150" width="72" height="8" rx="4" fill="var(--theme-text)" opacity="0.12"/>
                  <rect x="56" y="168" width="60" height="8" rx="4" fill="var(--theme-text)" opacity="0.08"/>
                  <rect x="56" y="186" width="80" height="8" rx="4" fill="var(--theme-text)" opacity="0.08"/>
                  <rect x="56" y="204" width="52" height="8" rx="4" fill="var(--theme-text)" opacity="0.08"/>
                  <rect x="56" y="230" width="72" height="8" rx="4" fill="var(--theme-text)" opacity="0.12"/>
                  <rect x="56" y="248" width="60" height="8" rx="4" fill="var(--theme-text)" opacity="0.08"/>
                  <rect x="56" y="266" width="44" height="8" rx="4" fill="var(--theme-text)" opacity="0.08"/>

                  <!-- Main content area -->
                  <!-- Stats row -->
                  <g transform="translate(176, 84)">
                    <rect width="100" height="60" rx="8" fill="var(--theme-bg-warm)" stroke="var(--theme-border)" stroke-width="1"/>
                    <text x="14" y="22" font-family="IBM Plex Sans,sans-serif" font-size="9" font-weight="500" fill="var(--theme-text-secondary)">Sent</text>
                    <text x="14" y="44" font-family="IBM Plex Sans,sans-serif" font-size="18" font-weight="700" fill="var(--theme-text)">12,483</text>
                  </g>
                  <g transform="translate(286, 84)">
                    <rect width="100" height="60" rx="8" fill="var(--theme-bg-warm)" stroke="var(--theme-border)" stroke-width="1"/>
                    <text x="14" y="22" font-family="IBM Plex Sans,sans-serif" font-size="9" font-weight="500" fill="var(--theme-text-secondary)">Opened</text>
                    <text x="14" y="44" font-family="IBM Plex Sans,sans-serif" font-size="18" font-weight="700" fill="var(--theme-primary)">4,821</text>
                  </g>
                  <g transform="translate(396, 84)">
                    <rect width="100" height="60" rx="8" fill="var(--theme-bg-warm)" stroke="var(--theme-border)" stroke-width="1"/>
                    <text x="14" y="22" font-family="IBM Plex Sans,sans-serif" font-size="9" font-weight="500" fill="var(--theme-text-secondary)">Clicked</text>
                    <text x="14" y="44" font-family="IBM Plex Sans,sans-serif" font-size="18" font-weight="700" fill="var(--theme-text)">1,247</text>
                  </g>

                  <!-- Chart area -->
                  <g transform="translate(176, 160)">
                    <rect width="320" height="130" rx="8" fill="var(--theme-bg-warm)" stroke="var(--theme-border)" stroke-width="1"/>
                    <text x="14" y="22" font-family="IBM Plex Sans,sans-serif" font-size="10" font-weight="600" fill="var(--theme-text)">Open Rate — Last 7 Days</text>
                    <!-- Chart bars -->
                    <rect x="24" y="95" width="28" height="20" rx="3" fill="var(--theme-border)"/>
                    <rect x="62" y="75" width="28" height="40" rx="3" fill="var(--theme-border)"/>
                    <rect x="100" y="55" width="28" height="60" rx="3" fill="var(--theme-primary)" opacity="0.3"/>
                    <rect x="138" y="45" width="28" height="70" rx="3" fill="var(--theme-primary)" opacity="0.5"/>
                    <rect x="176" y="60" width="28" height="55" rx="3" fill="var(--theme-primary)" opacity="0.4"/>
                    <rect x="214" y="38" width="28" height="77" rx="3" fill="var(--theme-primary)" opacity="0.7"/>
                    <rect x="252" y="42" width="28" height="73" rx="3" fill="var(--theme-primary)"/>
                    <!-- Trend line -->
                    <polyline points="38,90 76,70 114,52 152,40 190,55 228,34 266,38" stroke="var(--theme-primary)" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
                  </g>

                  <!-- Email preview card -->
                  <g transform="translate(176, 300)">
                    <rect width="320" height="32" rx="6" fill="#fff" stroke="var(--theme-border)" stroke-width="1"/>
                    <circle cx="20" cy="16" r="8" fill="var(--theme-primary)" opacity="0.15"/>
                    <path d="M16 16l2.5 2 4-4" stroke="var(--theme-primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <rect x="36" y="10" width="120" height="6" rx="3" fill="var(--theme-text)" opacity="0.5"/>
                    <rect x="36" y="20" width="80" height="4" rx="2" fill="var(--theme-text-tertiary)" opacity="0.4"/>
                    <text x="290" y="20" text-anchor="end" font-family="IBM Plex Sans,sans-serif" font-size="9" font-weight="600" fill="var(--theme-primary)">38.6%</text>
                  </g>

                  <!-- Laptop base -->
                  <path d="M20 340 L40 340 Q40 340 40 340 L520 340 Q520 340 520 340 L540 340 L540 355 Q540 365 530 365 L30 365 Q20 365 20 355 Z" fill="var(--theme-text)" opacity="0.06"/>
                  <rect x="200" y="342" width="160" height="8" rx="4" fill="var(--theme-border)"/>

                  <!-- Floating elements -->
                  <!-- Notification badge -->
                  <g transform="translate(460, 18)">
                    <rect width="80" height="32" rx="16" fill="var(--theme-primary)" filter="url(#shadow)"/>
                    <text x="40" y="21" text-anchor="middle" font-family="IBM Plex Sans,sans-serif" font-size="11" font-weight="600" fill="#fff">Sent &#x2713;</text>
                  </g>

                  <!-- Small envelope -->
                  <g transform="translate(8, 260)">
                    <rect width="44" height="36" rx="8" fill="var(--theme-primary)" opacity="0.1"/>
                    <path d="M10 14l12 8 12-8" stroke="var(--theme-primary)" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                    <rect x="8" y="10" width="28" height="20" rx="3" stroke="var(--theme-primary)" stroke-width="1.2" fill="none"/>
                  </g>
                </svg>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     2. CARDS: Email Campaign Management (4 cards)
     ====================================================================== -->
<section class="mc-features-grid">
    <div class="mc-container">

        <div class="mc-features-grid__section">
            <h2 class="mc-features-grid__section-title">Email Campaign Management</h2>
            <div class="mc-features-grid__cards">
                <a href="https://knowledge.acellemail.com/category/email-marketing" class="mc-features-grid__card" target="_blank">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8m-4-4v4"/></svg></div>
                    <h3 class="mc-features-grid__name">Drag &amp; Drop Builder</h3>
                    <p class="mc-features-grid__desc">Visual email editor with content blocks for images, text, buttons, videos, and social links. No coding needed &mdash; design like a pro.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="https://knowledge.acellemail.com/category/email-marketing" class="mc-features-grid__card" target="_blank">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg></div>
                    <h3 class="mc-features-grid__name">A/B Split Testing</h3>
                    <p class="mc-features-grid__desc">Test subject lines, content variations, and send times. AcelleMail picks the winner automatically and sends it to the rest of your list.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="{{ route('email-marketing') }}" class="mc-features-grid__card">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></div>
                    <h3 class="mc-features-grid__name">100+ Email Templates</h3>
                    <p class="mc-features-grid__desc">Pre-built, mobile-responsive templates for newsletters, promotions, announcements, and transactional emails. Customize colors, fonts, and layout.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="{{ route('email-marketing') }}" class="mc-features-grid__card">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
                    <h3 class="mc-features-grid__name">Scheduled &amp; Auto Sending</h3>
                    <p class="mc-features-grid__desc">Schedule campaigns for the perfect moment or let auto-scheduling pick the best time based on subscriber timezone and engagement patterns.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="https://knowledge.acellemail.com" class="mc-features-grid__card" target="_blank">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg></div>
                    <h3 class="mc-features-grid__name">Dynamic Content &amp; Spintax</h3>
                    <p class="mc-features-grid__desc">Personalize every email with merge tags, conditional content blocks, and Spintax variations. Each subscriber sees content tailored to them.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="{{ route('email-marketing') }}" class="mc-features-grid__card">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg></div>
                    <h3 class="mc-features-grid__name">Multi-Threaded Delivery</h3>
                    <p class="mc-features-grid__desc">Send to large lists fast with multi-threaded delivery. Queue-based architecture with Supervisor support ensures reliable, high-volume sending.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
            </div>
        </div>

    </div>
</section>

<!-- ======================================================================
     3. VIDEO: Create & Send a Campaign
     ====================================================================== -->
<section class="mc-feature-alt" id="video-campaign" style="background: var(--mc-light-gray);">
    <div class="mc-container">
        <div class="mc-feature-alt__grid">
            <div class="mc-feature-alt__image">
                <div class="mc-feature-alt__embed">
                    <iframe src="https://www.youtube-nocookie.com/embed/wRlfC-jccys" title="Create and send an email campaign in 3 minutes" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
            <div class="mc-feature-alt__content">
                <span class="mc-eyebrow">SEE IT IN ACTION</span>
                <h2 class="mc-feature-alt__heading">Create and send a campaign in 3 minutes</h2>
                <p class="mc-feature-alt__text">From template selection to delivery &mdash; watch how AcelleMail makes it simple to design beautiful emails, target your audience, and hit send. No coding required.</p>
                <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" class="mc-btn mc-btn--primary" target="_blank">Download AcelleMail</a>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     4. CARDS: Marketing Automation (4 cards)
     ====================================================================== -->
<section class="mc-features-grid">
    <div class="mc-container">

        <div class="mc-features-grid__section">
            <h2 class="mc-features-grid__section-title">Marketing Automation</h2>
            <div class="mc-features-grid__cards">
                <a href="{{ route('automation') }}" class="mc-features-grid__card">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></div>
                    <h3 class="mc-features-grid__name">Trigger-Based Emails</h3>
                    <p class="mc-features-grid__desc">Fire emails on subscription, unsubscription, birthdays, custom dates, or any subscriber field change. React to behavior in real time.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="{{ route('automation') }}" class="mc-features-grid__card">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg></div>
                    <h3 class="mc-features-grid__name">Visual Workflow Builder</h3>
                    <p class="mc-features-grid__desc">Drag-and-drop automation canvas with conditional splits, time delays, wait-until conditions, and multi-branch paths. See the entire journey at a glance.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="{{ route('automation') }}" class="mc-features-grid__card">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg></div>
                    <h3 class="mc-features-grid__name">Welcome &amp; Onboarding Series</h3>
                    <p class="mc-features-grid__desc">Multi-step welcome sequences that introduce new subscribers to your brand, deliver lead magnets, and drive first conversions automatically.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="{{ route('automation') }}" class="mc-features-grid__card">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><path d="M4 11a9 9 0 0 1 9 9"/><path d="M4 4a16 16 0 0 1 16 16"/><circle cx="5" cy="19" r="1"/></svg></div>
                    <h3 class="mc-features-grid__name">RSS-to-Email Campaigns</h3>
                    <p class="mc-features-grid__desc">Automatically pull your latest blog posts or news feed and send digest emails to subscribers. Set frequency &mdash; daily, weekly, or on new items.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="{{ route('automation') }}" class="mc-features-grid__card">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></div>
                    <h3 class="mc-features-grid__name">Behavioral Targeting</h3>
                    <p class="mc-features-grid__desc">Branch automations based on who opened, clicked, or ignored your emails. Re-engage inactive subscribers with targeted follow-ups.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="{{ route('automation') }}" class="mc-features-grid__card">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
                    <h3 class="mc-features-grid__name">Birthday &amp; Anniversary Emails</h3>
                    <p class="mc-features-grid__desc">Send automated birthday greetings, renewal reminders, or anniversary offers based on custom date fields in your subscriber profiles.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
            </div>
        </div>

    </div>
</section>

<!-- ======================================================================
     5. VIDEO: A/B Test Campaigns (reversed)
     ====================================================================== -->
<section class="mc-feature-alt mc-feature-alt--reverse">
    <div class="mc-container">
        <div class="mc-feature-alt__grid">
            <div class="mc-feature-alt__image">
                <div class="mc-feature-alt__embed">
                    <iframe src="https://www.youtube-nocookie.com/embed/UDdCn8ITn1c" title="A/B test your email campaigns" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
            <div class="mc-feature-alt__content">
                <span class="mc-eyebrow">OPTIMIZE EVERY SEND</span>
                <h2 class="mc-feature-alt__heading">A/B test your campaigns for better results</h2>
                <p class="mc-feature-alt__text">Test subject lines, content, and sending times to find what resonates. AcelleMail picks the winner automatically and sends it to the rest of your list.</p>
                <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" class="mc-btn mc-btn--primary" target="_blank">Download AcelleMail</a>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     6. CARDS: List Management (4 cards)
     ====================================================================== -->
<section class="mc-features-grid">
    <div class="mc-container">

        <div class="mc-features-grid__section">
            <h2 class="mc-features-grid__section-title">List Management</h2>
            <div class="mc-features-grid__cards">
                <a href="https://knowledge.acellemail.com/category/list-management" class="mc-features-grid__card" target="_blank">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg></div>
                    <h3 class="mc-features-grid__name">Mass Import &amp; Export</h3>
                    <p class="mc-features-grid__desc">Bulk import from CSV, Excel, or copy-paste. Map columns to fields, handle duplicates automatically, and export your full list anytime.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="https://knowledge.acellemail.com/category/list-management" class="mc-features-grid__card" target="_blank">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
                    <h3 class="mc-features-grid__name">Advanced Segmentation</h3>
                    <p class="mc-features-grid__desc">Create segments by combining tags, custom fields, engagement scores, open/click history, subscription date, and any custom criteria. Segments update in real time.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="https://knowledge.acellemail.com/category/list-management" class="mc-features-grid__card" target="_blank">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg></div>
                    <h3 class="mc-features-grid__name">Tags &amp; Custom Fields</h3>
                    <p class="mc-features-grid__desc">Unlimited custom fields (text, date, number, dropdown) plus tag-based organization. Use fields in merge tags, segments, and automation conditions.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="https://knowledge.acellemail.com" class="mc-features-grid__card" target="_blank">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8m-4-4v4"/></svg></div>
                    <h3 class="mc-features-grid__name">Embeddable Sign-Up Forms</h3>
                    <p class="mc-features-grid__desc">Generate HTML forms with custom fields and embed on any website. Supports single and double opt-in with custom confirmation pages.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="https://knowledge.acellemail.com/category/security-compliance" class="mc-features-grid__card" target="_blank">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v8m-4-4h8"/></svg></div>
                    <h3 class="mc-features-grid__name">Double Opt-In &amp; Consent</h3>
                    <p class="mc-features-grid__desc">GDPR-ready double opt-in with customizable confirmation emails and landing pages. Maintain clean, permission-based lists from day one.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="https://knowledge.acellemail.com/category/list-management" class="mc-features-grid__card" target="_blank">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><path d="M12 2a5 5 0 015 5c0 2.76-2.24 5-5 5s-5-2.24-5-5a5 5 0 015-5zM3 20c0-3 4-5.5 9-5.5s9 2.5 9 5.5"/></svg></div>
                    <h3 class="mc-features-grid__name">Subscriber Profiles &amp; Activity</h3>
                    <p class="mc-features-grid__desc">Full subscriber timeline showing opens, clicks, bounces, and automation history. Track engagement scores and contact activity over time.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
            </div>
        </div>

    </div>
</section>

<!-- ======================================================================
     7. VIDEO: Create Mail List & Subscribers
     ====================================================================== -->
<section class="mc-feature-alt" style="background: var(--mc-cream);">
    <div class="mc-container">
        <div class="mc-feature-alt__grid">
            <div class="mc-feature-alt__image">
                <div class="mc-feature-alt__embed">
                    <iframe src="https://www.youtube-nocookie.com/embed/1u-D6LJSk80" title="Create a mail list and add subscribers in 2 minutes" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
            <div class="mc-feature-alt__content">
                <span class="mc-eyebrow">LIST BUILDING</span>
                <h2 class="mc-feature-alt__heading">Build your subscriber list in minutes</h2>
                <p class="mc-feature-alt__text">Import from CSV, paste from Excel, or let subscribers sign up through embedded forms. Organize with tags, segments, and custom fields &mdash; all from one dashboard.</p>
                <a href="https://demo.acellemail.com" class="mc-btn mc-btn--primary" target="_blank">Try Live Demo</a>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     8. CARDS: Deliverability & Analytics (4 cards — merged)
     ====================================================================== -->
<section class="mc-features-grid">
    <div class="mc-container">

        <div class="mc-features-grid__section">
            <h2 class="mc-features-grid__section-title">Deliverability &amp; Analytics</h2>
            <div class="mc-features-grid__cards">
                <a href="https://knowledge.acellemail.com/category/sending-deliverability" class="mc-features-grid__card" target="_blank">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></div>
                    <h3 class="mc-features-grid__name">SPF, DKIM &amp; DMARC</h3>
                    <p class="mc-features-grid__desc">Built-in domain authentication setup with step-by-step DNS guidance. Verify your sending domain to maximize inbox placement and sender reputation.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="https://knowledge.acellemail.com/category/sending-deliverability" class="mc-features-grid__card" target="_blank">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg></div>
                    <h3 class="mc-features-grid__name">Email Verification</h3>
                    <p class="mc-features-grid__desc">Verify email addresses before sending using built-in checks or integrations with Emailable, Kickbox, ZeroBounce, and debounce.io. Reduce bounces automatically.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="https://knowledge.acellemail.com/category/sending-deliverability" class="mc-features-grid__card" target="_blank">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><path d="M2 20h.01M7 20v-4M12 20v-8M17 20V8M22 4v16"/></svg></div>
                    <h3 class="mc-features-grid__name">IP Warmup Management</h3>
                    <p class="mc-features-grid__desc">Linear or exponential warmup strategies with automatic daily quota calculation. Safely build IP reputation on new dedicated IPs without getting throttled.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="https://knowledge.acellemail.com/category/analytics-reporting" class="mc-features-grid__card" target="_blank">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg></div>
                    <h3 class="mc-features-grid__name">Real-Time Campaign Analytics</h3>
                    <p class="mc-features-grid__desc">Live dashboards tracking opens, clicks, bounces, complaints, and unsubscribes. Interactive charts with click-to-open ratio and per-link performance.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="https://knowledge.acellemail.com/category/analytics-reporting" class="mc-features-grid__card" target="_blank">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg></div>
                    <h3 class="mc-features-grid__name">Click Maps &amp; Geo Reports</h3>
                    <p class="mc-features-grid__desc">Visual heatmaps of where subscribers click in your emails. Geographic and device reports showing opens by country, browser, and email client.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="https://knowledge.acellemail.com/category/analytics-reporting" class="mc-features-grid__card" target="_blank">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><path d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg></div>
                    <h3 class="mc-features-grid__name">Spam Score &amp; Preview</h3>
                    <p class="mc-features-grid__desc">Check your emails against SpamAssassin filters before sending. Preview rendering across Gmail, Outlook, Apple Mail, and mobile clients.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
            </div>
        </div>

        <!-- Section: Integrations (6 cards — kept as-is) -->
        <div class="mc-features-grid__section">
            <h2 class="mc-features-grid__section-title">Integrations</h2>
            <div class="mc-features-grid__cards">
                <a href="{{ route('integrations') }}" class="mc-features-grid__card">
                    <div class="mc-features-grid__icon">
                        <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"/><line x1="7" y1="2" x2="7" y2="22"/><line x1="17" y1="2" x2="17" y2="22"/><line x1="2" y1="12" x2="22" y2="12"/><line x1="2" y1="7" x2="7" y2="7"/><line x1="2" y1="17" x2="7" y2="17"/><line x1="17" y1="7" x2="22" y2="7"/><line x1="17" y1="17" x2="22" y2="17"/></svg>
                    </div>
                    <h3 class="mc-features-grid__name">Amazon SES</h3>
                    <p class="mc-features-grid__desc">Send emails at $0.10 per 1,000 with Amazon Simple Email Service</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="{{ route('integrations') }}" class="mc-features-grid__card">
                    <div class="mc-features-grid__icon">
                        <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="mc-features-grid__name">SendGrid</h3>
                    <p class="mc-features-grid__desc">Connect SendGrid for reliable high-volume email delivery</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="{{ route('integrations') }}" class="mc-features-grid__card">
                    <div class="mc-features-grid__icon">
                        <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                    </div>
                    <h3 class="mc-features-grid__name">SparkPost</h3>
                    <p class="mc-features-grid__desc">Use SparkPost for enterprise-grade email infrastructure</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="{{ route('integrations') }}" class="mc-features-grid__card">
                    <div class="mc-features-grid__icon">
                        <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <h3 class="mc-features-grid__name">Elastic Email</h3>
                    <p class="mc-features-grid__desc">Affordable SMTP relay with powerful deliverability tools</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="{{ route('integrations') }}" class="mc-features-grid__card">
                    <div class="mc-features-grid__icon">
                        <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                    </div>
                    <h3 class="mc-features-grid__name">Mailgun</h3>
                    <p class="mc-features-grid__desc">Developer-friendly API for transactional and bulk email</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="{{ route('integrations') }}" class="mc-features-grid__card">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg></div>
                    <h3 class="mc-features-grid__name">Any SMTP Server</h3>
                    <p class="mc-features-grid__desc">Postfix, Exim, Sendmail, Qmail, or any standard SMTP. Full control over your mail infrastructure with per-server configuration.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="{{ route('integrations') }}" class="mc-features-grid__card">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg></div>
                    <h3 class="mc-features-grid__name">RESTful API &amp; Webhooks</h3>
                    <p class="mc-features-grid__desc">Full REST API for subscribers, campaigns, lists, and automations. Webhooks for real-time event notifications. Zapier integration included.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="{{ route('integrations') }}" class="mc-features-grid__card">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
                    <h3 class="mc-features-grid__name">Stripe, PayPal &amp; More</h3>
                    <p class="mc-features-grid__desc">Accept subscription payments via Stripe, PayPal, Braintree, Paddle, Razorpay, or Coinbase Commerce when running AcelleMail as a SaaS platform.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
            </div>
        </div>

    </div>
</section>

<!-- ======================================================================
     10. LOGOS: Integration service logos
     ====================================================================== -->
<section class="mc-features-integrations">
    <div class="mc-container">
        <div class="mc-features-integrations__header">
            <h2 class="mc-features-integrations__heading">Connect your favorite sending services and tools</h2>
        </div>
        <div class="mc-features-integrations__grid">
            <a href="{{ route('integrations') }}" class="mc-features-integrations__card">
                <img src="{{ asset('images/services/amazon-ses-logo.svg') }}" alt="Amazon SES" class="mc-features-integrations__img" loading="lazy">
                <h3 class="mc-features-integrations__name">Amazon SES</h3>
                <p class="mc-features-integrations__desc">Send 100,000 emails for just $10 with Amazon SES integration.</p>
            </a>
            <a href="{{ route('integrations') }}" class="mc-features-integrations__card">
                <img src="{{ asset('images/services/sendgrid-logo.svg') }}" alt="SendGrid" class="mc-features-integrations__img" loading="lazy">
                <h3 class="mc-features-integrations__name">SendGrid</h3>
                <p class="mc-features-integrations__desc">Reliable delivery with advanced analytics and deliverability tools.</p>
            </a>
            <a href="{{ route('integrations') }}" class="mc-features-integrations__card">
                <img src="{{ asset('images/services/sparkpost-logo.svg') }}" alt="SparkPost" class="mc-features-integrations__img" loading="lazy">
                <h3 class="mc-features-integrations__name">SparkPost</h3>
                <p class="mc-features-integrations__desc">Enterprise email infrastructure with predictive analytics.</p>
            </a>
            <a href="{{ route('integrations') }}" class="mc-features-integrations__card">
                <img src="{{ asset('images/services/stripe.svg') }}" alt="Stripe" class="mc-features-integrations__img" loading="lazy">
                <h3 class="mc-features-integrations__name">Stripe</h3>
                <p class="mc-features-integrations__desc">Accept payments and manage subscriptions with Stripe integration.</p>
            </a>
            <a href="{{ route('integrations') }}" class="mc-features-integrations__card">
                <img src="{{ asset('images/services/woocommerce-logo.svg') }}" alt="WooCommerce" class="mc-features-integrations__img" loading="lazy">
                <h3 class="mc-features-integrations__name">WooCommerce</h3>
                <p class="mc-features-integrations__desc">Sync your WooCommerce store customers and orders seamlessly.</p>
            </a>
            <a href="{{ route('integrations') }}" class="mc-features-integrations__card">
                <img src="{{ asset('images/services/wordpress-logo.svg') }}" alt="WordPress" class="mc-features-integrations__img" loading="lazy">
                <h3 class="mc-features-integrations__name">WordPress</h3>
                <p class="mc-features-integrations__desc">Embed signup forms and manage subscribers from your WordPress site.</p>
            </a>
        </div>
        <div class="mc-features-integrations__footer">
            <a href="{{ route('integrations') }}" class="mc-features-integrations__view-all">View all sending services &rarr;</a>
        </div>
    </div>
</section>

<!-- ======================================================================
     11. VIDEO: Connect SMTP & Sending Servers (reversed)
     ====================================================================== -->
<section class="mc-feature-alt mc-feature-alt--reverse" style="background: var(--mc-light-gray);">
    <div class="mc-container">
        <div class="mc-feature-alt__grid">
            <div class="mc-feature-alt__image">
                <div class="mc-feature-alt__embed">
                    <iframe src="https://www.youtube-nocookie.com/embed/RR6aoLYwx34" title="Connect SMTP and sending servers" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
            <div class="mc-feature-alt__content">
                <span class="mc-eyebrow">EASY SETUP</span>
                <h2 class="mc-feature-alt__heading">Connect any sending service in minutes</h2>
                <p class="mc-feature-alt__text">Plug in Amazon SES, SendGrid, Mailgun, or any SMTP server. AcelleMail handles bounce processing, feedback loops, and delivery tracking automatically.</p>
                <a href="{{ route('integrations') }}" class="mc-btn mc-btn--primary">View all integrations</a>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     12. FEATURE-ALT: Email Deliverability (kept as-is)
     ====================================================================== -->
<section class="mc-feature-alt" style="background: var(--mc-cream);">
    <div class="mc-container">
        <div class="mc-feature-alt__grid">
            <div class="mc-feature-alt__image">
                <svg viewBox="0 0 520 400" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:100%;height:auto;">
                  <rect width="520" height="400" rx="16" fill="var(--theme-bg-warm)"/>
                  <rect x="60" y="50" width="260" height="180" rx="12" fill="#fff" stroke="var(--theme-border)" stroke-width="1.5"/>
                  <rect x="60" y="50" width="260" height="44" rx="12" fill="var(--theme-text)"/>
                  <rect x="60" y="82" width="260" height="12" rx="0" fill="var(--theme-text)"/>
                  <circle cx="82" cy="72" r="6" fill="var(--theme-primary)"/>
                  <text x="96" y="76" font-family="IBM Plex Sans,sans-serif" font-size="13" font-weight="600" fill="#fff">Inbox</text>
                  <rect x="76" y="108" width="28" height="28" rx="6" fill="var(--theme-primary)" opacity="0.12"/>
                  <path d="M84 118l3 3 5-5" stroke="var(--theme-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <rect x="114" y="112" width="120" height="8" rx="4" fill="var(--theme-text)" opacity="0.7"/>
                  <rect x="114" y="126" width="80" height="6" rx="3" fill="var(--theme-text-tertiary)" opacity="0.5"/>
                  <circle cx="296" cy="122" r="4" fill="var(--theme-primary)"/>
                  <line x1="76" y1="148" x2="304" y2="148" stroke="var(--theme-border)" stroke-width="1"/>
                  <rect x="76" y="158" width="28" height="28" rx="6" fill="var(--theme-primary)" opacity="0.12"/>
                  <path d="M84 168l3 3 5-5" stroke="var(--theme-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <rect x="114" y="162" width="140" height="8" rx="4" fill="var(--theme-text)" opacity="0.7"/>
                  <rect x="114" y="176" width="96" height="6" rx="3" fill="var(--theme-text-tertiary)" opacity="0.5"/>
                  <circle cx="296" cy="172" r="4" fill="var(--theme-primary)"/>
                  <line x1="76" y1="198" x2="304" y2="198" stroke="var(--theme-border)" stroke-width="1"/>
                  <rect x="76" y="206" width="28" height="28" rx="6" fill="var(--theme-primary)" opacity="0.12"/>
                  <path d="M84 216l3 3 5-5" stroke="var(--theme-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <rect x="114" y="210" width="100" height="8" rx="4" fill="var(--theme-text)" opacity="0.7"/>
                  <rect x="114" y="224" width="70" height="6" rx="3" fill="var(--theme-text-tertiary)" opacity="0.5"/>
                  <circle cx="296" cy="220" r="4" fill="var(--theme-primary)"/>
                  <g transform="translate(340, 40)">
                    <rect width="140" height="160" rx="12" fill="#fff" stroke="var(--theme-border)" stroke-width="1.5"/>
                    <path d="M70 30 C70 30 42 42 42 68 C42 94 70 115 70 115 C70 115 98 94 98 68 C98 42 70 30 70 30Z" fill="var(--theme-primary)" opacity="0.1" stroke="var(--theme-primary)" stroke-width="1.5"/>
                    <path d="M58 68l8 8 16-16" stroke="var(--theme-primary)" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <text x="70" y="140" text-anchor="middle" font-family="IBM Plex Sans,sans-serif" font-size="11" font-weight="600" fill="var(--theme-text)">Authenticated</text>
                  </g>
                  <g transform="translate(60, 260)">
                    <rect width="80" height="32" rx="16" fill="var(--theme-primary)"/>
                    <text x="40" y="21" text-anchor="middle" font-family="IBM Plex Sans,sans-serif" font-size="12" font-weight="600" fill="#fff">SPF &#x2713;</text>
                  </g>
                  <g transform="translate(152, 260)">
                    <rect width="86" height="32" rx="16" fill="var(--theme-primary)"/>
                    <text x="43" y="21" text-anchor="middle" font-family="IBM Plex Sans,sans-serif" font-size="12" font-weight="600" fill="#fff">DKIM &#x2713;</text>
                  </g>
                  <g transform="translate(250, 260)">
                    <rect width="100" height="32" rx="16" fill="var(--theme-primary)"/>
                    <text x="50" y="21" text-anchor="middle" font-family="IBM Plex Sans,sans-serif" font-size="12" font-weight="600" fill="#fff">DMARC &#x2713;</text>
                  </g>
                  <g transform="translate(60, 310)">
                    <rect width="420" height="70" rx="12" fill="#fff" stroke="var(--theme-border)" stroke-width="1.5"/>
                    <text x="20" y="28" font-family="IBM Plex Sans,sans-serif" font-size="12" font-weight="600" fill="var(--theme-text)">Sender Reputation</text>
                    <text x="380" y="28" text-anchor="end" font-family="IBM Plex Sans,sans-serif" font-size="12" font-weight="700" fill="var(--theme-primary)">98.5%</text>
                    <rect x="20" y="42" width="380" height="10" rx="5" fill="var(--theme-bg-light)"/>
                    <rect x="20" y="42" width="360" height="10" rx="5" fill="var(--theme-primary)"/>
                  </g>
                  <g transform="translate(370, 220)">
                    <rect width="110" height="72" rx="10" fill="var(--theme-text)" opacity="0.9"/>
                    <path d="M16 20l39 24 39-24" stroke="var(--theme-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M16 20v36a4 4 0 004 4h70a4 4 0 004-4V20" stroke="var(--theme-primary)" stroke-width="1.5" stroke-linecap="round" fill="none"/>
                    <circle cx="90" cy="16" r="12" fill="var(--theme-primary)"/>
                    <path d="M85 16l3 3 5-5" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </g>
                </svg>
            </div>
            <div class="mc-feature-alt__content">
                <span class="mc-eyebrow">Deliverability</span>
                <h2 class="mc-feature-alt__heading">Reach inboxes, not spam folders</h2>
                <p class="mc-feature-alt__text">AcelleMail&rsquo;s built-in deliverability tools help you maintain a strong sender reputation and ensure your emails land in the inbox. With domain authentication, bounce handling, and feedback loop integration, your campaigns perform at their best.</p>
                <ul class="mc-feature-list">
                    <li class="mc-feature-list__item">SPF, DKIM, and DMARC authentication setup</li>
                    <li class="mc-feature-list__item">Automatic bounce and complaint handling</li>
                    <li class="mc-feature-list__item">Sender reputation monitoring per sending server</li>
                    <li class="mc-feature-list__item">Feedback loop integration with major ISPs</li>
                    <li class="mc-feature-list__item">Sending limit and throttling controls per server</li>
                </ul>
                <a href="{{ route('email-marketing') }}" class="mc-feature-alt__link">Learn about deliverability &rarr;</a>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     13. VIDEO: Install Self-Hosted
     ====================================================================== -->
<section class="mc-feature-alt">
    <div class="mc-container">
        <div class="mc-feature-alt__grid">
            <div class="mc-feature-alt__image">
                <div class="mc-feature-alt__embed">
                    <iframe src="https://www.youtube-nocookie.com/embed/uqMyS9tEZnw" title="Install self-hosted email marketing in 5 minutes" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
            <div class="mc-feature-alt__content">
                <span class="mc-eyebrow">SELF-HOSTED</span>
                <h2 class="mc-feature-alt__heading">Install on your server in 5 minutes</h2>
                <p class="mc-feature-alt__text">Full data ownership, no monthly fees, unlimited subscribers. AcelleMail runs on your own infrastructure &mdash; you control everything.</p>
                <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" class="mc-btn mc-btn--primary" target="_blank">Download AcelleMail</a>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     14. FEATURE-ALT: GDPR & Compliance (kept as-is)
     ====================================================================== -->
<section class="mc-feature-alt mc-feature-alt--reverse" style="background: var(--mc-light-gray);">
    <div class="mc-container">
        <div class="mc-feature-alt__grid">
            <div class="mc-feature-alt__image">
                <img src="{{ $themeImg('images/features/email-sms.svg') }}" alt="GDPR compliance tools" loading="lazy">
            </div>
            <div class="mc-feature-alt__content">
                <span class="mc-eyebrow">GDPR &amp; Compliance</span>
                <h2 class="mc-feature-alt__heading">Stay compliant with global privacy regulations</h2>
                <p class="mc-feature-alt__text">AcelleMail is built with privacy and compliance at its core. Manage consent, handle data requests, and ensure your email marketing meets GDPR, CAN-SPAM, and other regulatory requirements &mdash; all from a platform you fully control.</p>
                <ul class="mc-feature-list">
                    <li class="mc-feature-list__item">Consent management with double opt-in</li>
                    <li class="mc-feature-list__item">One-click unsubscribe in every email</li>
                    <li class="mc-feature-list__item">Data portability and export tools</li>
                    <li class="mc-feature-list__item">Right to deletion &mdash; remove subscriber data completely</li>
                </ul>
                <a href="{{ route('features') }}" class="mc-feature-alt__link">Learn about compliance &rarr;</a>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     15. CARDS: SaaS & Multi-Tenancy (6 cards)
     ====================================================================== -->
<section class="mc-features-grid">
    <div class="mc-container">

        <div class="mc-features-grid__section">
            <h2 class="mc-features-grid__section-title">SaaS Platform &amp; Multi-Tenancy</h2>
            <div class="mc-features-grid__cards">
                <a href="https://knowledge.acellemail.com/category/admin-guide" class="mc-features-grid__card" target="_blank">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg></div>
                    <h3 class="mc-features-grid__name">Multi-Tenant Architecture</h3>
                    <p class="mc-features-grid__desc">Run AcelleMail as a full SaaS platform. Each customer gets their own workspace with isolated lists, campaigns, automations, and sending servers.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="https://knowledge.acellemail.com/category/admin-guide" class="mc-features-grid__card" target="_blank">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><rect x="2" y="5" width="20" height="14" rx="2"/><line x1="2" y1="10" x2="22" y2="10"/></svg></div>
                    <h3 class="mc-features-grid__name">Multiple Payment Gateways</h3>
                    <p class="mc-features-grid__desc">Ships with Stripe, PayPal, Braintree, Paystack, Razorpay, Coinbase Commerce, and offline invoicing. Plugin architecture means you can add any gateway &mdash; no limits.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="https://knowledge.acellemail.com/category/admin-guide" class="mc-features-grid__card" target="_blank">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg></div>
                    <h3 class="mc-features-grid__name">Subscription Plans &amp; Quotas</h3>
                    <p class="mc-features-grid__desc">Create tiered plans with custom limits &mdash; subscriber caps, send quotas, sending server slots, automation rules, and list counts. Customers self-serve upgrades.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="https://knowledge.acellemail.com/category/admin-guide" class="mc-features-grid__card" target="_blank">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M16 8l-4 4-4-4M8 16l4-4 4 4"/></svg></div>
                    <h3 class="mc-features-grid__name">Recurring Billing</h3>
                    <p class="mc-features-grid__desc">Automatic monthly or yearly billing with Stripe and Braintree remote subscriptions. Payment retries, plan changes, and cancellation handled automatically.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="https://knowledge.acellemail.com/category/admin-guide" class="mc-features-grid__card" target="_blank">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
                    <h3 class="mc-features-grid__name">Admin Panel &amp; Customer Management</h3>
                    <p class="mc-features-grid__desc">Full admin dashboard to manage customers, subscriptions, payment history, sending servers, and system-wide settings. Impersonate any customer account for support.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
                <a href="https://knowledge.acellemail.com/category/admin-guide" class="mc-features-grid__card" target="_blank">
                    <div class="mc-features-grid__icon"><svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="var(--mc-black)" stroke-width="1.5"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8m-4-4v4"/></svg></div>
                    <h3 class="mc-features-grid__name">White-Label &amp; Custom Branding</h3>
                    <p class="mc-features-grid__desc">Custom themes (dark, light, 7+ colors), custom logo, custom domain. Your customers see your brand, not AcelleMail. Full white-label SaaS experience.</p>
                    <span class="mc-features-grid__link">Learn more &rarr;</span>
                </a>
            </div>
        </div>

    </div>
</section>

<!-- ======================================================================
     15b. VIDEO: Set Up Stripe Subscription Payment
     ====================================================================== -->
<section class="mc-feature-alt mc-feature-alt--reverse" style="background: var(--mc-cream);">
    <div class="mc-container">
        <div class="mc-feature-alt__grid">
            <div class="mc-feature-alt__image">
                <div class="mc-feature-alt__embed">
                    <iframe src="https://www.youtube-nocookie.com/embed/wiHeVHc2DAE" title="Set up Stripe subscription payment gateway in AcelleMail" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
            </div>
            <div class="mc-feature-alt__content">
                <span class="mc-eyebrow">RUN YOUR OWN SAAS</span>
                <h2 class="mc-feature-alt__heading">Accept payments globally &mdash; with the gateway your customers already use</h2>
                <p class="mc-feature-alt__text">AcelleMail ships with Stripe, PayPal, Braintree, Paystack, Razorpay, Coinbase Commerce, and more out of the box &mdash; with a plugin architecture that lets you add any payment provider. Direct charges, remote subscriptions, offline invoicing &mdash; mix and match to fit your market.</p>
                <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" class="mc-btn mc-btn--primary" target="_blank">Download AcelleMail</a>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     16. Stats Section
     ====================================================================== -->
<section class="mc-stats-section">
    <div class="mc-container">
        <div class="mc-stats-section__header">
            <h2 class="mc-stats-section__heading">Trusted by thousands of businesses worldwide</h2>
        </div>
        <div class="mc-stats-section__grid">
            <div class="mc-stats-section__item">
                <span class="mc-stats-section__number">50K+</span>
                <span class="mc-stats-section__label">Installations</span>
                <p class="mc-stats-section__desc">Businesses and agencies run AcelleMail on their own servers.</p>
            </div>
            <div class="mc-stats-section__item">
                <span class="mc-stats-section__number">10+</span>
                <span class="mc-stats-section__label">Sending Services</span>
                <p class="mc-stats-section__desc">Connect Amazon SES, SendGrid, SparkPost, Mailgun, and more.</p>
            </div>
            <div class="mc-stats-section__item">
                <span class="mc-stats-section__number">$0.10</span>
                <span class="mc-stats-section__label">Per 1,000 emails</span>
                <p class="mc-stats-section__desc">Send via Amazon SES at a fraction of SaaS platform costs.</p>
            </div>
            <div class="mc-stats-section__item">
                <span class="mc-stats-section__number">100%</span>
                <span class="mc-stats-section__label">Data Ownership</span>
                <p class="mc-stats-section__desc">Your data stays on your server. No third-party access.</p>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     16. Security & Self-Hosted (updated image)
     ====================================================================== -->
<section class="mc-section">
    <div class="mc-container">
        <div class="mc-feature-row">
            <div class="mc-feature-row__content">
                <span class="mc-eyebrow">Security &amp; Self-Hosted</span>
                <h2 class="mc-feature-row__title">Complete control over your email infrastructure</h2>
                <p class="mc-feature-row__desc">With AcelleMail installed on your own server, you have full control over your data, security, and email infrastructure. No vendor lock-in, no monthly subscriber fees, and no limits on how many emails you can send.</p>
                <ul class="mc-feature-list">
                    <li class="mc-feature-list__item">Self-hosted on your own server &mdash; full data ownership</li>
                    <li class="mc-feature-list__item">No per-subscriber pricing &mdash; unlimited contacts</li>
                    <li class="mc-feature-list__item">Multi-tenant support for agencies and resellers</li>
                    <li class="mc-feature-list__item">Role-based access controls and user management</li>
                </ul>
                <a href="{{ route('pricing') }}" class="mc-btn mc-btn--secondary">View pricing</a>
            </div>
            <div class="mc-feature-row__image">
                <svg viewBox="0 0 480 360" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:100%;height:auto;" aria-label="Self-hosted email marketing security">
                  <rect width="480" height="360" rx="16" fill="var(--theme-bg-warm)"/>
                  <!-- Server rack -->
                  <g transform="translate(60, 40)">
                    <rect width="160" height="200" rx="12" fill="#fff" stroke="var(--theme-border)" stroke-width="1.5"/>
                    <rect x="16" y="20" width="128" height="36" rx="6" fill="var(--theme-bg-light)" stroke="var(--theme-border)" stroke-width="1"/>
                    <circle cx="36" cy="38" r="4" fill="var(--theme-primary)"/>
                    <rect x="50" y="34" width="60" height="4" rx="2" fill="var(--theme-text)" opacity="0.2"/>
                    <rect x="50" y="42" width="40" height="3" rx="1.5" fill="var(--theme-text)" opacity="0.1"/>
                    <rect x="16" y="66" width="128" height="36" rx="6" fill="var(--theme-bg-light)" stroke="var(--theme-border)" stroke-width="1"/>
                    <circle cx="36" cy="84" r="4" fill="var(--theme-primary)" opacity="0.6"/>
                    <rect x="50" y="80" width="60" height="4" rx="2" fill="var(--theme-text)" opacity="0.2"/>
                    <rect x="50" y="88" width="40" height="3" rx="1.5" fill="var(--theme-text)" opacity="0.1"/>
                    <rect x="16" y="112" width="128" height="36" rx="6" fill="var(--theme-bg-light)" stroke="var(--theme-border)" stroke-width="1"/>
                    <circle cx="36" cy="130" r="4" fill="var(--theme-primary)" opacity="0.4"/>
                    <rect x="50" y="126" width="60" height="4" rx="2" fill="var(--theme-text)" opacity="0.2"/>
                    <rect x="50" y="134" width="40" height="3" rx="1.5" fill="var(--theme-text)" opacity="0.1"/>
                    <text x="80" y="175" text-anchor="middle" font-family="IBM Plex Sans,sans-serif" font-size="11" font-weight="600" fill="var(--theme-text)">Your Server</text>
                    <text x="80" y="192" text-anchor="middle" font-family="IBM Plex Sans,sans-serif" font-size="9" fill="var(--theme-text-secondary)">Full control</text>
                  </g>

                  <!-- Shield -->
                  <g transform="translate(280, 30)">
                    <rect width="140" height="180" rx="12" fill="#fff" stroke="var(--theme-border)" stroke-width="1.5"/>
                    <path d="M70 30 C70 30 38 44 38 74 C38 104 70 125 70 125 C70 125 102 104 102 74 C102 44 70 30 70 30Z" fill="var(--theme-primary)" opacity="0.08" stroke="var(--theme-primary)" stroke-width="1.5"/>
                    <path d="M56 74l10 10 18-20" stroke="var(--theme-primary)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    <text x="70" y="150" text-anchor="middle" font-family="IBM Plex Sans,sans-serif" font-size="11" font-weight="600" fill="var(--theme-text)">100% Private</text>
                    <text x="70" y="166" text-anchor="middle" font-family="IBM Plex Sans,sans-serif" font-size="9" fill="var(--theme-text-secondary)">Your data, your rules</text>
                  </g>

                  <!-- Connection lines -->
                  <line x1="220" y1="140" x2="280" y2="120" stroke="var(--theme-border)" stroke-width="1.5" stroke-dasharray="4 3"/>

                  <!-- Feature badges bottom -->
                  <g transform="translate(60, 260)">
                    <rect width="110" height="36" rx="18" fill="var(--theme-text)"/>
                    <text x="55" y="23" text-anchor="middle" font-family="IBM Plex Sans,sans-serif" font-size="11" font-weight="600" fill="#fff">No Vendor Lock</text>
                  </g>
                  <g transform="translate(185, 260)">
                    <rect width="110" height="36" rx="18" fill="var(--theme-primary)"/>
                    <text x="55" y="23" text-anchor="middle" font-family="IBM Plex Sans,sans-serif" font-size="11" font-weight="600" fill="#fff">Unlimited &#x221E;</text>
                  </g>
                  <g transform="translate(310, 260)">
                    <rect width="110" height="36" rx="18" fill="var(--theme-text)"/>
                    <text x="55" y="23" text-anchor="middle" font-family="IBM Plex Sans,sans-serif" font-size="11" font-weight="600" fill="#fff">Open Source</text>
                  </g>

                  <!-- Data flow dots -->
                  <circle cx="250" cy="100" r="3" fill="var(--theme-primary)" opacity="0.6"/>
                  <circle cx="260" cy="130" r="2" fill="var(--theme-primary)" opacity="0.4"/>
                  <circle cx="245" cy="160" r="2.5" fill="var(--theme-primary)" opacity="0.3"/>
                </svg>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================
     17. What's New (kept as-is)
     ====================================================================== -->
<section class="mc-section mc-section--cream">
    <div class="mc-container">
        <div class="mc-hero__content--center" style="margin-bottom: var(--space-2xl);">
            <h2>What&rsquo;s new in AcelleMail</h2>
            <p class="mc-text-lg" style="margin-top: var(--space-md);">We&rsquo;re always shipping new features to help you grow. Here are some of our latest releases.</p>
        </div>
        <div class="mc-grid mc-grid--3 mc-grid--gap-lg">
            <a href="https://knowledge.acellemail.com/category/acellemail-updates" class="mc-card mc-card--bordered" target="_blank">
                <div class="mc-card__body mc-card__body--lg">
                    <span class="mc-badge mc-badge--new" style="margin-bottom: var(--space-md);">NEW</span>
                    <h4 class="mc-card__title">Enhanced Automation Builder</h4>
                    <p class="mc-card__desc">Build sophisticated customer journeys with our visual automation builder featuring conditional splits, time delays, and multi-step workflows.</p>
                    <span class="mc-card__link">Learn more</span>
                </div>
            </a>
            <a href="{{ route('email-marketing') }}" class="mc-card mc-card--bordered">
                <div class="mc-card__body mc-card__body--lg">
                    <span class="mc-badge mc-badge--new" style="margin-bottom: var(--space-md);">NEW</span>
                    <h4 class="mc-card__title">Email Verification Service</h4>
                    <p class="mc-card__desc">Built-in email verification to clean your lists, reduce bounces, and protect your sender reputation before every campaign.</p>
                    <span class="mc-card__link">Learn more</span>
                </div>
            </a>
            <a href="https://knowledge.acellemail.com/category/acellemail-updates" class="mc-card mc-card--bordered" target="_blank">
                <div class="mc-card__body mc-card__body--lg">
                    <span class="mc-badge mc-badge--new" style="margin-bottom: var(--space-md);">NEW</span>
                    <h4 class="mc-card__title">Advanced Segmentation</h4>
                    <p class="mc-card__desc">Create hyper-targeted segments based on subscriber behavior, engagement, custom fields, and tags for maximum campaign relevance.</p>
                    <span class="mc-card__link">Learn more</span>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- ======================================================================
     18. Support (kept as-is)
     ====================================================================== -->
<section class="mc-features-support">
    <div class="mc-container">
        <div class="mc-features-support__header">
            <h2 class="mc-features-support__heading">Get help getting started</h2>
            <p class="mc-features-support__subheading">We offer documentation, community support, and professional services to help you get the most out of AcelleMail.</p>
        </div>
        <div class="mc-features-support__grid">
            <div class="mc-features-support__card">
                <div class="mc-features-support__image">
                    <img src="{{ asset('images/features/onboarding.png') }}" alt="Documentation" loading="lazy">
                </div>
                <h3 class="mc-features-support__name">Documentation</h3>
                <p class="mc-features-support__desc">Step-by-step guides covering installation, configuration, sending server setup, and campaign creation to get you started fast.</p>
                <a href="https://knowledge.acellemail.com" class="mc-btn mc-btn--outline" target="_blank">Read the docs</a>
            </div>
            <div class="mc-features-support__card">
                <div class="mc-features-support__image">
                    <img src="{{ asset('images/features/experts.png') }}" alt="Community" loading="lazy">
                </div>
                <h3 class="mc-features-support__name">Community &amp; Support</h3>
                <p class="mc-features-support__desc">Join our community forums and get help from experienced AcelleMail users, or contact our support team for priority assistance.</p>
                <a href="https://forum.acellemail.com" class="mc-btn mc-btn--outline" target="_blank">Join community</a>
            </div>
            <div class="mc-features-support__card">
                <div class="mc-features-support__image">
                    <img src="{{ asset('images/features/customer-success.png') }}" alt="Installation Service" loading="lazy">
                </div>
                <h3 class="mc-features-support__name">Installation Service</h3>
                <p class="mc-features-support__desc">Let our team install and configure AcelleMail on your server, set up sending services, and ensure everything runs perfectly.</p>
                <a href="{{ route('contact') }}" class="mc-btn mc-btn--outline">Talk with us</a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('jsonld')
@include('partials.seo.jsonld-breadcrumb', ['breadcrumbTitle' => 'Features'])
@endpush
