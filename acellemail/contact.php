<?php $current_page = 'contact'; ?>
<?php include '_header.php'; ?>

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
      <a href="https://acellemail.com" class="mc-card mc-card--bordered mc-contact-quick__card" target="_blank">
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
     CONTACT — FORM
     ====================================================================== -->
<section class="mc-section mc-section--light">
  <div class="mc-container">
    <div class="mc-contact-form">
      <h3 class="mc-text-center" style="margin-bottom: var(--space-xl);">Send us a message</h3>

      <form action="#" method="post" class="mc-contact-form__inner">
        <!-- Topic -->
        <div class="mc-form-group">
          <label for="contact-topic">Topic</label>
          <select id="contact-topic" class="mc-input mc-contact-form__select">
            <option value="" disabled selected>Select a topic</option>
            <option value="technical">Technical Support</option>
            <option value="license">License Questions</option>
            <option value="installation">Installation Help</option>
            <option value="custom">Custom Development</option>
            <option value="bug">Bug Report</option>
          </select>
        </div>

        <!-- First / Last Name -->
        <div class="mc-contact-form__row">
          <div class="mc-form-group">
            <label for="contact-first">First name</label>
            <input type="text" id="contact-first" class="mc-input" placeholder="Jane">
          </div>
          <div class="mc-form-group">
            <label for="contact-last">Last name</label>
            <input type="text" id="contact-last" class="mc-input" placeholder="Doe">
          </div>
        </div>

        <!-- Email -->
        <div class="mc-form-group">
          <label for="contact-email">Email</label>
          <input type="email" id="contact-email" class="mc-input" placeholder="jane@example.com">
        </div>

        <!-- Subject -->
        <div class="mc-form-group">
          <label for="contact-subject">Subject</label>
          <input type="text" id="contact-subject" class="mc-input" placeholder="How can we help?">
        </div>

        <!-- Message -->
        <div class="mc-form-group">
          <label for="contact-message">Message</label>
          <textarea id="contact-message" class="mc-input mc-contact-form__textarea" rows="6" placeholder="Tell us more about your question or issue..."></textarea>
        </div>

        <!-- Submit -->
        <div class="mc-text-center">
          <button type="submit" class="mc-btn mc-btn--primary mc-btn--lg">Send</button>
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
          <a href="pricing.php" class="mc-link mc-link--arrow">View support packages</a>
        </p>
      </div>
      <div class="mc-feature-row__image">
        <img src="images/about/office.png" alt="AcelleMail team workspace">
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
        <a href="help.php" class="mc-card mc-card--bordered mc-contact-intuit__card">
          <div class="mc-card__body mc-card__body--lg mc-text-center">
            <h4 class="mc-card__title">Documentation</h4>
            <span class="mc-card__link">Browse guides</span>
          </div>
        </a>
      </div>
    </div>
  </div>
</section>

<?php include '_footer.php'; ?>
