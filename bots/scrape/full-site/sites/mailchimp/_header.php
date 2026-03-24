<?php
/**
 * Mailchimp Clone — Shared Header
 *
 * Usage: Set $current_page before including this file.
 *   $current_page = 'home'; // home, products, solutions, resources, pricing, about, help, automations, integrations
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
  <meta name="description" content="Mailchimp — Turn Emails into Revenue. Win new customers with the #1 email marketing and automations platform.">
  <meta name="theme-color" content="#FFE01B">
  <title>Mailchimp | Email Marketing, Automation &amp; More</title>

  <!-- Preconnect for performance -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:ital,opsz,wght@0,8..60,200..900;1,8..60,200..900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <!-- Styles -->
  <link rel="stylesheet" href="<?php echo $base_path; ?>css/style.css">
</head>
<body>

<!-- ======================================================================
     INTUIT FAMILY BAR
     ====================================================================== -->
<div class="mc-intuit-bar">
  <div class="mc-container">
    <div class="mc-intuit-bar__inner">
      <span class="mc-intuit-bar__logo">Intuit</span>
      <span class="mc-intuit-bar__sep">|</span>
      <nav class="mc-intuit-bar__links" aria-label="Intuit family">
        <a href="#" class="mc-intuit-bar__link">TurboTax</a>
        <a href="#" class="mc-intuit-bar__link mc-intuit-bar__link--dot">Credit Karma</a>
        <a href="#" class="mc-intuit-bar__link mc-intuit-bar__link--dot">QuickBooks</a>
        <a href="#" class="mc-intuit-bar__link mc-intuit-bar__link--dot mc-intuit-bar__link--active">Mailchimp</a>
      </nav>
    </div>
  </div>
</div>

<!-- ======================================================================
     PROMO BANNER
     ====================================================================== -->
<div class="mc-promo-banner" id="promoBanner">
  <div class="mc-container">
    <p class="mc-promo-banner__text">
      Join Mailchimp with a free 14-day trial or save 15% off on 10,000+ contacts.
      <a href="pricing.php" class="mc-promo-banner__link">Start Today</a>
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
        <a href="index.php" class="mc-header__logo" aria-label="Mailchimp Home">
          <img src="<?php echo $base_path; ?>images/logo-wordmark.png" alt="Mailchimp" width="140" height="28">
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
        <!-- Login -->
        <a href="#" class="mc-header__login">Log In</a>

        <!-- CTA Button -->
        <a href="pricing.php" class="mc-btn mc-btn--primary mc-header__cta">Start Free Trial</a>

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
      Products
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 4 10 8 6 12"/></svg>
    </a>
    <a href="email-marketing.php" class="mc-mobile-nav__link">
      Email Marketing
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 4 10 8 6 12"/></svg>
    </a>
    <a href="automation.php" class="mc-mobile-nav__link">
      Automations
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
    <a href="pricing.php" class="mc-btn mc-btn--primary mc-btn--full">Start Free Trial</a>
    <a href="#" class="mc-btn mc-btn--secondary mc-btn--full">Log In</a>
  </div>
</div>

<main>
