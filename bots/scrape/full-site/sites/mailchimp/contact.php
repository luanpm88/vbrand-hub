<?php $current_page = 'contact'; ?>
<?php include '_header.php'; ?>

<!-- ======================================================================
     CONTACT — HERO
     ====================================================================== -->
<section class="mc-section">
  <div class="mc-container">
    <div class="mc-hero__content--center">
      <h1>Contact Mailchimp</h1>
      <p class="mc-text-lg" style="margin-top: var(--space-md);">
        Mailchimp is an all-in-one marketing platform for growing businesses. Mailchimp empowers
        millions of customers around the world to start and grow their businesses with world-class
        marketing technology, award-winning customer support, and inspiring content.
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
      <a href="integrations.php" class="mc-card mc-card--bordered mc-contact-quick__card">
        <div class="mc-card__body">
          <p class="mc-contact-quick__title">Looking for API help?</p>
          <span class="mc-card__link">Find it here</span>
        </div>
      </a>
      <a href="help.php" class="mc-card mc-card--bordered mc-contact-quick__card">
        <div class="mc-card__body">
          <p class="mc-contact-quick__title">Have abuse to report?</p>
          <span class="mc-card__link">Tell us about it here</span>
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
            <option value="access">Account Access</option>
            <option value="billing">Billing</option>
            <option value="transactional">Transactional Emails</option>
            <option value="sales">Contact Sales</option>
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
     CONTACT — COMPANY ADDRESS
     ====================================================================== -->
<section class="mc-section">
  <div class="mc-container">
    <div class="mc-feature-row">
      <div class="mc-feature-row__content">
        <h2 class="mc-feature-row__title">Our mailing address is:</h2>
        <p class="mc-feature-row__desc">
          Intuit Mailchimp<br>
          405 N Angier Ave. NE<br>
          Atlanta, GA 30308<br>
          USA
        </p>
        <p>
          Want to learn more about Intuit&rsquo;s locations?
          <a href="#" class="mc-link mc-link--arrow">Visit Intuit</a>
        </p>
      </div>
      <div class="mc-feature-row__image">
        <img src="images/about/office.png" alt="Mailchimp office in Atlanta">
      </div>
    </div>
  </div>
</section>

<!-- ======================================================================
     CONTACT — INTUIT FAMILY
     ====================================================================== -->
<section class="mc-section mc-section--cream">
  <div class="mc-container">
    <div class="mc-contact-intuit">
      <div class="mc-contact-intuit__image">
        <img src="images/about/intuit-family.png" alt="Intuit family of products">
      </div>
      <h2 class="mc-text-center" style="margin-top: var(--space-xl);">Mailchimp is part of the Intuit family</h2>
      <p class="mc-text-center mc-text-lg" style="max-width: 640px; margin: var(--space-md) auto var(--space-xl);">
        Together with TurboTax, Credit Karma, and QuickBooks, we&rsquo;re on a mission to power
        prosperity around the world.
      </p>
      <div class="mc-contact-intuit__cards">
        <a href="#" class="mc-card mc-card--bordered mc-contact-intuit__card">
          <div class="mc-card__body mc-card__body--lg mc-text-center">
            <h4 class="mc-card__title">Intuit Website</h4>
            <span class="mc-card__link">Visit</span>
          </div>
        </a>
        <a href="#" class="mc-card mc-card--bordered mc-contact-intuit__card">
          <div class="mc-card__body mc-card__body--lg mc-text-center">
            <h4 class="mc-card__title">Contact Intuit</h4>
            <span class="mc-card__link">Get in touch</span>
          </div>
        </a>
      </div>
    </div>
  </div>
</section>

<?php include '_footer.php'; ?>
