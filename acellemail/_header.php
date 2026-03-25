<?php
/**
 * AcelleMail — Shared Header
 *
 * Usage: Set $current_page before including this file.
 *   $current_page = 'home'; // home, features, email-marketing, automation, integrations, pricing, security, about, help, contact
 *   include '_header.php';
 */

if (!isset($current_page)) {
    $current_page = '';
}

// Build relative path to assets based on file location
if (!isset($base_path)) {
    $base_path = '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="AcelleMail — Self-hosted email marketing platform. Full source code, no recurring fees. Send unlimited emails with any SMTP service.">
  <meta name="theme-color" content="#241C15">
  <title>AcelleMail | Self-Hosted Email Marketing Platform</title>

  <!-- Google Fonts: Fraunces (display heading) + IBM Plex Sans (body) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,300;9..144,400;9..144,500;9..144,600;9..144,700&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Styles -->
  <link rel="stylesheet" href="<?php echo $base_path; ?>css/style.css">
</head>
<body>

<!-- ======================================================================
     PROMO BANNER
     ====================================================================== -->
<div class="mc-promo-banner" id="promoBanner">
  <div class="mc-container">
    <p class="mc-promo-banner__text">
      Get AcelleMail — $64 one-time purchase, full source code included.
      <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" class="mc-promo-banner__link" target="_blank">Buy on CodeCanyon</a>
    </p>
  </div>
  <button class="mc-promo-banner__close" aria-label="Close promotional banner" onclick="document.getElementById('promoBanner').style.display='none'">
    &times;
  </button>
</div>

<!-- ======================================================================
     MAIN NAVIGATION
     ====================================================================== -->
<header class="mc-header" id="mainHeader">
  <div class="mc-container">
    <div class="mc-header__inner">
      <div class="mc-header__left">
        <!-- Logo -->
        <a href="index.php" class="mc-header__logo" aria-label="AcelleMail Home">
          <img src="<?php echo $base_path; ?>images/logo_dark.svg" alt="AcelleMail" width="160" height="32">
        </a>

        <!-- Desktop Navigation -->
        <nav class="mc-header__nav" aria-label="Main navigation">
          <a href="index.php" class="mc-header__nav-link<?php echo $current_page === 'home' ? ' mc-header__nav-link--active' : ''; ?>">Home</a>
          <a href="features.php" class="mc-header__nav-link<?php echo $current_page === 'features' ? ' mc-header__nav-link--active' : ''; ?>">Features</a>
          <a href="email-marketing.php" class="mc-header__nav-link<?php echo $current_page === 'email-marketing' ? ' mc-header__nav-link--active' : ''; ?>">Email</a>
          <a href="automation.php" class="mc-header__nav-link<?php echo $current_page === 'automation' ? ' mc-header__nav-link--active' : ''; ?>">Automation</a>
          <a href="integrations.php" class="mc-header__nav-link<?php echo $current_page === 'integrations' ? ' mc-header__nav-link--active' : ''; ?>">Integrations</a>
          <a href="pricing.php" class="mc-header__nav-link<?php echo $current_page === 'pricing' ? ' mc-header__nav-link--active' : ''; ?>">Pricing</a>
          <a href="security.php" class="mc-header__nav-link<?php echo $current_page === 'security' ? ' mc-header__nav-link--active' : ''; ?>">Security</a>
          <a href="about.php" class="mc-header__nav-link<?php echo $current_page === 'about' ? ' mc-header__nav-link--active' : ''; ?>">About</a>
          <a href="help.php" class="mc-header__nav-link<?php echo $current_page === 'help' ? ' mc-header__nav-link--active' : ''; ?>">Help</a>
          <a href="contact.php" class="mc-header__nav-link<?php echo $current_page === 'contact' ? ' mc-header__nav-link--active' : ''; ?>">Contact</a>
        </nav>
      </div>

      <div class="mc-header__right">
        <!-- Demo Login -->
        <a href="https://demo.acellemail.com" class="mc-btn mc-btn--secondary mc-btn--sm mc-header__login" target="_blank">Try Demo</a>

        <!-- CTA Button -->
        <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" class="mc-btn mc-btn--primary mc-header__cta" target="_blank">Buy Now</a>

        <!-- Mobile Hamburger -->
        <button class="mc-header__hamburger" id="hamburgerBtn" aria-label="Open menu" aria-expanded="false">
          <span></span>
          <span></span>
          <span></span>
        </button>
      </div>
    </div>
  </div>
</header>

<!-- ======================================================================
     MOBILE NAV OVERLAY
     ====================================================================== -->
<div class="mc-mobile-nav" id="mobileNav" aria-hidden="true">
  <button class="mc-mobile-nav__close" id="mobileNavClose" aria-label="Close menu">&times;</button>

  <nav class="mc-mobile-nav__links" aria-label="Mobile navigation">
    <a href="features.php" class="mc-mobile-nav__link">
      Features
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 4 10 8 6 12"/></svg>
    </a>
    <a href="email-marketing.php" class="mc-mobile-nav__link">
      Email Marketing
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 4 10 8 6 12"/></svg>
    </a>
    <a href="automation.php" class="mc-mobile-nav__link">
      Automation
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 4 10 8 6 12"/></svg>
    </a>
    <a href="integrations.php" class="mc-mobile-nav__link">
      Integrations
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 4 10 8 6 12"/></svg>
    </a>
    <a href="pricing.php" class="mc-mobile-nav__link">
      Pricing
    </a>
    <a href="security.php" class="mc-mobile-nav__link">
      Security
    </a>
    <a href="help.php" class="mc-mobile-nav__link">
      Help
    </a>
    <a href="about.php" class="mc-mobile-nav__link">
      About
    </a>
    <a href="contact.php" class="mc-mobile-nav__link">
      Contact
    </a>
  </nav>

  <div class="mc-mobile-nav__actions">
    <a href="https://codecanyon.net/item/acelle-email-marketing-web-application/17796082" class="mc-btn mc-btn--primary mc-btn--full" target="_blank">Buy Now</a>
    <a href="https://demo.acellemail.com" class="mc-btn mc-btn--secondary mc-btn--full" target="_blank">Try Demo</a>
  </div>
</div>

<main>
