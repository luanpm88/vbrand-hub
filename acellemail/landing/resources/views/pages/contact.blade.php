@extends('layouts.app')

@section('title', 'Contact AcelleMail — Support, Sales & Partnership Inquiries')
@section('meta_description', 'Get in touch with AcelleMail for pre-sales questions, technical support, partnership inquiries, or custom solutions. We typically respond within 24 hours.')
@section('og_title', 'Contact Us — AcelleMail')

@section('content')

<!-- ======================================================================
     CONTACT — HERO
     ====================================================================== -->
<section class="mc-section">
  <div class="mc-container">
    <div class="mc-hero__content--center">
      <h1>Contact AcelleMail</h1>
      <p class="mc-text-lg" style="margin-top: var(--space-md);">
        AcelleMail is a self-hosted email marketing platform built on Laravel. We help
        thousands of businesses around the world take control of their email marketing with
        full source code, professional support, and continuous updates.
      </p>
    </div>
  </div>
</section>

<!-- ======================================================================
     CONTACT — QUICK ACCESS LINKS
     ====================================================================== -->
<section class="mc-section--sm">
  <div class="mc-container">
    <div class="mc-contact-quick">
      <a href="{{ route('api') }}" class="mc-card mc-card--bordered mc-contact-quick__card">
        <div class="mc-card__body">
          <p class="mc-contact-quick__title">Looking for API documentation?</p>
          <span class="mc-card__link">View API docs</span>
        </div>
      </a>
      <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082/comments" class="mc-card mc-card--bordered mc-contact-quick__card" target="_blank">
        <div class="mc-card__body">
          <p class="mc-contact-quick__title">Have a feature request?</p>
          <span class="mc-card__link">Post on CodeCanyon</span>
        </div>
      </a>
    </div>
  </div>
</section>

<!-- ======================================================================
     CONTACT — FORM (real POST handler, DB-backed; replaces the legacy
     mailto: form that lost every submission whose visitor didn't have
     a working desktop mail client. See ContactController.)
     ====================================================================== -->
<section class="mc-section mc-section--light" id="contact-form">
  <div class="mc-container">
    <div class="mc-contact-form">
      <h3 class="mc-text-center" style="margin-bottom: var(--space-xl);">Send us a message</h3>

      {{-- Success is owned by /contact/thanks now — see ContactController::thanks.
           Keeping the @if(session('contact_success')) hook below for E2E
           back-compat: any future test that posts directly will still get
           a visible signal even before the redirect lands on /thanks. --}}
      @if(session('contact_success'))
        <div class="mc-form-success" role="status" data-testid="contact-success">
          <strong>Thanks — we got your message.</strong>
        </div>
      @endif

      @if($errors->any())
        <div class="mc-form-error-summary" role="alert" data-testid="contact-error">
          <strong>Please fix the issues below:</strong>
          <ul style="margin:var(--space-xs) 0 0 var(--space-lg);">
            @foreach($errors->all() as $msg)
              <li>{{ $msg }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('contact.store') }}" method="POST" class="mc-contact-form__inner" data-testid="contact-form">
        @csrf

        {{-- Honeypot: bots fill every visible input. Hidden via off-screen
             positioning + tabindex=-1 + autocomplete=off — accessible
             screen-readers also skip it via aria-hidden. --}}
        <div aria-hidden="true" style="position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden;">
          <label for="contact-website">Website (leave empty)</label>
          <input type="text" id="contact-website" name="website" value="" tabindex="-1" autocomplete="off">
        </div>

        <!-- Topic -->
        <div class="mc-form-group">
          <label for="contact-topic">Topic</label>
          <select id="contact-topic" name="topic" class="mc-input mc-contact-form__select @error('topic') mc-input--error @enderror" required>
            <option value="" disabled @if(!old('topic')) selected @endif>Select a topic</option>
            @foreach(\App\Models\ContactSubmission::TOPICS as $key => $label)
              <option value="{{ $key }}" @selected(old('topic') === $key)>{{ $label }}</option>
            @endforeach
          </select>
        </div>

        <!-- First / Last Name -->
        <div class="mc-contact-form__row">
          <div class="mc-form-group">
            <label for="contact-first">First name</label>
            <input type="text" id="contact-first" name="first_name" value="{{ old('first_name') }}" class="mc-input @error('first_name') mc-input--error @enderror" placeholder="Jane" required maxlength="80" autocomplete="given-name">
          </div>
          <div class="mc-form-group">
            <label for="contact-last">Last name</label>
            <input type="text" id="contact-last" name="last_name" value="{{ old('last_name') }}" class="mc-input @error('last_name') mc-input--error @enderror" placeholder="Doe" required maxlength="80" autocomplete="family-name">
          </div>
        </div>

        <!-- Email -->
        <div class="mc-form-group">
          <label for="contact-email">Email</label>
          <input type="email" id="contact-email" name="email" value="{{ old('email') }}" class="mc-input @error('email') mc-input--error @enderror" placeholder="jane@example.com" required maxlength="255" autocomplete="email">
        </div>

        <!-- Subject -->
        <div class="mc-form-group">
          <label for="contact-subject">Subject</label>
          <input type="text" id="contact-subject" name="subject" value="{{ old('subject') }}" class="mc-input @error('subject') mc-input--error @enderror" placeholder="How can we help?" required maxlength="200">
        </div>

        <!-- Message -->
        <div class="mc-form-group">
          <label for="contact-message">Message</label>
          <textarea id="contact-message" name="message" class="mc-input mc-contact-form__textarea @error('message') mc-input--error @enderror" rows="6" placeholder="Tell us more about your question or issue..." required minlength="10" maxlength="5000">{{ old('message') }}</textarea>
          <p class="mc-form-hint">Your details are private — we use them only to reply to your message.</p>
        </div>

        <!-- Submit -->
        <div class="mc-text-center">
          <button type="submit" class="mc-btn mc-btn--primary mc-btn--lg" data-testid="contact-submit">Send message</button>
        </div>
      </form>
    </div>
  </div>
