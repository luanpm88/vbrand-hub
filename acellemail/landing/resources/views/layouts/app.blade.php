@php
  $appLocale = app()->getLocale();
  $localeDef = config('i18n.locales.'.$appLocale, config('i18n.locales.en'));
@endphp
<!DOCTYPE html>
<html lang="{{ $localeDef['html_lang'] ?? 'en' }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="@yield('meta_description', 'AcelleMail — Self-hosted email marketing platform. Full source code, no recurring fees. Send unlimited emails with any SMTP service.')">
  <meta name="robots" content="@yield('robots', 'index, follow')">
  <meta name="theme-color" content="{{ config('landing.theme_color', '#241C15') }}">
  {{-- CSRF token for AJAX requests that aren't already inside a <form>
       carrying @csrf (e.g., CTA fire-and-forget tracker in script.js). --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'AcelleMail | Self-Hosted Email Marketing Platform')</title>
  <link rel="canonical" href="@yield('canonical_url', url()->current())">
  @include('partials.seo.hreflang')
  {{-- Blog RSS feed (SEO_PLAN.md §4.4 wave 22) — advertised on every page so
       readers + feed readers + crawlers can discover it from anywhere. --}}
  <link rel="alternate" type="application/rss+xml" title="AcelleMail Blog" href="{{ url('/blog/rss.xml') }}">
  @php $themeSuffix = config('landing.asset_suffix', ''); @endphp
  <link rel="icon" href="{{ asset($themeSuffix ? 'favicon'.$themeSuffix.'.svg' : 'favicon.svg') }}" type="image/svg+xml">

  <!-- Open Graph -->
  <meta property="og:type" content="@yield('og_type', 'website')">
  <meta property="og:site_name" content="AcelleMail">
  <meta property="og:title" content="@yield('og_title', 'AcelleMail — Self-Hosted Email Marketing Platform')">
  <meta property="og:description" content="@yield('meta_description', 'AcelleMail — Self-hosted email marketing platform. Full source code, no recurring fees. Send unlimited emails with any SMTP service.')">
  <meta property="og:url" content="@yield('canonical_url', url()->current())">
  <meta property="og:image" content="@yield('og_image', $themeImg('images/og/og-default.svg'))">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:locale" content="{{ $localeDef['og_locale'] ?? 'en_US' }}">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="@yield('og_title', 'AcelleMail — Self-Hosted Email Marketing Platform')">
  <meta name="twitter:description" content="@yield('meta_description', 'AcelleMail — Self-hosted email marketing platform. Full source code, no recurring fees. Send unlimited emails with any SMTP service.')">
  <meta name="twitter:image" content="@yield('og_image', $themeImg('images/og/og-default.svg'))">

  <!-- Structured Data -->
  @stack('jsonld')

  {{-- Analytics — emits gtag.js only when config('landing.ga_id') is set.
       Loads async so it never blocks the critical CSS / font path below. --}}
  @include('partials.analytics')

  <!-- DNS-prefetch for click-through destinations + asset hosts. Warms the
       connection during landing-page idle time so click-to-CodeCanyon/demo
       feels instant. See SEO_PLAN.md §3.4. -->
  <link rel="dns-prefetch" href="https://codecanyon.net">
  <link rel="dns-prefetch" href="https://demo.acellemail.com">
  <link rel="dns-prefetch" href="/kb">
  <link rel="dns-prefetch" href="https://www.youtube-nocookie.com">
  <link rel="dns-prefetch" href="https://i.ytimg.com">

  <!-- Preconnect for cross-origin assets we know we'll fetch this paint:
       YouTube (only on home, where videos are embedded). Fonts are now
       self-hosted (see <style> block below) so no fonts.googleapis.com
       preconnect needed — saves a third-party round-trip on every page. -->
  @if(Route::is('home'))
  <link rel="preconnect" href="https://www.youtube-nocookie.com">
  <link rel="preconnect" href="https://i.ytimg.com" crossorigin>
  @endif

  <!-- Self-hosted fonts (variable WOFF2, ~50KB each, all weights from 1 file).
       Inter is the active font under theme-pleo; Fraunces + IBM Plex Sans
       are kept for theme-orange/blue/teal so theme switching still works.
       Each family declares TWO @font-face blocks discriminated by
       unicode-range — `latin` (Wave 1) + `vietnamese` (Wave 3). The browser
       picks the correct file per glyph; without the Vietnamese subset the
       diacritic-rich code points (U+1EA0–U+1EF9 ặ ậ ử ữ ự ỗ ổ ỹ etc.) fall
       back to system fonts and render with broken combining marks.
       See SEO_PLAN.md §3.3. Subset ranges mirror Google Fonts' standard
       split so the woff2 files we host are byte-identical to upstream. -->
  <link rel="preload" as="font" type="font/woff2" crossorigin
        href="{{ asset('fonts/inter-latin-variable.woff2') }}">
  @if(in_array(app()->getLocale(), ['vi'], true))
  {{-- VI pages: also preload the Vietnamese subset so the diacritic glyphs
       paint without a swap flash. EN/JA pages skip this — no Vietnamese
       characters on the page, no need to fetch the extra 12 KB. --}}
  <link rel="preload" as="font" type="font/woff2" crossorigin
        href="{{ asset('fonts/inter-vietnamese-variable.woff2') }}">
  <link rel="preload" as="font" type="font/woff2" crossorigin
        href="{{ asset('fonts/fraunces-vietnamese-variable.woff2') }}">
  @endif
  <style>
    /* Inter — latin */
    @font-face {
      font-family: 'Inter';
      font-style: normal;
      font-weight: 100 900;
      font-display: swap;
      src: url('{{ asset('fonts/inter-latin-variable.woff2') }}') format('woff2-variations'),
           url('{{ asset('fonts/inter-latin-variable.woff2') }}') format('woff2');
    }
    /* Inter — vietnamese (PLAN §14 Wave 3) */
    @font-face {
      font-family: 'Inter';
      font-style: normal;
      font-weight: 100 900;
      font-display: swap;
      src: url('{{ asset('fonts/inter-vietnamese-variable.woff2') }}') format('woff2');
      unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
    }
    /* Fraunces — latin */
    @font-face {
      font-family: 'Fraunces';
      font-style: normal;
      font-weight: 100 900;
      font-display: swap;
      src: url('{{ asset('fonts/fraunces-latin-variable.woff2') }}') format('woff2-variations'),
           url('{{ asset('fonts/fraunces-latin-variable.woff2') }}') format('woff2');
    }
    /* Fraunces — vietnamese (PLAN §14 Wave 3) */
    @font-face {
      font-family: 'Fraunces';
      font-style: normal;
      font-weight: 100 900;
      font-display: swap;
      src: url('{{ asset('fonts/fraunces-vietnamese-variable.woff2') }}') format('woff2');
      unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
    }
    /* IBM Plex Sans — latin */
    @font-face {
      font-family: 'IBM Plex Sans';
      font-style: normal;
      font-weight: 100 900;
      font-display: swap;
      src: url('{{ asset('fonts/plex-latin-400.woff2') }}') format('woff2-variations'),
           url('{{ asset('fonts/plex-latin-400.woff2') }}') format('woff2');
    }
    /* IBM Plex Sans — vietnamese (PLAN §14 Wave 3) */
    @font-face {
      font-family: 'IBM Plex Sans';
      font-style: normal;
      font-weight: 100 900;
      font-display: swap;
      src: url('{{ asset('fonts/plex-vietnamese-400.woff2') }}') format('woff2');
      unicode-range: U+0102-0103, U+0110-0111, U+0128-0129, U+0168-0169, U+01A0-01A1, U+01AF-01B0, U+0300-0301, U+0303-0304, U+0308-0309, U+0323, U+0329, U+1EA0-1EF9, U+20AB;
    }
  </style>

  @php
    // Cache-bust by mtime — pairs with nginx's "max-age=31536000, immutable"
    // (per SEO_PLAN.md §2.3). When CSS/JS changes, mtime changes, browsers
    // refetch. Falls back to '*.css' (raw) if '*.min.css' doesn't exist
    // — keeps local dev working without running `php artisan assets:minify`.
    $useMin = file_exists(public_path('css/style.min.css'));
    $cssBase  = $useMin ? 'css/style.min.css'        : 'css/style.css';
    $themeName = config('landing.theme', 'theme-orange');
    $cssTheme = $useMin
      ? 'css/' . $themeName . '.min.css'
      : 'css/' . $themeName . '.css';
    $jsMain   = $useMin ? 'js/script.min.js' : 'js/script.js';
    $vBase  = file_exists(public_path($cssBase))  ? filemtime(public_path($cssBase))  : 1;
    $vTheme = file_exists(public_path($cssTheme)) ? filemtime(public_path($cssTheme)) : 1;
    $vJs    = file_exists(public_path($jsMain))   ? filemtime(public_path($jsMain))   : 1;

    // Critical CSS — per-theme inline bundle so above-the-fold renders
    // without waiting for the main stylesheet. Built by `php artisan
    // assets:critical` (called automatically by `assets:build`).
    // Falls back to raw critical-base.css when the per-theme bundle is
    // missing (fresh checkout before first build). See SEO_PLAN.md §9.2.
    $criticalPath = public_path("css/critical-{$themeName}.min.css");
    $criticalCss = file_exists($criticalPath)
      ? file_get_contents($criticalPath)
      : (file_exists(public_path('css/critical-base.css'))
          ? file_get_contents(public_path('css/critical-base.css'))
          : '');
  @endphp
  @if($criticalCss !== '')
  {{-- Inline critical CSS — paints header/promo/hero before the network
       stylesheet arrives. <14 KB so it fits in 1 TCP slow-start packet. --}}
  <style id="mc-critical-css">{!! $criticalCss !!}</style>
  @endif
  {{-- Theme tokens + main stylesheet — async via preload→onload (loadCSS
       pattern). Browsers without JS or with the rel-preload feature behind
       a flag fall back to the <noscript> blocking <link>. --}}
  <link rel="preload" as="style" href="{{ asset($cssTheme) }}?v={{ $vTheme }}" onload="this.onload=null;this.rel='stylesheet'">
  <link rel="preload" as="style" href="{{ asset($cssBase) }}?v={{ $vBase }}"  onload="this.onload=null;this.rel='stylesheet'">
  <noscript>
    <link rel="stylesheet" href="{{ asset($cssTheme) }}?v={{ $vTheme }}">
    <link rel="stylesheet" href="{{ asset($cssBase) }}?v={{ $vBase }}">
  </noscript>
  @php
    // Wave 24: lite-youtube-embed assets — only loaded on pages with embeds
    // (home + features). The custom element registers <lite-youtube>.
    // Saves ~500 KB of YouTube iframe payload per video on first paint.
    $useLiteYt = in_array(($currentPage ?? null), ['home', 'features'], true);
    if ($useLiteYt) {
      $liteYtCss = $useMin && file_exists(public_path('css/lite-yt-embed.min.css'))
        ? 'css/lite-yt-embed.min.css' : 'css/lite-yt-embed.css';
      $liteYtJs  = $useMin && file_exists(public_path('js/lite-yt-embed.min.js'))
        ? 'js/lite-yt-embed.min.js'  : 'js/lite-yt-embed.js';
      $vLiteCss = file_exists(public_path($liteYtCss)) ? filemtime(public_path($liteYtCss)) : 1;
      $vLiteJs  = file_exists(public_path($liteYtJs))  ? filemtime(public_path($liteYtJs))  : 1;
    }
  @endphp
  @if($useLiteYt)
    <link rel="preload" as="style" href="{{ asset($liteYtCss) }}?v={{ $vLiteCss }}" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset($liteYtCss) }}?v={{ $vLiteCss }}"></noscript>
  @endif
  @stack('styles')
