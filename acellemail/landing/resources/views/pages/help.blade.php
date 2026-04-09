@extends('layouts.app')

@section('title', 'Help Center — Documentation, Guides & Support | AcelleMail')
@section('meta_description', 'Get help with AcelleMail: installation guides, configuration docs, API reference, video tutorials, and community forum. Everything you need to get started.')
@section('og_title', 'Help & Documentation — AcelleMail')

@section('content')

<!-- ======================================================================
     HELP — HERO
     ====================================================================== -->
<section class="mc-hero mc-hero--cream">
  <div class="mc-container">
    <div class="mc-hero__grid">
      <div class="mc-hero__content">
        <h1 class="mc-hero__title">Help &amp; Documentation</h1>
        <p class="mc-hero__subtitle">
          Get the most out of AcelleMail with guides, tutorials, and community support.
          From installation to advanced automation, find everything you need &mdash; or ask the community on our <a href="https://forum.acellemail.com" style="color: var(--mc-teal); font-weight: 600;" target="_blank">forum</a>.
        </p>
        <div class="mc-help-search">
          <svg class="mc-help-search__icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" class="mc-input mc-input--lg mc-help-search__input" placeholder="Search help articles...">
        </div>
      </div>
      <div class="mc-hero__image">
        <img src="{{ asset('images/hero/help-hero.png') }}" alt="AcelleMail help center" fetchpriority="high">
      </div>
    </div>
  </div>
</section>

<!-- ======================================================================
     HELP — KNOWLEDGE BASE CTA
     ====================================================================== -->
<section class="mc-section mc-section--cream">
  <div class="mc-container">
    <div class="mc-feature-row">
      <div class="mc-feature-row__content">
        <span class="mc-eyebrow">Knowledge Base</span>
        <h2 class="mc-feature-row__title">Browse the Knowledge Base</h2>
        <p class="mc-feature-row__desc">
          Browse our comprehensive Knowledge Base with tutorials, guides, and references for email marketing.
          Find step-by-step articles on deliverability, automation, sending servers, and more &mdash; all in one place.
        </p>
        <a href="https://knowledge.acellemail.com" class="mc-btn mc-btn--primary" target="_blank">Open Knowledge Base</a>
      </div>
    </div>
  </div>
</section>

<!-- ======================================================================
     HELP — POPULAR GUIDES
     ====================================================================== -->
