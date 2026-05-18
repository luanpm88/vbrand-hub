<?php

use App\Http\Controllers\Admin\CommentsController as AdminCommentsController;
use App\Http\Controllers\Admin\ContactsController as AdminContactsController;
use App\Http\Controllers\Admin\CtaController as AdminCtaController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SubscribersController as AdminSubscribersController;
use App\Http\Controllers\Admin\UgcSweepController as AdminUgcSweepController;
use App\Http\Controllers\Admin\UsersController as AdminUsersController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Cta\TrackController as CtaTrackController;
use App\Http\Controllers\GlossaryController;
use App\Http\Controllers\Kb\ArticleController as KbArticleController;
use App\Http\Controllers\Kb\EngagementController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/features', [PageController::class, 'features'])->name('features');
Route::get('/email-marketing', [PageController::class, 'emailMarketing'])->name('email-marketing');
Route::get('/automation', [PageController::class, 'automation'])->name('automation');
Route::get('/integrations', [PageController::class, 'integrations'])->name('integrations');
Route::get('/pricing', [PageController::class, 'pricing'])->name('pricing');

// Aurius 4.0 — managed AI service for Acelle (acelle/ai plugin). Marketing
// + pricing page; used as the business-verification target for the AI
// service's payment gateway. Underlying product surfaces live in the
// acelle/ai plugin (chatbox, sparkle rewrite, coach personas, observability).
Route::get('/aurius', [PageController::class, 'aurius'])->name('aurius');

Route::get('/security', [PageController::class, 'security'])->name('security');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/help', [PageController::class, 'help'])->name('help');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
// Real form handler (replaces the old mailto: link). Named limiter
// `contact-submit` is defined in AppServiceProvider; prod cap = 5/min/IP.
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:contact-submit')
    ->name('contact.store');
// Dedicated thank-you destination after a successful submit. Reads the
// flash payload and bounces back to /contact if hit directly.
Route::get('/contact/thanks', [ContactController::class, 'thanks'])->name('contact.thanks');

// Newsletter signup + one-click unsubscribe. Public; no auth.
// `newsletter-subscribe` limiter: prod = 5/min/IP. Unsubscribe is GET so
// the signed link in the welcome email works directly in inbox previews.
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
    ->middleware('throttle:newsletter-subscribe')
    ->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe'])
    ->name('newsletter.unsubscribe');

// Legal pages (Wave 12 / SEO_PLAN §3.7) — split from previous /security catch-all.
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms',   [PageController::class, 'terms'])->name('terms');
Route::get('/cookies', [PageController::class, 'cookies'])->name('cookies');

// Persona / for-* pages (Wave 17 + Wave 18 — see DEVELOPER_CONTENT_PLAN.md + SEO_PLAN §4.2)
Route::get('/for/developers',  [PageController::class, 'forDevelopers'])->name('for.developers');
Route::get('/for/saas',        [PageController::class, 'forSaas'])->name('for.saas');
Route::get('/for/agencies',    [PageController::class, 'forAgencies'])->name('for.agencies');
Route::get('/for/ecommerce',   [PageController::class, 'forEcommerce'])->name('for.ecommerce');
Route::get('/for/newsletters', [PageController::class, 'forNewsletters'])->name('for.newsletters');
Route::get('/for/enterprise',  [PageController::class, 'forEnterprise'])->name('for.enterprise');

// REST API reference — native landing page (replaces external KB link).
Route::get('/api', [PageController::class, 'api'])->name('api');

// Pillar guides (top-of-funnel, SEO_PLAN §4.3 — capture research-stage queries
// before product comparison; long-form, structured for featured snippets +
// AI Overviews; cluster head linking down to /vs/* and /for/* pages).
Route::get('/guide/self-hosted-email-marketing', [PageController::class, 'guideSelfHosted'])->name('guide.self-hosted');
Route::get('/guide/email-marketing-cost-savings', [PageController::class, 'guideCostSavings'])->name('guide.cost-savings');
Route::get('/guide/email-deliverability',         [PageController::class, 'guideDeliverability'])->name('guide.deliverability');

