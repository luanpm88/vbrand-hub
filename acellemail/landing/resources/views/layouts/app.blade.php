<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="@yield('meta_description', 'AcelleMail — Self-hosted email marketing platform. Full source code, no recurring fees. Send unlimited emails with any SMTP service.')">
  <meta name="theme-color" content="#241C15">
  <title>@yield('title', 'AcelleMail | Self-Hosted Email Marketing Platform')</title>
  <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

  <!-- Google Fonts: Fraunces (display heading) + IBM Plex Sans (body) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300;9..144,400;9..144,500;9..144,600;9..144,700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Styles -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  @stack('styles')
</head>
<body>

@include('partials.promo-banner')
@include('partials.header')
@include('partials.mobile-nav')

<main>
@yield('content')
</main>

@include('partials.footer')

<!-- Scripts -->
<script src="{{ asset('js/script.js') }}"></script>
@stack('scripts')
</body>
</html>