</section>

<!-- ======================================================================
     CONTACT — COMPANY INFO
     ====================================================================== -->
<section class="mc-section">
  <div class="mc-container">
    <div class="mc-feature-row">
      <div class="mc-feature-row__content">
        <h2 class="mc-feature-row__title">Get in touch with us:</h2>
        <p class="mc-feature-row__desc">
          AcelleMail<br>
          Email: support@acellemail.com<br>
          CodeCanyon: Item #17796082<br>
          Response time: 24–48 hours
        </p>
        <p>
          Looking for priority support or custom development?
          <a href="{{ route('pricing') }}" class="mc-link mc-link--arrow">View support packages</a>
        </p>
      </div>
      <div class="mc-feature-row__image">
        <img src="{{ asset('images/about/office.png') }}" alt="AcelleMail team workspace" fetchpriority="high" width="640" height="369" decoding="async">
      </div>
    </div>
  </div>
</section>

<!-- ======================================================================
     CONTACT — SUPPORT OPTIONS
     ====================================================================== -->
<section class="mc-section mc-section--cream">
  <div class="mc-container">
    <div class="mc-contact-intuit">
      <h2 class="mc-text-center" style="margin-top: var(--space-xl);">Multiple ways to get help</h2>
      <p class="mc-text-center mc-text-lg" style="max-width: 640px; margin: var(--space-md) auto var(--space-xl);">
        Whether you need quick answers, hands-on support, or custom development — we have
        the right option for your needs.
      </p>
      <div class="mc-contact-intuit__cards">
        <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082/comments" class="mc-card mc-card--bordered mc-contact-intuit__card" target="_blank">
          <div class="mc-card__body mc-card__body--lg mc-text-center">
            <h4 class="mc-card__title">CodeCanyon Forum</h4>
            <span class="mc-card__link">Ask a question</span>
          </div>
        </a>
        <a href="/kb" class="mc-card mc-card--bordered mc-contact-intuit__card" target="_blank">
          <div class="mc-card__body mc-card__body--lg mc-text-center">
            <h4 class="mc-card__title">Documentation</h4>
            <span class="mc-card__link">Browse guides</span>
          </div>
        </a>
      </div>
    </div>
  </div>
</section>

@endsection

@push('jsonld')
@include('partials.seo.jsonld-breadcrumb', ['breadcrumbTitle' => 'Contact'])
@include('partials.seo.jsonld-organization')
@endpush
