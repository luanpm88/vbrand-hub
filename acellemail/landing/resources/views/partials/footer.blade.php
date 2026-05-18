{{-- Footer CTA --}}
<section class="mc-footer-cta">
  <div class="mc-container">
    <h2 class="mc-footer-cta__title">{{ __('footer.cta_title') }}</h2>
    <p class="mc-footer-cta__subtitle">
      {{ __('footer.cta_subtitle') }}
    </p>
    <a href="https://codecanyon.net/cart/add_items?item_ids=17796082" class="mc-btn mc-btn--primary mc-btn--lg">{{ __('cta.get_acellemail_one_time') }}</a>
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
        <h4 class="mc-footer__col-title">{{ __('footer.col_product') }}</h4>
        <nav class="mc-footer__links">
          <a href="@lroute('features')" class="mc-footer__link">{{ __('footer.product.all_features') }}</a>
          <a href="@lroute('email-marketing')" class="mc-footer__link">{{ __('footer.product.email_marketing') }}</a>
          <a href="@lroute('automation')" class="mc-footer__link">{{ __('footer.product.automation') }}</a>
          <a href="@lroute('aurius')" class="mc-footer__link">{{ __('footer.product.aurius') }}</a>
          <a href="@lroute('integrations')" class="mc-footer__link">{{ __('footer.product.integrations') }}</a>
          <a href="@lroute('security')" class="mc-footer__link">{{ __('footer.product.security') }}</a>
          <a href="@lroute('pricing')" class="mc-footer__link">{{ __('footer.product.pricing') }}</a>
        </nav>
      </div>

      <div class="mc-footer__col">
        <h4 class="mc-footer__col-title">{{ __('footer.col_resources') }}</h4>
        <nav class="mc-footer__links">
          <a href="@lroute('blog.index')" class="mc-footer__link">{{ __('footer.resources.blog') }}</a>
          <a href="@lroute('for.developers')" class="mc-footer__link">{{ __('footer.resources.for_devs') }}</a>
          <a href="@lroute('developers.index')" class="mc-footer__link">{{ __('footer.resources.plugin_docs') }}</a>
          <a href="@lroute('api')" class="mc-footer__link">{{ __('footer.resources.rest_api') }}</a>
          <a href="@lroute('glossary.index')" class="mc-footer__link">{{ __('footer.resources.glossary') }}</a>
          <a href="@lroute('kb.index')" class="mc-footer__link">{{ __('footer.resources.kb') }}</a>
          <a href="@lroute('kb.index')" class="mc-footer__link">{{ __('footer.resources.documentation') }}</a>
          <a href="@lroute('kb.category', ['slug' => 'installation-setup'])" class="mc-footer__link">{{ __('footer.resources.installation') }}</a>
          <a href="@lroute('kb.category', ['slug' => 'server-management'])" class="mc-footer__link">{{ __('footer.resources.server_req') }}</a>
        </nav>
      </div>

      <div class="mc-footer__col">
        <h4 class="mc-footer__col-title">{{ __('footer.col_community') }}</h4>
        <nav class="mc-footer__links">
          <a href="https://forum.acellemail.com" class="mc-footer__link" style="display: none;">{{ __('footer.community.forum') }}</a>
          <a href="https://forum.acellemail.com" class="mc-footer__link" style="display: none;">{{ __('footer.community.ask') }}</a>
          <a href="@lroute('kb.category', ['slug' => 'developer-guide'])" class="mc-footer__link">{{ __('footer.community.developers') }}</a>
          <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" class="mc-footer__link">{{ __('footer.community.changelog') }}</a>
        </nav>
      </div>

      <div class="mc-footer__col">
        <h4 class="mc-footer__col-title">{{ __('footer.col_compare') }}</h4>
        <nav class="mc-footer__links">
          {{-- /vs/mailchimp ships in wave 13. /vs/listmonk + /vs/mautic ship in
               wave 14. /vs/sendgrid + /vs/brevo + /vs/klaviyo ship in wave 15
               (Sendinblue rebranded to Brevo in 2023, so the legacy
               Sendinblue label points at /vs/brevo for searches that still
               use the old name). --}}
          <a href="@lroute('compare.show', ['slug' => 'mailchimp'])" class="mc-footer__link">{{ __('footer.compare_alt.mailchimp') }}</a>
          <a href="@lroute('compare.show', ['slug' => 'listmonk'])" class="mc-footer__link">{{ __('footer.compare_alt.listmonk') }}</a>
          <a href="@lroute('compare.show', ['slug' => 'mautic'])" class="mc-footer__link">{{ __('footer.compare_alt.mautic') }}</a>
          <a href="@lroute('compare.show', ['slug' => 'sendgrid'])" class="mc-footer__link">{{ __('footer.compare_alt.sendgrid') }}</a>
          <a href="@lroute('compare.show', ['slug' => 'brevo'])" class="mc-footer__link">{{ __('footer.compare_alt.brevo') }}</a>
          <a href="@lroute('compare.show', ['slug' => 'klaviyo'])" class="mc-footer__link">{{ __('footer.compare_alt.klaviyo') }}</a>
        </nav>
      </div>

      <div class="mc-footer__col">
        <h4 class="mc-footer__col-title">{{ __('footer.col_company') }}</h4>
        <nav class="mc-footer__links">
          <a href="@lroute('about')" class="mc-footer__link">{{ __('footer.company.story') }}</a>
          <a href="@lroute('about')" class="mc-footer__link">{{ __('footer.company.source_code') }}</a>
          <a href="@lroute('contact')" class="mc-footer__link">{{ __('footer.company.contact') }}</a>
          <a href="@lroute('security')" class="mc-footer__link">{{ __('footer.company.privacy') }}</a>
        </nav>
      </div>

      <div class="mc-footer__col">
        <h4 class="mc-footer__col-title">{{ __('footer.col_support') }}</h4>
        <nav class="mc-footer__links">
          <a href="@lroute('contact')" class="mc-footer__link">{{ __('footer.support.contact_us') }}</a>
          <a href="@lroute('help')" class="mc-footer__link">{{ __('footer.support.help') }}</a>
          <a href="https://forum.acellemail.com" class="mc-footer__link" style="display: none;">{{ __('footer.support.forum') }}</a>
          <a href="https://codecanyon.net/cart/add_items?item_ids=17796082" class="mc-footer__link">{{ __('footer.support.buy') }}</a>
          <a href="https://acellemail.com/demo" class="mc-footer__link">{{ __('footer.support.demo') }}</a>
        </nav>
      </div>

      <div class="mc-footer__col">
        <h4 class="mc-footer__col-title">{{ __('footer.col_partners') }}</h4>
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
        {{ __('footer.mcp_text') }}
        <a href="https://acellemail.com/demo">{{ __('footer.mcp_demo') }}</a>
      </p>
    </div>

    <div class="mc-footer__legal">
      <div class="mc-footer__legal-left">
        <span class="mc-footer__copyright">{{ __('footer.copyright_template', ['start_year' => '2016', 'current_year' => date('Y')]) }}</span>
        <nav class="mc-footer__legal-links">
          <a href="@lroute('privacy')" class="mc-footer__legal-link">{{ __('footer.legal.privacy') }}</a>
          <a href="@lroute('terms')" class="mc-footer__legal-link">{{ __('footer.legal.terms') }}</a>
          <a href="@lroute('cookies')" class="mc-footer__legal-link">{{ __('footer.legal.cookies') }}</a>
          <a href="@lroute('security')" class="mc-footer__legal-link">{{ __('footer.legal.security') }}</a>
        </nav>
      </div>

      <div class="mc-footer__legal-right">
        <x-locale-switcher placement="footer" />
      </div>
    </div>

  </div>
</footer>
