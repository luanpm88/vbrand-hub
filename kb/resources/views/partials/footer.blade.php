<footer class="kb-footer">
    <div class="kb-container">
        <div class="kb-footer__grid">
            {{-- Product --}}
            <div>
                <h4 class="kb-footer__heading">Product</h4>
                <ul class="kb-footer__list">
                    <li><a href="https://acellemail.com" target="_blank">AcelleMail Home</a></li>
                    <li><a href="https://acellemail.com/pricing" target="_blank">Pricing</a></li>
                    <li><a href="https://acellemail.com/features" target="_blank">Features</a></li>
                    <li><a href="https://acellemail.com/automation" target="_blank">Automation</a></li>
                    <li><a href="https://acellemail.com/integrations" target="_blank">Integrations</a></li>
                    <li><a href="https://demo.acellemail.com" target="_blank">Live Demo</a></li>
                </ul>
            </div>

            {{-- Resources --}}
            <div>
                <h4 class="kb-footer__heading">Resources</h4>
                <ul class="kb-footer__list">
                    <li><a href="{{ route('home') }}">Knowledge Base</a></li>
                    <li><a href="{{ route('articles.search') }}">Search Articles</a></li>
                    <li><a href="https://acellemail.com/help" target="_blank">Documentation</a></li>
                    <li><a href="https://acellemail.com/security" target="_blank">Security & GDPR</a></li>
                    <li><a href="https://acellemail.com/about" target="_blank">About AcelleMail</a></li>
                    <li><a href="https://acellemail.com/contact" target="_blank">Contact Us</a></li>
                </ul>
            </div>

            {{-- Compare --}}
            <div>
                <h4 class="kb-footer__heading">Compare</h4>
                <ul class="kb-footer__list">
                    <li><a href="{{ route('articles.category', 'migration-comparison') }}">Mailchimp Alternative</a></li>
                    <li><a href="{{ route('articles.category', 'migration-comparison') }}">SendGrid Alternative</a></li>
                    <li><a href="{{ route('articles.category', 'migration-comparison') }}">ActiveCampaign Alternative</a></li>
                    <li><a href="{{ route('articles.category', 'migration-comparison') }}">ClickFunnels Alternative</a></li>
                    <li><a href="{{ route('articles.category', 'migration-comparison') }}">Sendinblue Alternative</a></li>
                </ul>
            </div>

            {{-- Community --}}
            <div>
                <h4 class="kb-footer__heading">Community</h4>
                <ul class="kb-footer__list">
                    <li><a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" target="_blank">CodeCanyon</a></li>
                    <li><a href="https://forum.acellemail.com" target="_blank">Community Forum</a></li>
                    <li><a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082/comments" target="_blank">Support</a></li>
                    <li><a href="{{ route('articles.category', 'acellemail-updates') }}">Changelog</a></li>
                </ul>
            </div>
        </div>

        <div class="kb-footer__bottom">
            <span>&copy; {{ date('Y') }} AcelleMail. All rights reserved.</span>
            <span class="kb-footer__powered">
                Powered by <a href="https://acellemail.com" target="_blank">AcelleMail</a>
            </span>
        </div>
    </div>
</footer>