<section class="mc-section">
  <div class="mc-container">
    <h2 class="mc-text-center" style="margin-bottom: var(--space-2xl);">Popular Guides</h2>
    <div class="mc-grid mc-grid--3 mc-grid--gap-lg">

      <!-- Guide 1 -->
      <a href="https://knowledge.acellemail.com/category/installation-setup" class="mc-card mc-card--bordered mc-help-guide" target="_blank">
        <div class="mc-card__body mc-card__body--lg">
          <span class="mc-help-guide__number">1</span>
          <h4 class="mc-card__title">Getting Started: Install AcelleMail on Your Server</h4>
          <p class="mc-card__desc">Step-by-step installation guide. Requirements: PHP 8.x, MySQL 5.7+, Composer, and a web server (Apache/Nginx).</p>
          <span class="mc-card__link">Read guide</span>
        </div>
      </a>

      <!-- Guide 2 -->
      <a href="https://knowledge.acellemail.com/category/sending-deliverability" class="mc-card mc-card--bordered mc-help-guide" target="_blank">
        <div class="mc-card__body mc-card__body--lg">
          <span class="mc-help-guide__number">2</span>
          <h4 class="mc-card__title">Sending Configuration: Amazon SES, SendGrid &amp; SMTP</h4>
          <p class="mc-card__desc">Configure your sending service for reliable email delivery. Connect Amazon SES, SendGrid, Mailgun, or any SMTP server.</p>
          <span class="mc-card__link">Read guide</span>
        </div>
      </a>

      <!-- Guide 3 -->
      <a href="https://knowledge.acellemail.com/category/email-design" class="mc-card mc-card--bordered mc-help-guide" target="_blank">
        <div class="mc-card__body mc-card__body--lg">
          <span class="mc-help-guide__number">3</span>
          <h4 class="mc-card__title">Template Customization: Drag &amp; Drop Builder</h4>
          <p class="mc-card__desc">Design beautiful emails with the drag &amp; drop builder or import your own custom HTML templates.</p>
          <span class="mc-card__link">Read guide</span>
        </div>
      </a>

      <!-- Guide 4 -->
      <a href="https://knowledge.acellemail.com/category/automation" class="mc-card mc-card--bordered mc-help-guide" target="_blank">
        <div class="mc-card__body mc-card__body--lg">
          <span class="mc-help-guide__number">4</span>
          <h4 class="mc-card__title">Automation Setup: Triggers, Journeys &amp; Scheduling</h4>
          <p class="mc-card__desc">Create automated email journeys with triggers, delays, and conditions. Set up scheduled campaigns and drip sequences.</p>
          <span class="mc-card__link">Read guide</span>
        </div>
      </a>

      <!-- Guide 5 -->
      <a href="https://knowledge.acellemail.com/category/server-management" class="mc-card mc-card--bordered mc-help-guide" target="_blank">
        <div class="mc-card__body mc-card__body--lg">
          <span class="mc-help-guide__number">5</span>
          <h4 class="mc-card__title">Server Optimization: Queue Workers &amp; Cron Jobs</h4>
          <p class="mc-card__desc">Optimize sending performance with queue workers, cron job configuration, and server tuning for high-volume campaigns.</p>
          <span class="mc-card__link">Read guide</span>
        </div>
      </a>

      <!-- Guide 6 -->
      <a href="https://knowledge.acellemail.com/category/saas-multi-tenant" class="mc-card mc-card--bordered mc-help-guide" target="_blank">
        <div class="mc-card__body mc-card__body--lg">
          <span class="mc-help-guide__number">6</span>
          <h4 class="mc-card__title">SaaS Setup: Multi-Tenant Billing &amp; User Management</h4>
          <p class="mc-card__desc">Extended license feature: run AcelleMail as a SaaS with multi-tenant support, billing integration, and user management.</p>
          <span class="mc-card__link">Read guide</span>
        </div>
      </a>

    </div>
  </div>
</section>

<!-- ======================================================================
     HELP — CONTACT SUPPORT
     ====================================================================== -->
<section class="mc-section mc-section--cream">
  <div class="mc-container">
    <div class="mc-feature-row">
      <div class="mc-feature-row__image">
        <img src="{{ asset('images/help/contact-support.png') }}" alt="Contact our support team" loading="lazy">
      </div>
      <div class="mc-feature-row__content">
        <h2 class="mc-feature-row__title">Need professional help?</h2>
        <p class="mc-feature-row__desc">
          Our team offers installation, migration, and custom development services.
          Whether you need help setting up AcelleMail or want custom features built, we&rsquo;re here to help.
        </p>
        <div class="mc-hero__actions">
          <a href="{{ route('pricing') }}" class="mc-btn mc-btn--primary">Get AcelleMail</a>
          <a href="https://demo.acellemail.com" class="mc-btn mc-btn--secondary" target="_blank">Try the Demo</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ======================================================================
     HELP — TOPICS GRID
     ====================================================================== -->
