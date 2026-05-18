{{-- Footer CTA --}}
<section class="mc-footer-cta">
  <div class="mc-container">
    <h2 class="mc-footer-cta__title">Run your email marketing on<br>your own server, your own terms</h2>
    <p class="mc-footer-cta__subtitle">
      Join thousands of companies that have taken control of their email marketing with full source code,
      no recurring fees, and unlimited sending. One-time $74 license, lifetime updates.
    </p>
    <a href="https://codecanyon.net/cart/add_items?item_ids=17796082" class="mc-btn mc-btn--primary mc-btn--lg">Get AcelleMail — $74 one-time</a>
  </div>
</section>

{{-- Newsletter signup (cross-site, every page). Placement choice: above
     the link grid so it's visible in the first scroll of the footer,
     not buried under fine print. Source = 'footer' for attribution. --}}
<section class="mc-footer-newsletter" aria-labelledby="footer-newsletter-title">
  <div class="mc-container mc-footer-newsletter__inner">
    <div class="mc-footer-newsletter__copy">
      <h3 id="footer-newsletter-title" class="mc-footer-newsletter__title">Get the AcelleMail newsletter</h3>
      <p class="mc-footer-newsletter__subtitle">Monthly issue with new releases, deliverability tips, and self-hosting playbooks. No spam.</p>
    </div>
    <x-newsletter.inline source="footer" variant="compact" cta="Subscribe" />
  </div>
</section>