// Developer documentation suite — /developers index hub
// (Wave 17a-docs-1; deep-dives 2-11 land in subsequent waves under /developers/<slug>).
Route::get('/developers', [PageController::class, 'developersIndex'])->name('developers.index');
Route::get('/developers/getting-started',     [PageController::class, 'developersGettingStarted'])->name('developers.getting-started');
Route::get('/developers/plugin-architecture', [PageController::class, 'developersPluginArchitecture'])->name('developers.plugin-architecture');
Route::get('/developers/hook-system',         [PageController::class, 'developersHookSystem'])->name('developers.hook-system');
Route::get('/developers/ui-injection',        [PageController::class, 'developersUiInjection'])->name('developers.ui-injection');
Route::get('/developers/database-models',     [PageController::class, 'developersDatabaseModels'])->name('developers.database-models');
Route::get('/developers/translations',        [PageController::class, 'developersTranslations'])->name('developers.translations');
Route::get('/developers/lifecycle',           [PageController::class, 'developersLifecycle'])->name('developers.lifecycle');
Route::get('/developers/testing',             [PageController::class, 'developersTesting'])->name('developers.testing');
Route::get('/developers/sending-drivers',     [PageController::class, 'developersSendingDrivers'])->name('developers.sending-drivers');
Route::get('/developers/payment-gateways',    [PageController::class, 'developersPaymentGateways'])->name('developers.payment-gateways');
Route::get('/developers/showcase',            [PageController::class, 'developersShowcase'])->name('developers.showcase');

// Comparison pages — /vs/{slug} (SEO_PLAN.md §4.1, wave 13+).
// Slug constraint guards against arbitrary routing; data file presence is
// validated in CompareController (404 if resources/compare/{slug}.php missing).
Route::get('/vs/{slug}', [CompareController::class, 'show'])
    ->where('slug', '[a-z0-9-]+')
    ->name('compare.show');

// Glossary — /glossary index + /glossary/{slug} per term (SEO_PLAN.md §4.5,
// wave 21). DefinedTerm + DefinedTermSet schema for AI-Overview surfaces.
// Slug constraint mirrors /vs/; entry presence validated in GlossaryController.
Route::get('/glossary',         [GlossaryController::class, 'index'])->name('glossary.index');
Route::get('/glossary/{slug}',  [GlossaryController::class, 'show'])
    ->where('slug', '[a-z0-9-]+')
    ->name('glossary.show');