<section class="mc-section">
  <div class="mc-container">
    <h2 class="mc-text-center" style="margin-bottom: var(--space-2xl);">Help by Topic</h2>
    <div class="mc-grid mc-grid--3 mc-grid--gap-md mc-help-topics">

      <a href="https://knowledge.acellemail.com/category/installation-setup" class="mc-card mc-card--bordered mc-help-topic" target="_blank">
        <div class="mc-card__body">
          <h5 class="mc-help-topic__title">Installation</h5>
          <p class="mc-help-topic__desc">Server requirements, installation steps, and environment configuration.</p>
        </div>
      </a>

      <a href="https://knowledge.acellemail.com/category/list-management" class="mc-card mc-card--bordered mc-help-topic" target="_blank">
        <div class="mc-card__body">
          <h5 class="mc-help-topic__title">Subscriber Lists</h5>
          <p class="mc-help-topic__desc">Import, organize, segment, and manage your subscriber lists.</p>
        </div>
      </a>

      <a href="https://knowledge.acellemail.com/category/automation" class="mc-card mc-card--bordered mc-help-topic" target="_blank">
        <div class="mc-card__body">
          <h5 class="mc-help-topic__title">Automation</h5>
          <p class="mc-help-topic__desc">Create automated email journeys with triggers and conditions.</p>
        </div>
      </a>

      <a href="https://knowledge.acellemail.com/category/security-compliance" class="mc-card mc-card--bordered mc-help-topic" target="_blank">
        <div class="mc-card__body">
          <h5 class="mc-help-topic__title">Security &amp; Privacy</h5>
          <p class="mc-help-topic__desc">GDPR tools, SPF/DKIM setup, access controls, and data protection.</p>
        </div>
      </a>

      <a href="https://knowledge.acellemail.com/category/email-design" class="mc-card mc-card--bordered mc-help-topic" target="_blank">
        <div class="mc-card__body">
          <h5 class="mc-help-topic__title">Template Builder</h5>
          <p class="mc-help-topic__desc">Drag &amp; drop editor, custom HTML templates, and template management.</p>
        </div>
      </a>

      <a href="https://knowledge.acellemail.com/category/sending-deliverability" class="mc-card mc-card--bordered mc-help-topic" target="_blank">
        <div class="mc-card__body">
          <h5 class="mc-help-topic__title">Email Delivery</h5>
          <p class="mc-help-topic__desc">Sending server setup, bounce handling, and deliverability optimization.</p>
        </div>
      </a>

      <a href="https://knowledge.acellemail.com/category/email-marketing" class="mc-card mc-card--bordered mc-help-topic" target="_blank">
        <div class="mc-card__body">
          <h5 class="mc-help-topic__title">Campaigns</h5>
          <p class="mc-help-topic__desc">Create, schedule, and send email campaigns to your subscribers.</p>
        </div>
      </a>

      <a href="https://knowledge.acellemail.com/category/sending-deliverability" class="mc-card mc-card--bordered mc-help-topic" target="_blank">
        <div class="mc-card__body">
          <h5 class="mc-help-topic__title">Sending Servers</h5>
          <p class="mc-help-topic__desc">Configure Amazon SES, SendGrid, Mailgun, SparkPost, or SMTP.</p>
        </div>
      </a>

      <a href="https://knowledge.acellemail.com/category/server-management" class="mc-card mc-card--bordered mc-help-topic" target="_blank">
        <div class="mc-card__body">
          <h5 class="mc-help-topic__title">Queue &amp; Cron Jobs</h5>
          <p class="mc-help-topic__desc">Set up queue workers and cron jobs for reliable email processing.</p>
        </div>
      </a>

      <a href="https://knowledge.acellemail.com/category/integrations" class="mc-card mc-card--bordered mc-help-topic" target="_blank">
        <div class="mc-card__body">
          <h5 class="mc-help-topic__title">Integrations</h5>
          <p class="mc-help-topic__desc">Connect AcelleMail with WordPress, WooCommerce, and third-party services.</p>
        </div>
      </a>

      <a href="https://knowledge.acellemail.com" class="mc-card mc-card--bordered mc-help-topic" target="_blank">
        <div class="mc-card__body">
          <h5 class="mc-help-topic__title">Signup Forms</h5>
          <p class="mc-help-topic__desc">Embedded forms, popup forms, and landing pages to grow your list.</p>
        </div>
      </a>

      <a href="https://knowledge.acellemail.com/category/saas-multi-tenant" class="mc-card mc-card--bordered mc-help-topic" target="_blank">
        <div class="mc-card__body">
          <h5 class="mc-help-topic__title">SaaS &amp; Multi-Tenant</h5>
          <p class="mc-help-topic__desc">Run AcelleMail as a service with billing, plans, and customer management.</p>
        </div>
      </a>

      <a href="https://knowledge.acellemail.com" class="mc-card mc-card--bordered mc-help-topic" target="_blank">
        <div class="mc-card__body">
          <h5 class="mc-help-topic__title">Personalization</h5>
          <p class="mc-help-topic__desc">Use merge tags, dynamic content, and conditional blocks in your emails.</p>
        </div>
      </a>

      <a href="https://knowledge.acellemail.com/articles/rest-api-authentication-and-endpoints" class="mc-card mc-card--bordered mc-help-topic" target="_blank">
        <div class="mc-card__body">
          <h5 class="mc-help-topic__title">API &amp; Webhooks</h5>
          <p class="mc-help-topic__desc">REST API documentation, webhook events, and developer integration guides.</p>
        </div>
      </a>

      <a href="https://knowledge.acellemail.com/category/analytics-reporting" class="mc-card mc-card--bordered mc-help-topic" target="_blank">
        <div class="mc-card__body">
          <h5 class="mc-help-topic__title">Reports &amp; Analytics</h5>
          <p class="mc-help-topic__desc">Track opens, clicks, bounces, and campaign performance metrics.</p>
        </div>
      </a>

      <a href="https://knowledge.acellemail.com/category/email-design" class="mc-card mc-card--bordered mc-help-topic" target="_blank">
        <div class="mc-card__body">
          <h5 class="mc-help-topic__title">Templates</h5>
          <p class="mc-help-topic__desc">Use, customize, and create email templates for your campaigns.</p>
        </div>
      </a>

      <a href="https://knowledge.acellemail.com/category/troubleshooting" class="mc-card mc-card--bordered mc-help-topic" target="_blank">
        <div class="mc-card__body">
          <h5 class="mc-help-topic__title">Troubleshooting</h5>
          <p class="mc-help-topic__desc">Common issues, error messages, and solutions for AcelleMail.</p>
        </div>
      </a>

      <a href="https://knowledge.acellemail.com/category/acellemail-updates" class="mc-card mc-card--bordered mc-help-topic" target="_blank">
        <div class="mc-card__body">
          <h5 class="mc-help-topic__title">Updates &amp; Migration</h5>
          <p class="mc-help-topic__desc">Upgrade to the latest version and migrate from other email platforms.</p>
        </div>
      </a>

    </div>
  </div>
