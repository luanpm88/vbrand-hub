@extends('layouts.app')

@section('title', 'About | AcelleMail')

@section('content')

<!-- ======================================================================
     ABOUT — HERO
     ====================================================================== -->
<section class="mc-about-hero">
  <img src="{{ asset('images/hero/about-hero.jpg') }}" alt="AcelleMail headquarters" class="mc-about-hero__image">
  <div class="mc-about-hero__overlay"></div>
  <div class="mc-about-hero__content">
    <div class="mc-container">
      <h1 class="mc-about-hero__heading">About AcelleMail</h1>
    </div>
  </div>
</section>

<!-- ======================================================================
     ABOUT — INTRO
     ====================================================================== -->
<section class="mc-section">
  <div class="mc-container mc-container--narrow">
    <p class="mc-text-lg">
      AcelleMail is the self-hosted email marketing platform built for developers and businesses.
      We empower thousands of customers around the world to take full control of their email marketing
      with a powerful, open-source web application built on PHP and Laravel. AcelleMail puts simplicity
      and flexibility at the heart of your campaigns, so you can send emails, automate workflows, manage
      subscribers, and track results&mdash;all on your own server, with complete data ownership and
      seamless integration with any SMTP service like Amazon SES, SendGrid, or SparkPost.
    </p>
  </div>
</section>

<!-- ======================================================================
     ABOUT — FOUNDER STORY
     ====================================================================== -->
<section class="mc-section mc-section--light">
  <div class="mc-container mc-container--narrow">
    <h2>Our story</h2>
    <div class="mc-about-founder__body">
      <p>
        In 2016, AcelleMail began as a Laravel side project built by developers who were frustrated
        with expensive, cloud-only email marketing platforms. Small businesses and agencies needed a
        powerful tool they could host themselves&mdash;without monthly fees that scaled with every
        new subscriber. There had to be a better way.
      </p>
      <p>
        What started as a simple self-hosted mailer quickly grew into a full-featured email marketing
        application. Released on CodeCanyon, AcelleMail resonated with developers and business owners
        who wanted complete control over their data and infrastructure. The approach was simple: build
        for developers first, keep the code clean and extensible, and listen to the community.
      </p>
      <p>
        Over the years, AcelleMail has grown from a humble open-source project into a trusted platform
        with over 50,000 downloads and a 4.6-star rating on Envato. Now at version 4.1.5 LTS, it has
        become the go-to self-hosted alternative to costly SaaS platforms&mdash;proving that powerful
        email marketing doesn&rsquo;t have to come with a recurring price tag.
      </p>
    </div>
  </div>
</section>

<!-- ======================================================================
     ABOUT — OUR PHILOSOPHY
     ====================================================================== -->
<section class="mc-section">
  <div class="mc-container mc-container--narrow">
    <h2>Our Philosophy</h2>
    <div class="mc-about-culture__body">
      <p>
        Our approach is open source, developer-first, and community-driven. We believe the best software
        is built transparently, so we ship full source code with every release&mdash;no encoded files, no
        hidden dependencies. Whether you&rsquo;re customizing the platform for a client or extending it
        with your own plugins, you have complete freedom to make it yours. And by the
        way&mdash;<a href="https://forum.acellemail.com" class="mc-link" target="_blank">we&rsquo;d love your feedback</a>.
      </p>
      <p>
        Community feedback is central to how we build and improve. Every feature request, bug report,
        and suggestion shared through our CodeCanyon comments and support channels shapes the roadmap.
        We&rsquo;re committed to continuous improvement&mdash;shipping regular updates, maintaining
        backward compatibility, and ensuring that every release makes the platform more reliable,
        more flexible, and easier to use for everyone.
      </p>
    </div>
  </div>
</section>

<!-- ======================================================================
     ABOUT — OUR COMMITMENT
     ====================================================================== -->
<section class="mc-section mc-section--cream">
  <div class="mc-container mc-container--narrow">
    <h2>Our Commitment</h2>
    <p>
      Through our commitment to transparency and trust, AcelleMail provides free lifetime updates,
      full backward compatibility, and GDPR compliance from day one. We believe in transparent
      pricing&mdash;one purchase, no recurring fees, no hidden costs&mdash;so you can focus on
      growing your business instead of managing subscriptions.
    </p>
    <div class="mc-about-stat">
      <span class="mc-stat__number mc-stat__number--teal">50,000+</span>
      <span class="mc-about-stat__label">downloads by developers and businesses worldwide</span>
    </div>
  </div>
</section>

<!-- ======================================================================
     ABOUT — LEARN MORE CARDS
     ====================================================================== -->
<section class="mc-section">
  <div class="mc-container">
    <h2 class="mc-text-center" style="margin-bottom: var(--space-2xl);">Learn more about AcelleMail</h2>
    <div class="mc-grid mc-grid--3 mc-grid--gap-lg">
      <!-- Card 1: Documentation -->
      <a href="https://knowledge.acellemail.com" class="mc-card mc-card--bordered" target="_blank">
        <div class="mc-card__image mc-card__image--fixed">
          <img src="{{ asset('images/about/newsroom.jpg') }}" alt="AcelleMail Documentation">
        </div>
        <div class="mc-card__body">
          <h4 class="mc-card__title">AcelleMail Documentation</h4>
          <p class="mc-card__desc">Explore guides, tutorials, and API references for AcelleMail.</p>
          <span class="mc-card__link">Read more</span>
        </div>
      </a>

      <!-- Card 2: Changelog -->
      <a href="https://knowledge.acellemail.com/category/acellemail-updates" class="mc-card mc-card--bordered" target="_blank">
        <div class="mc-card__image mc-card__image--fixed">
          <img src="{{ asset('images/about/why-acellemail.jpg') }}" alt="See what's new in every release">
        </div>
        <div class="mc-card__body">
          <h4 class="mc-card__title">Changelog &amp; What&rsquo;s New</h4>
          <p class="mc-card__desc">Every release brings new features and improvements. See the full history of updates, enhancements, and fixes in AcelleMail.</p>
          <span class="mc-card__link">Learn more</span>
        </div>
      </a>

      <!-- Card 3: Community -->
      <a href="https://forum.acellemail.com" class="mc-card mc-card--bordered" target="_blank">
        <div class="mc-card__image mc-card__image--fixed">
          <img src="{{ asset('images/about/whats-new.png') }}" alt="AcelleMail Community">
        </div>
        <div class="mc-card__body">
          <h4 class="mc-card__title">AcelleMail Community</h4>
          <p class="mc-card__desc">Join thousands of developers: share tips, request features, report bugs, and help shape the roadmap.</p>
          <span class="mc-card__link">Join the community</span>
        </div>
      </a>
    </div>
  </div>
</section>

@endsection