{{-- Main Footer --}}
<footer class="mc-footer">
  <div class="mc-container">

    <div class="mc-footer__grid">
      <div class="mc-footer__col">
        <h4 class="mc-footer__col-title">Product</h4>
        <nav class="mc-footer__links">
          <a href="{{ route('features') }}" class="mc-footer__link">All Features</a>
          <a href="{{ route('email-marketing') }}" class="mc-footer__link">Email Marketing</a>
          <a href="{{ route('automation') }}" class="mc-footer__link">Automation</a>
          <a href="{{ route('aurius') }}" class="mc-footer__link">Aurius 4.0 &mdash; AI plugin</a>
          <a href="{{ route('integrations') }}" class="mc-footer__link">Integrations</a>
          <a href="{{ route('security') }}" class="mc-footer__link">Security &amp; GDPR</a>
          <a href="{{ route('pricing') }}" class="mc-footer__link">Pricing</a>
        </nav>
      </div>

      <div class="mc-footer__col">
        <h4 class="mc-footer__col-title">Resources</h4>
        <nav class="mc-footer__links">
          <a href="{{ route('blog.index') }}" class="mc-footer__link">Blog</a>
          <a href="{{ route('for.developers') }}" class="mc-footer__link">For Developers</a>
          <a href="{{ route('developers.index') }}" class="mc-footer__link">Plugin Documentation</a>
          <a href="{{ route('api') }}" class="mc-footer__link">REST API</a>
          <a href="{{ route('glossary.index') }}" class="mc-footer__link">Glossary</a>
          <a href="/kb" class="mc-footer__link">Knowledge Base</a>
          <a href="/kb" class="mc-footer__link">Documentation</a>
          <a href="/kb/category/installation-setup" class="mc-footer__link">Installation Guide</a>
          <a href="/kb/category/server-management" class="mc-footer__link">Server Requirements</a>
        </nav>
      </div>

      <div class="mc-footer__col">
        <h4 class="mc-footer__col-title">Community</h4>
        <nav class="mc-footer__links">
          <a href="https://forum.acellemail.com" class="mc-footer__link" style="display: none;">Community Forum</a>
          <a href="https://forum.acellemail.com" class="mc-footer__link" style="display: none;">Ask a Question</a>
          <a href="/kb/category/developer-guide" class="mc-footer__link">Developers</a>
          <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" class="mc-footer__link">Changelog</a>
        </nav>
      </div>

      <div class="mc-footer__col">
        <h4 class="mc-footer__col-title">Compare</h4>
        <nav class="mc-footer__links">
          {{-- /vs/mailchimp ships in wave 13. /vs/listmonk + /vs/mautic ship in
               wave 14. /vs/sendgrid + /vs/brevo + /vs/klaviyo ship in wave 15
               (Sendinblue rebranded to Brevo in 2023, so the legacy
               Sendinblue label points at /vs/brevo for searches that still
               use the old name). --}}
          <a href="{{ route('compare.show', ['slug' => 'mailchimp']) }}" class="mc-footer__link">Mailchimp Alternative</a>
          <a href="{{ route('compare.show', ['slug' => 'listmonk']) }}" class="mc-footer__link">listmonk Alternative</a>
          <a href="{{ route('compare.show', ['slug' => 'mautic']) }}" class="mc-footer__link">Mautic Alternative</a>
          <a href="{{ route('compare.show', ['slug' => 'sendgrid']) }}" class="mc-footer__link">SendGrid Alternative</a>
          <a href="{{ route('compare.show', ['slug' => 'brevo']) }}" class="mc-footer__link">Brevo Alternative</a>
          <a href="{{ route('compare.show', ['slug' => 'klaviyo']) }}" class="mc-footer__link">Klaviyo Alternative</a>
        </nav>
      </div>

      <div class="mc-footer__col">
        <h4 class="mc-footer__col-title">Company</h4>
        <nav class="mc-footer__links">
          <a href="{{ route('about') }}" class="mc-footer__link">Our Story</a>
          <a href="{{ route('about') }}" class="mc-footer__link">Full Source Code</a>
          <a href="{{ route('contact') }}" class="mc-footer__link">Contact</a>
          <a href="{{ route('security') }}" class="mc-footer__link">Privacy &amp; GDPR</a>
        </nav>
      </div>

      <div class="mc-footer__col">
        <h4 class="mc-footer__col-title">Support</h4>
        <nav class="mc-footer__links">
          <a href="{{ route('contact') }}" class="mc-footer__link">Contact Us</a>
          <a href="{{ route('help') }}" class="mc-footer__link">Help Center</a>
          <a href="https://forum.acellemail.com" class="mc-footer__link" style="display: none;">Community Forum</a>
          <a href="https://codecanyon.net/cart/add_items?item_ids=17796082" class="mc-footer__link">Buy License — $74</a>
          <a href="https://acellemail.com/demo" class="mc-footer__link">Live Demo</a>
        </nav>
      </div>

      <div class="mc-footer__col">
        <h4 class="mc-footer__col-title">Partners</h4>
        <nav class="mc-footer__links">
          <a href="https://emotsy.com/" class="mc-footer__link" target="_blank" rel="noopener">Emotsy SIA</a>
          <a href="https://ipwarmup.com/" class="mc-footer__link" target="_blank" rel="noopener">IPwarmup.com</a>
          <a href="https://rencontru.net/" class="mc-footer__link" target="_blank" rel="noopener">Rencontru LTD</a>
          <a href="https://mobilemessage.com.au/" class="mc-footer__link" target="_blank" rel="noopener">Mobile Message</a>
        </nav>
      </div>
    </div>

    <div class="mc-footer__mcp">
      <div class="mc-footer__mcp-logo">
        @php $footerLogo = config('landing.theme') === 'theme-pleo' ? 'logo_dark.svg' : 'logo_light.svg'; @endphp
        <img src="{{ asset('images/' . $footerLogo) }}" alt="AcelleMail" width="110" height="25">
      </div>
      <p class="mc-footer__mcp-text">
        Self-hosted email marketing built on Laravel. Full source code, unlimited emails, your server.
        <a href="https://acellemail.com/demo">Try the demo</a>
      </p>
    </div>

    <div class="mc-footer__legal">
      <div class="mc-footer__legal-left">
        <span class="mc-footer__copyright">&copy; 2016&ndash;{{ date('Y') }} AcelleMail. All Rights Reserved.</span>
        <nav class="mc-footer__legal-links">
          <a href="{{ route('privacy') }}" class="mc-footer__legal-link">Privacy</a>
          <a href="{{ route('terms') }}" class="mc-footer__legal-link">Terms</a>
          <a href="{{ route('cookies') }}" class="mc-footer__legal-link">Cookies</a>
          <a href="{{ route('security') }}" class="mc-footer__legal-link">Security &amp; GDPR</a>
        </nav>
      </div>
    </div>

  </div>
</footer>