// Blog — /blog index + /blog/{slug} posts + /blog/rss.xml feed
// (SEO_PLAN.md §4.4, wave 22). Posts auto-discovered from
// resources/blog/posts/*.php; tag taxonomy: tutorial, comparison,
// deliverability, release-notes, industry. Each post emits Article schema.
// RSS route is registered BEFORE the slug catch-all so it is matched first.
Route::get('/blog',              [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/rss.xml',      [BlogController::class, 'rss'])->name('blog.rss');
Route::get('/blog/{slug}',       [BlogController::class, 'show'])
    ->where('slug', '[a-z0-9-]+')
    ->name('blog.show');

Route::get('/demo', function () {
    return redirect()->away('https://demo.acellemail.com/demo');
})->name('demo');

// ----------------------------------------------------------------------------
// Auth — quick email+password register/login for KB engagement (Wave 0).
// No email verification (per COMMENT_LIKE_KB_CTA_PLAN.md §3.1) — registration
// is intentionally one screen. Sessions are DB-backed and CSRF-auto-protected
// by Laravel's standard web middleware.
// ----------------------------------------------------------------------------
Route::middleware('guest')->group(function () {
    Route::get('/auth/login',    [AuthController::class, 'showLogin'])->name('auth.login');
    Route::get('/auth/register', [AuthController::class, 'showRegister'])->name('auth.register');
    // Named limiters defined in AppServiceProvider::configureAuthRateLimits().
    // Production: 10 login/min, 5 register/min per IP. Local + testing: 300/min
    // so the Playwright suite (50+ register calls / minute from 127.0.0.1
    // across the desktop + mobile projects) doesn't trip the limit.
    Route::post('/auth/login',    [AuthController::class, 'login'])
        ->middleware('throttle:auth-login')->name('auth.login.attempt');
    Route::post('/auth/register', [AuthController::class, 'register'])
        ->middleware('throttle:auth-register')->name('auth.register.attempt');
});
Route::middleware('auth')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

// Knowledge Base — moved from knowledge.acellemail.com → acellemail.com/kb/*
// (architectural decision 2026-05-09: subfolder consolidates domain authority,
// makes 91 cross-links from landing → KB internal, lets KB articles link up
// to /guide/* pillars natively. nginx serves a 301 redirect from
// knowledge.acellemail.com/* → /kb/* to preserve inbound backlinks).
Route::prefix('kb')->name('kb.')->group(function () {
    Route::get('/',                  [KbArticleController::class, 'index'])->name('index');
    Route::get('/search',            [KbArticleController::class, 'search'])->name('search');
    Route::get('/category/{slug}',   [KbArticleController::class, 'category'])
        ->where('slug', '[a-z0-9-]+')->name('category');
    Route::get('/tag/{slug}',        [KbArticleController::class, 'tag'])
        ->where('slug', '[a-z0-9-]+')->name('tag');
    Route::get('/articles/{slug}',   [KbArticleController::class, 'show'])
        ->where('slug', '[a-z0-9-]+')->name('articles.show');
});

// ----------------------------------------------------------------------------
// Engagement — Wave 1 (likes + comments) + Wave 2 (edit/delete-own + report).
// All endpoints require an authenticated session; rate-limited per user via
// named limiters in AppServiceProvider::configureEngagementRateLimits().
// ----------------------------------------------------------------------------
Route::middleware('auth')->prefix('kb')->name('kb.')->group(function () {
    Route::post('/articles/{slug}/like', [EngagementController::class, 'toggleArticleLike'])
        ->where('slug', '[a-z0-9-]+')
        ->middleware('throttle:engagement-like')
        ->name('articles.like');

    Route::post('/articles/{slug}/comments', [EngagementController::class, 'storeComment'])
        ->where('slug', '[a-z0-9-]+')
        ->middleware('throttle:engagement-comment')
        ->name('articles.comments.store');

    Route::patch('/comments/{comment}', [EngagementController::class, 'updateComment'])
        ->middleware('throttle:engagement-comment')
        ->name('comments.update');

    Route::delete('/comments/{comment}', [EngagementController::class, 'deleteComment'])
        ->middleware('throttle:engagement-comment')
        ->name('comments.destroy');

    Route::post('/comments/{comment}/like', [EngagementController::class, 'toggleCommentLike'])
        ->middleware('throttle:engagement-like')
        ->name('comments.like');

    Route::post('/comments/{comment}/report', [EngagementController::class, 'reportComment'])
        ->middleware('throttle:engagement-comment')
        ->name('comments.report');
});

// ----------------------------------------------------------------------------
// Admin namespace. Gated by `auth` + `admin` (EnsureUserIsAdmin middleware,
// registered in bootstrap/app.php). Layout: resources/views/admin/layout.blade.php
// extends layouts.app so admin surfaces inherit the landing site shell.
// ----------------------------------------------------------------------------
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', AdminDashboardController::class)->name('dashboard');

    // Users — full CRUD + admin role toggle.
    Route::get('/users',                [AdminUsersController::class, 'index'])->name('users.index');
    Route::get('/users/create',         [AdminUsersController::class, 'create'])->name('users.create');
    Route::post('/users',               [AdminUsersController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit',    [AdminUsersController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}',         [AdminUsersController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}',      [AdminUsersController::class, 'destroy'])->name('users.destroy');

    // Comments — moderation queue (existing).
    Route::get('/comments',                       [AdminCommentsController::class, 'index'])->name('comments.index');
    Route::post('/comments/{comment}/hide',       [AdminCommentsController::class, 'hide'])->name('comments.hide');
    Route::post('/comments/{comment}/restore',    [AdminCommentsController::class, 'restore'])->name('comments.restore');

    // UGC Sweep — daily abuse digest history + on-demand run.
    Route::get('/ugc-sweep',          [AdminUgcSweepController::class, 'index'])->name('ugc.index');
    Route::post('/ugc-sweep/run',     [AdminUgcSweepController::class, 'runNow'])->name('ugc.run');
    Route::get('/ugc-sweep/{date}',   [AdminUgcSweepController::class, 'show'])->name('ugc.show');

    // CTAs — toggle active/paused (existing).
    Route::get('/cta',                  [AdminCtaController::class, 'index'])->name('cta.index');
    Route::post('/cta/{variant}/toggle',[AdminCtaController::class, 'toggle'])->name('cta.toggle');

    // Contact submissions inbox (Wave 5 / 2026-05-17). Replaces the old
    // mailto: form on /contact — submissions land in `contact_submissions`
    // and surface here with status filtering + per-row admin notes.
    Route::get('/contacts',             [AdminContactsController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/{contact}',   [AdminContactsController::class, 'show'])->name('contacts.show');
    Route::patch('/contacts/{contact}', [AdminContactsController::class, 'updateStatus'])->name('contacts.update');
    Route::delete('/contacts/{contact}',[AdminContactsController::class, 'destroy'])->name('contacts.destroy');

    // Newsletter subscribers (Wave 5). DB-only signups from footer + inline
    // CTAs; CSV export feeds an Acelle list import when ready.
    Route::get('/subscribers',                 [AdminSubscribersController::class, 'index'])->name('subscribers.index');
    Route::get('/subscribers/export',          [AdminSubscribersController::class, 'export'])->name('subscribers.export');
    Route::delete('/subscribers/{subscriber}', [AdminSubscribersController::class, 'destroy'])->name('subscribers.destroy');
});

// ----------------------------------------------------------------------------
// CTA tracker — POST /cta/{slug}/track, public (no auth), CSRF-protected via
// standard web middleware. Fire-and-forget from public/js/script.js when a
// variant is shown/clicked/dismissed.
// ----------------------------------------------------------------------------
Route::post('/cta/{slug}/track', CtaTrackController::class)
    ->where('slug', '[a-z0-9-]+')
    ->name('cta.track');

// Legacy KB redirects — knowledge.acellemail.com used /category/{slug} and
// /articles/{slug} at the root. nginx handles the host-level 301 in production;
// these routes catch any internal/legacy refs that hit the new host directly.
Route::get('/category/{slug}', fn(string $slug) => redirect()->route('kb.category', $slug, 301))
    ->where('slug', '[a-z0-9-]+');
Route::get('/articles/{slug}', fn(string $slug) => redirect()->route('kb.articles.show', $slug, 301))
    ->where('slug', '[a-z0-9-]+');
Route::get('/tag/{slug}', fn(string $slug) => redirect()->route('kb.tag', $slug, 301))
    ->where('slug', '[a-z0-9-]+');

Route::get('/sitemap.xml', function () {
    // Each entry's lastmod is the blade template's filemtime — gives Google
    // a stable signal that only updates when the page content actually changes.
    // Using now() (the previous behaviour) reset every request and made the
    // signal meaningless. See SEO_PLAN.md §3.6.
    $entries = [
        ['url' => '/',                 'view' => 'pages/home',            'priority' => '1.0',  'changefreq' => 'weekly'],
        ['url' => '/features',         'view' => 'pages/features',        'priority' => '0.9',  'changefreq' => 'monthly'],
        ['url' => '/email-marketing',  'view' => 'pages/email-marketing', 'priority' => '0.9',  'changefreq' => 'monthly'],
        ['url' => '/automation',       'view' => 'pages/automation',      'priority' => '0.9',  'changefreq' => 'monthly'],
        ['url' => '/integrations',     'view' => 'pages/integrations',    'priority' => '0.8',  'changefreq' => 'monthly'],
        ['url' => '/pricing',          'view' => 'pages/pricing',         'priority' => '0.95', 'changefreq' => 'monthly'],
        // Aurius 4.0 — managed AI service, payment-gateway business-
        // verification target. Priority just under pricing (it IS pricing
        // for the AI service); monthly because plans/copy may iterate.
        ['url' => '/aurius',           'view' => 'pages/aurius',          'priority' => '0.9',  'changefreq' => 'monthly'],
        ['url' => '/security',         'view' => 'pages/security',        'priority' => '0.7',  'changefreq' => 'monthly'],
        ['url' => '/about',            'view' => 'pages/about',           'priority' => '0.5',  'changefreq' => 'yearly'],
        ['url' => '/help',             'view' => 'pages/help',            'priority' => '0.7',  'changefreq' => 'monthly'],
        ['url' => '/contact',          'view' => 'pages/contact',         'priority' => '0.6',  'changefreq' => 'yearly'],
        ['url' => '/privacy',          'view' => 'pages/privacy',         'priority' => '0.3',  'changefreq' => 'yearly'],
        ['url' => '/terms',            'view' => 'pages/terms',           'priority' => '0.3',  'changefreq' => 'yearly'],
        ['url' => '/cookies',          'view' => 'pages/cookies',         'priority' => '0.3',  'changefreq' => 'yearly'],
        ['url' => '/for/developers',   'view' => 'pages/for-developers',   'priority' => '0.85', 'changefreq' => 'monthly'],
        ['url' => '/for/saas',         'view' => 'pages/for-saas',         'priority' => '0.85', 'changefreq' => 'monthly'],
        ['url' => '/for/agencies',     'view' => 'pages/for-agencies',     'priority' => '0.85', 'changefreq' => 'monthly'],
        ['url' => '/for/ecommerce',    'view' => 'pages/for-ecommerce',    'priority' => '0.85', 'changefreq' => 'monthly'],
        ['url' => '/for/newsletters',  'view' => 'pages/for-newsletters',  'priority' => '0.85', 'changefreq' => 'monthly'],
        ['url' => '/for/enterprise',   'view' => 'pages/for-enterprise',   'priority' => '0.85', 'changefreq' => 'monthly'],
        ['url' => '/api',              'view' => 'pages/api',             'priority' => '0.9',  'changefreq' => 'monthly'],
        // Pillar guides (SEO_PLAN §4.3) — high priority, stable content.
        ['url' => '/guide/self-hosted-email-marketing', 'view' => 'pages/guide/self-hosted-email-marketing', 'priority' => '0.9', 'changefreq' => 'monthly'],
        ['url' => '/guide/email-marketing-cost-savings', 'view' => 'pages/guide/email-marketing-cost-savings', 'priority' => '0.9', 'changefreq' => 'monthly'],
        ['url' => '/guide/email-deliverability',         'view' => 'pages/guide/email-deliverability',         'priority' => '0.9', 'changefreq' => 'monthly'],
        ['url' => '/developers',                  'view' => 'pages/developers/index',           'priority' => '0.85', 'changefreq' => 'monthly'],
        ['url' => '/developers/getting-started',     'view' => 'pages/developers/getting-started',     'priority' => '0.8',  'changefreq' => 'monthly'],
        ['url' => '/developers/plugin-architecture', 'view' => 'pages/developers/plugin-architecture', 'priority' => '0.8',  'changefreq' => 'monthly'],
        ['url' => '/developers/hook-system',         'view' => 'pages/developers/hook-system',         'priority' => '0.8',  'changefreq' => 'monthly'],
        ['url' => '/developers/ui-injection',        'view' => 'pages/developers/ui-injection',        'priority' => '0.8',  'changefreq' => 'monthly'],
        ['url' => '/developers/database-models',     'view' => 'pages/developers/database-models',     'priority' => '0.8',  'changefreq' => 'monthly'],
        ['url' => '/developers/translations',        'view' => 'pages/developers/translations',        'priority' => '0.8',  'changefreq' => 'monthly'],
        ['url' => '/developers/lifecycle',           'view' => 'pages/developers/lifecycle',           'priority' => '0.8',  'changefreq' => 'monthly'],
        ['url' => '/developers/testing',             'view' => 'pages/developers/testing',             'priority' => '0.8',  'changefreq' => 'monthly'],
        ['url' => '/developers/sending-drivers',     'view' => 'pages/developers/sending-drivers',     'priority' => '0.8',  'changefreq' => 'monthly'],
        ['url' => '/developers/payment-gateways',    'view' => 'pages/developers/payment-gateways',    'priority' => '0.8',  'changefreq' => 'monthly'],
        ['url' => '/developers/showcase',            'view' => 'pages/developers/showcase',            'priority' => '0.8',  'changefreq' => 'monthly'],
        // Comparison pages — added per /vs/{slug} wave (SEO_PLAN §4.1).
        // lastmod here uses the data file (resources/compare/<slug>.php) instead
        // of a view, since the page template is shared and data is the page.
        ['url' => '/vs/mailchimp',     'data' => 'compare/mailchimp.php',  'priority' => '0.85', 'changefreq' => 'monthly'],
        ['url' => '/vs/listmonk',      'data' => 'compare/listmonk.php',   'priority' => '0.8',  'changefreq' => 'monthly'],
        ['url' => '/vs/mautic',        'data' => 'compare/mautic.php',     'priority' => '0.8',  'changefreq' => 'monthly'],
        ['url' => '/vs/sendgrid',      'data' => 'compare/sendgrid.php',   'priority' => '0.8',  'changefreq' => 'monthly'],
        ['url' => '/vs/brevo',         'data' => 'compare/brevo.php',      'priority' => '0.8',  'changefreq' => 'monthly'],
        ['url' => '/vs/klaviyo',       'data' => 'compare/klaviyo.php',    'priority' => '0.8',  'changefreq' => 'monthly'],
        // Glossary — index + per-term (SEO_PLAN §4.5, wave 21). All terms
        // share resources/glossary/index.php as single source of truth, so
        // lastmod for every term is the same data-file mtime.
        ['url' => '/glossary',                'view' => 'pages/glossary/index', 'priority' => '0.7', 'changefreq' => 'monthly'],
        ['url' => '/glossary/smtp',           'data' => 'glossary/index.php',   'priority' => '0.6', 'changefreq' => 'monthly'],
        ['url' => '/glossary/spf',            'data' => 'glossary/index.php',   'priority' => '0.6', 'changefreq' => 'monthly'],
        ['url' => '/glossary/dkim',           'data' => 'glossary/index.php',   'priority' => '0.6', 'changefreq' => 'monthly'],
        ['url' => '/glossary/dmarc',          'data' => 'glossary/index.php',   'priority' => '0.6', 'changefreq' => 'monthly'],
        ['url' => '/glossary/amazon-ses',     'data' => 'glossary/index.php',   'priority' => '0.6', 'changefreq' => 'monthly'],
        ['url' => '/glossary/double-opt-in',  'data' => 'glossary/index.php',   'priority' => '0.6', 'changefreq' => 'monthly'],
        ['url' => '/glossary/hard-bounce',    'data' => 'glossary/index.php',   'priority' => '0.6', 'changefreq' => 'monthly'],
        ['url' => '/glossary/soft-bounce',    'data' => 'glossary/index.php',   'priority' => '0.6', 'changefreq' => 'monthly'],
        ['url' => '/glossary/ip-warmup',      'data' => 'glossary/index.php',   'priority' => '0.6', 'changefreq' => 'monthly'],
        ['url' => '/glossary/email-deliverability', 'data' => 'glossary/index.php', 'priority' => '0.6', 'changefreq' => 'monthly'],
    ];

    // Blog (SEO_PLAN §4.4, wave 22). Posts are filesystem-discovered so the
    // sitemap registers itself from blog/posts/*.php — no manual upkeep when
    // posts are added or removed.
    $entries[] = ['url' => '/blog', 'view' => 'pages/blog/index', 'priority' => '0.75', 'changefreq' => 'weekly'];
    foreach (App\Http\Controllers\BlogController::slugs() as $slug) {
        $entries[] = [
            'url' => '/blog/' . $slug,
            // 'absolute' => the per-post data file path; the loop below
            // resolves lastmod from this file's mtime.
            'absolute' => resource_path('blog/posts/' . $slug . '.php'),
            'priority' => '0.7',
            'changefreq' => 'monthly',
        ];
    }

    // Knowledge Base — auto-discovered from DB (categories + published articles).
    // lastmod uses Eloquent updated_at so the sitemap reflects real edit times.
    // Tag pages are deliberately excluded from the sitemap (low-value taxonomy).
    if (\Illuminate\Support\Facades\Schema::hasTable('articles')) {
        $entries[] = ['url' => '/kb', 'absolute_lastmod' => now()->toW3cString(), 'priority' => '0.85', 'changefreq' => 'weekly'];
        foreach (\App\Models\Category::orderBy('sort_order')->get() as $cat) {
            $entries[] = [
                'url' => '/kb/category/' . $cat->slug,
                'absolute_lastmod' => $cat->updated_at->toW3cString(),
                'priority' => '0.7',
                'changefreq' => 'weekly',
            ];
        }
        foreach (\App\Models\Article::published()->orderByDesc('updated_at')->get() as $a) {
            $entries[] = [
                'url' => '/kb/articles/' . $a->slug,
                'absolute_lastmod' => $a->updated_at->toW3cString(),
                'priority' => '0.7',
                'changefreq' => 'monthly',
            ];
        }
    }

    foreach ($entries as &$e) {
        if (isset($e['absolute_lastmod'])) {
            $e['lastmod'] = $e['absolute_lastmod'];
            continue;
        }
        if (isset($e['absolute'])) {
            $path = $e['absolute'];
        } elseif (isset($e['data'])) {
            $path = resource_path($e['data']);
        } else {
            $path = resource_path('views/' . $e['view'] . '.blade.php');
        }
        $e['lastmod'] = file_exists($path)
            ? \Carbon\Carbon::createFromTimestamp(filemtime($path))->toW3cString()
            : now()->toW3cString();
    }
    unset($e);

    return response()
        ->view('sitemap', ['entries' => $entries])
        ->header('Content-Type', 'application/xml');
})->name('sitemap');

// i18n route mirrors (PLAN §11 Wave 1).
//
// These groups register every EN route's counterpart under the locale prefix.
// Locale gating is enforced by the SetLocale middleware: when a locale's
// `enabled` flag is false (Wave 1 baseline for both VI and JA), any hit on
// /vi/* or /ja/* aborts with 404 — the route is bound but unreachable.
//
// The controller methods are reused as-is. Wave 3+ teaches each method how
// to pick the per-locale blade (e.g. `view('pages.vi.home')` when
// `app()->getLocale() === 'vi'`). Wave 1 only needs the URI surface.
//
// Slug map: config/i18n.php → 'slugs' (single source of truth). Drift between
// this file and the slug map is caught by `php artisan i18n:sync-routes`
// (see App\Console\Commands\I18nSyncRoutes).

$_i18nSlugs = config('i18n.slugs', []);
foreach (['vi' => 'vi.', 'ja' => 'ja.'] as $localePrefix => $namePrefix) {
    Route::prefix($localePrefix)->name($namePrefix)->group(function () use ($localePrefix, $_i18nSlugs) {
        // Dot-keyed slug names (e.g. 'kb.search') would be interpreted as
        // nested config paths by Laravel's config() helper, so we read the
        // whole map once and index by literal string key.
        $slug = fn(string $key) => $_i18nSlugs[$key][$localePrefix] ?? $_i18nSlugs[$key]['en'] ?? '';

        Route::get('/'.$slug('home'),              [PageController::class, 'home'])->name('home');
        Route::get('/'.$slug('features'),          [PageController::class, 'features'])->name('features');
        Route::get('/'.$slug('email-marketing'),   [PageController::class, 'emailMarketing'])->name('email-marketing');
        Route::get('/'.$slug('automation'),        [PageController::class, 'automation'])->name('automation');
        Route::get('/'.$slug('integrations'),      [PageController::class, 'integrations'])->name('integrations');
        Route::get('/'.$slug('pricing'),           [PageController::class, 'pricing'])->name('pricing');
        Route::get('/'.$slug('aurius'),            [PageController::class, 'aurius'])->name('aurius');
        Route::get('/'.$slug('security'),          [PageController::class, 'security'])->name('security');
        Route::get('/'.$slug('about'),             [PageController::class, 'about'])->name('about');
        Route::get('/'.$slug('help'),              [PageController::class, 'help'])->name('help');
        Route::get('/'.$slug('contact'),           [PageController::class, 'contact'])->name('contact');
        Route::get('/'.$slug('privacy'),           [PageController::class, 'privacy'])->name('privacy');
        Route::get('/'.$slug('terms'),             [PageController::class, 'terms'])->name('terms');
        Route::get('/'.$slug('cookies'),           [PageController::class, 'cookies'])->name('cookies');
        Route::get('/'.$slug('api'),               [PageController::class, 'api'])->name('api');

        Route::get('/'.$slug('for.developers'),    [PageController::class, 'forDevelopers'])->name('for.developers');
        Route::get('/'.$slug('for.saas'),          [PageController::class, 'forSaas'])->name('for.saas');
        Route::get('/'.$slug('for.agencies'),      [PageController::class, 'forAgencies'])->name('for.agencies');
        Route::get('/'.$slug('for.ecommerce'),     [PageController::class, 'forEcommerce'])->name('for.ecommerce');
        Route::get('/'.$slug('for.newsletters'),   [PageController::class, 'forNewsletters'])->name('for.newsletters');
        Route::get('/'.$slug('for.enterprise'),    [PageController::class, 'forEnterprise'])->name('for.enterprise');

        Route::get('/'.$slug('guide.self-hosted'),    [PageController::class, 'guideSelfHosted'])->name('guide.self-hosted');
        Route::get('/'.$slug('guide.cost-savings'),   [PageController::class, 'guideCostSavings'])->name('guide.cost-savings');
        Route::get('/'.$slug('guide.deliverability'), [PageController::class, 'guideDeliverability'])->name('guide.deliverability');

        Route::get('/'.$slug('developers.index'),                [PageController::class, 'developersIndex'])->name('developers.index');
        Route::get('/'.$slug('developers.getting-started'),      [PageController::class, 'developersGettingStarted'])->name('developers.getting-started');
        Route::get('/'.$slug('developers.plugin-architecture'),  [PageController::class, 'developersPluginArchitecture'])->name('developers.plugin-architecture');
        Route::get('/'.$slug('developers.hook-system'),          [PageController::class, 'developersHookSystem'])->name('developers.hook-system');
        Route::get('/'.$slug('developers.ui-injection'),         [PageController::class, 'developersUiInjection'])->name('developers.ui-injection');
        Route::get('/'.$slug('developers.database-models'),      [PageController::class, 'developersDatabaseModels'])->name('developers.database-models');
        Route::get('/'.$slug('developers.translations'),         [PageController::class, 'developersTranslations'])->name('developers.translations');
        Route::get('/'.$slug('developers.lifecycle'),            [PageController::class, 'developersLifecycle'])->name('developers.lifecycle');
        Route::get('/'.$slug('developers.testing'),              [PageController::class, 'developersTesting'])->name('developers.testing');
        Route::get('/'.$slug('developers.sending-drivers'),      [PageController::class, 'developersSendingDrivers'])->name('developers.sending-drivers');
        Route::get('/'.$slug('developers.payment-gateways'),     [PageController::class, 'developersPaymentGateways'])->name('developers.payment-gateways');
        Route::get('/'.$slug('developers.showcase'),             [PageController::class, 'developersShowcase'])->name('developers.showcase');

        Route::get('/'.$slug('compare.show').'/{slug}', [CompareController::class, 'show'])
            ->where('slug', '[a-z0-9-]+')->name('compare.show');

        Route::get('/'.$slug('glossary.index'),                  [GlossaryController::class, 'index'])->name('glossary.index');
        Route::get('/'.$slug('glossary.show').'/{slug}',         [GlossaryController::class, 'show'])
            ->where('slug', '[a-z0-9-]+')->name('glossary.show');

        Route::get('/'.$slug('blog.index'),                      [BlogController::class, 'index'])->name('blog.index');
        Route::get('/'.$slug('blog.rss'),                        [BlogController::class, 'rss'])->name('blog.rss');
        Route::get('/'.$slug('blog.show').'/{slug}',             [BlogController::class, 'show'])
            ->where('slug', '[a-z0-9-]+')->name('blog.show');

        $kbBase = $slug('kb.index');
        Route::prefix($kbBase)->name('kb.')->group(function () use ($slug, $kbBase) {
            Route::get('/',                              [KbArticleController::class, 'index'])->name('index');
            $searchTail   = ltrim(str_replace($kbBase.'/', '', $slug('kb.search')), '/');
            $categoryTail = ltrim(str_replace($kbBase.'/', '', $slug('kb.category')), '/');
            $tagTail      = ltrim(str_replace($kbBase.'/', '', $slug('kb.tag')), '/');
            $articleTail  = ltrim(str_replace($kbBase.'/', '', $slug('kb.articles.show')), '/');
            Route::get('/'.$searchTail,                  [KbArticleController::class, 'search'])->name('search');
            Route::get('/'.$categoryTail.'/{slug}',      [KbArticleController::class, 'category'])
                ->where('slug', '[a-z0-9-]+')->name('category');
            Route::get('/'.$tagTail.'/{slug}',           [KbArticleController::class, 'tag'])
                ->where('slug', '[a-z0-9-]+')->name('tag');
            Route::get('/'.$articleTail.'/{slug}',       [KbArticleController::class, 'show'])
                ->where('slug', '[a-z0-9-]+')->name('articles.show');
        });
    });
}
