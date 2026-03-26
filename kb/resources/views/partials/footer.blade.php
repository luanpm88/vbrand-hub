<footer class="kb-footer">
    <div class="kb-container">
        <div class="kb-footer__grid">
            {{-- Product --}}
            <div>
                <h4 class="kb-footer__heading">Product</h4>
                <ul class="kb-footer__list">
                    <li><a href="https://acellemail.com" target="_blank" rel="noopener">AcelleMail Home</a></li>
                    <li><a href="https://acellemail.com/pricing" target="_blank" rel="noopener">Pricing</a></li>
                    <li><a href="https://acellemail.com/features" target="_blank" rel="noopener">Features</a></li>
                    <li><a href="https://acellemail.com/demo" target="_blank" rel="noopener">Live Demo</a></li>
                    <li><a href="https://acellemail.com/changelog" target="_blank" rel="noopener">Changelog</a></li>
                </ul>
            </div>

            {{-- Resources --}}
            <div>
                <h4 class="kb-footer__heading">Resources</h4>
                <ul class="kb-footer__list">
                    <li><a href="{{ route('home') }}">Knowledge Base</a></li>
                    <li><a href="https://acellemail.com/docs" target="_blank" rel="noopener">Documentation</a></li>
                    <li><a href="https://acellemail.com/api" target="_blank" rel="noopener">API Reference</a></li>
                    <li><a href="{{ route('articles.search') }}">Search Articles</a></li>
                </ul>
            </div>

            {{-- Community --}}
            <div>
                <h4 class="kb-footer__heading">Community</h4>
                <ul class="kb-footer__list">
                    <li><a href="https://github.com/nicsinc/acelern" target="_blank" rel="noopener">GitHub</a></li>
                    <li><a href="https://codecanyon.net/item/acellemail/17796082" target="_blank" rel="noopener">CodeCanyon</a></li>
                    <li><a href="https://acellemail.com/support" target="_blank" rel="noopener">Support</a></li>
                    <li><a href="https://acellemail.com/blog" target="_blank" rel="noopener">Blog</a></li>
                </ul>
            </div>
        </div>

        <div class="kb-footer__bottom">
            <span>&copy; {{ date('Y') }} AcelleMail. All rights reserved.</span>
            <span class="kb-footer__powered">
                Powered by <a href="https://acellemail.com" target="_blank" rel="noopener">AcelleMail</a>
            </span>
        </div>
    </div>
</footer>