</section>

<!-- ======================================================================
     HELP — EXPERT HELP
     ====================================================================== -->
<section class="mc-section mc-section--cream">
  <div class="mc-container">
    <div class="mc-feature-row mc-feature-row--reverse">
      <div class="mc-feature-row__image">
        <img src="{{ asset('images/help/expert-help.png') }}" alt="Get help from an expert" loading="lazy">
      </div>
      <div class="mc-feature-row__content">
        <h2 class="mc-feature-row__title">Get help from an expert</h2>
        <p class="mc-feature-row__desc">
          Need professional help? Our team offers installation, server migration, custom development,
          and performance optimization services. Or join our community forum to get answers from fellow users.
        </p>
        <div style="display: flex; gap: var(--space-sm); flex-wrap: wrap;">
          <a href="{{ route('contact') }}" class="mc-btn mc-btn--primary">Contact our team</a>
          <a href="https://forum.acellemail.com" class="mc-btn mc-btn--secondary" target="_blank">Community Forum</a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ======================================================================
     HELP — STILL HAVE QUESTIONS
     ====================================================================== -->
<section class="mc-section">
  <div class="mc-container">
    <div class="mc-hero__content--center">
      <h2>Still have questions?</h2>
      <p class="mc-text-lg" style="margin-top: var(--space-md);">
        Ask the community on our <a href="https://forum.acellemail.com" style="color: var(--mc-teal);" target="_blank">forum</a>,
        visit <a href="https://knowledge.acellemail.com" style="color: var(--mc-teal);" target="_blank">the knowledge base</a> for full documentation,
        or try the <a href="https://demo.acellemail.com" style="color: var(--mc-teal);">live demo</a> to explore features before purchasing.
      </p>
      <div style="display: flex; gap: var(--space-md); justify-content: center; margin-top: var(--space-lg); flex-wrap: wrap;">
        <a href="https://forum.acellemail.com" class="mc-btn mc-btn--primary" target="_blank">Visit the Forum</a>
        <a href="{{ route('contact') }}" class="mc-btn mc-btn--secondary">Contact Us</a>
      </div>
    </div>
  </div>
</section>

@endsection

@push('jsonld')
@include('partials.seo.jsonld-breadcrumb', ['breadcrumbTitle' => 'Help Center'])
@endpush