</head>
<body>

@include('partials.topbar')
@include('partials.header')
@include('partials.mobile-nav')

<main>
@yield('content')
</main>

@include('partials.footer')

{{-- Auth modal — rendered once globally so any page can open it. The
     component itself is gated by @guest, so authenticated users emit
     nothing. Triggers anywhere on the page use [data-auth-modal-open]. --}}
<x-auth.modal />

<!-- Scripts -->
@php
  // i18n locale metadata for client-side switcher + Accept-Language banner.
  // Emits only enabled locales + their alt-page URLs (computed via
  // HreflangRegistry's switcher fallback rule). Server NEVER varies its
  // response by Accept-Language — banner display is purely client-side
  // (PLAN §11 lock #12, Q#3 — resolved 2026-05-15).
  $i18nClientLocales = [];
  $i18nCurrentRouteName = \Illuminate\Support\Facades\Route::currentRouteName();
  // Strip locale prefix if present (vi.features → features).
  foreach (config('i18n.locales', []) as $_lk => $_def) {
      $_p = $_lk . '.';
      if ($i18nCurrentRouteName && str_starts_with($i18nCurrentRouteName, $_p)) {
          $i18nCurrentRouteName = substr($i18nCurrentRouteName, strlen($_p));
          break;
      }
  }
  $_route = \Illuminate\Support\Facades\Route::current();
  $_params = $_route ? array_filter($_route->parameters(), fn($k) => $k !== 'locale', ARRAY_FILTER_USE_KEY) : [];
  foreach (config('i18n.locales', []) as $_lk => $_def) {
      if (! ($_def['enabled'] ?? false)) continue;
      $i18nClientLocales[] = [
          'locale'      => $_lk,
          'native'      => $_def['native'],
          'iso639'      => $_def['iso639'] ?? $_lk,
          'enabled'     => true,
          'url'         => \App\Helpers\LocaleRoute::url($i18nCurrentRouteName ?? 'home', $_lk, $_params),
          'al_question' => trans('banner.al_question', ['native' => $_def['native']], $_lk),
          'al_yes'      => trans('banner.al_yes', ['native' => $_def['native']], $_lk),
          'al_no'       => trans('banner.al_no', [], 'en'),
          'al_close'    => trans('banner.al_close', [], 'en'),
      ];
  }
@endphp
<script>window.__i18nLocales = @json($i18nClientLocales);</script>
<script src="{{ asset($jsMain) }}?v={{ $vJs }}"></script>
@if($useLiteYt ?? false)
<script defer src="{{ asset($liteYtJs) }}?v={{ $vLiteJs }}"></script>
@endif
@stack('scripts')
</body>
</html>
