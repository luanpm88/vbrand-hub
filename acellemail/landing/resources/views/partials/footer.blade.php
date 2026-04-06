{{-- Footer CTA --}}
<section class="mc-footer-cta">
  <div class="mc-container">
    <h2 class="mc-footer-cta__title">50,000+ businesses run their email<br> marketing on AcelleMail</h2>
    <p class="mc-footer-cta__subtitle">
      Join thousands of companies that have taken control of their email marketing with full source code,
      no recurring fees, and unlimited sending.
    </p>
    <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" class="mc-btn mc-btn--primary mc-btn--lg" target="_blank">Buy on CodeCanyon</a>
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
          <a href="{{ route('integrations') }}" class="mc-footer__link">Integrations</a>
          <a href="{{ route('security') }}" class="mc-footer__link">Security &amp; GDPR</a>
          <a href="{{ route('pricing') }}" class="mc-footer__link">Pricing</a>
        </nav>
      </div>

      <div class="mc-footer__col">
        <h4 class="mc-footer__col-title">Resources</h4>
        <nav class="mc-footer__links">
          <a href="https://knowledge.acellemail.com" class="mc-footer__link" target="_blank">Knowledge Base</a>
          <a href="https://knowledge.acellemail.com" class="mc-footer__link" target="_blank">Documentation</a>
          <a href="https://knowledge.acellemail.com/articles/rest-api-authentication-and-endpoints" class="mc-footer__link" target="_blank">API Docs</a>
          <a href="https://knowledge.acellemail.com/category/installation-setup" class="mc-footer__link" target="_blank">Installation Guide</a>
          <a href="https://knowledge.acellemail.com/category/server-management" class="mc-footer__link" target="_blank">Server Requirements</a>
        </nav>
      </div>

      <div class="mc-footer__col">
        <h4 class="mc-footer__col-title">Community</h4>
        <nav class="mc-footer__links">
          <a href="https://forum.acellemail.com" class="mc-footer__link" target="_blank">Community Forum</a>
          <a href="https://forum.acellemail.com" class="mc-footer__link" target="_blank">Ask a Question</a>
          <a href="https://knowledge.acellemail.com/category/developer-guide" class="mc-footer__link" target="_blank">Developers</a>
          <a href="https://knowledge.acellemail.com/category/acellemail-updates" class="mc-footer__link" target="_blank">Changelog</a>
        </nav>
      </div>

      <div class="mc-footer__col">
        <h4 class="mc-footer__col-title">Compare</h4>
        <nav class="mc-footer__links">
          <a href="https://knowledge.acellemail.com/category/migration-comparison" class="mc-footer__link" target="_blank">Mailchimp Alternative</a>
          <a href="https://knowledge.acellemail.com/category/migration-comparison" class="mc-footer__link" target="_blank">SendGrid Alternative</a>
          <a href="https://knowledge.acellemail.com/category/migration-comparison" class="mc-footer__link" target="_blank">ActiveCampaign Alternative</a>
          <a href="https://knowledge.acellemail.com/category/migration-comparison" class="mc-footer__link" target="_blank">ClickFunnels Alternative</a>
          <a href="https://knowledge.acellemail.com/category/migration-comparison" class="mc-footer__link" target="_blank">Sendinblue Alternative</a>
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
          <a href="https://forum.acellemail.com" class="mc-footer__link" target="_blank">Community Forum</a>
          <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" class="mc-footer__link" target="_blank">Buy a License</a>
          <a href="https://demo.acellemail.com" class="mc-footer__link" target="_blank">Live Demo</a>
        </nav>
      </div>
    </div>

    <div class="mc-footer__mcp">
      <div class="mc-footer__mcp-logo">
        <img src="{{ asset('images/logo_light.svg') }}" alt="AcelleMail" width="110" height="25">
      </div>
      <p class="mc-footer__mcp-text">
        Self-hosted email marketing built on Laravel. Full source code, unlimited emails, your server.
        <a href="https://demo.acellemail.com" target="_blank">Try the demo</a>
      </p>
    </div>

    <div class="mc-footer__legal">
      <div class="mc-footer__legal-left">
        <span class="mc-footer__copyright">&copy; 2016&ndash;{{ date('Y') }} AcelleMail. All Rights Reserved.</span>
        <nav class="mc-footer__legal-links">
          <a href="{{ route('security') }}" class="mc-footer__legal-link">Privacy</a>
          <a href="{{ route('security') }}" class="mc-footer__legal-link">Terms</a>
          <a href="{{ route('security') }}" class="mc-footer__legal-link">GDPR</a>
        </nav>
      </div>
    </div>

  </div>
</footer>
