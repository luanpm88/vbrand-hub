/**
 * Mailchimp Clone — Shared JavaScript
 * Handles: hamburger menu, sticky header, FAQ accordion, scroll reveal
 */

(function () {
  'use strict';

  // ========================================================================
  // MOBILE NAV
  // ========================================================================

  var hamburger = document.getElementById('hamburgerBtn');
  var mobileNav = document.getElementById('mobileNav');
  var mobileNavClose = document.getElementById('mobileNavClose');

  function openMobileNav() {
    if (!mobileNav) return;
    mobileNav.classList.add('is-open');
    mobileNav.setAttribute('aria-hidden', 'false');
    if (hamburger) {
      hamburger.classList.add('is-active');
      hamburger.setAttribute('aria-expanded', 'true');
    }
    document.body.style.overflow = 'hidden';
  }

  function closeMobileNav() {
    if (!mobileNav) return;
    mobileNav.classList.remove('is-open');
    mobileNav.setAttribute('aria-hidden', 'true');
    if (hamburger) {
      hamburger.classList.remove('is-active');
      hamburger.setAttribute('aria-expanded', 'false');
    }
    document.body.style.overflow = '';
  }

  if (hamburger) {
    hamburger.addEventListener('click', function () {
      var isOpen = mobileNav && mobileNav.classList.contains('is-open');
      if (isOpen) {
        closeMobileNav();
      } else {
        openMobileNav();
      }
    });
  }

  if (mobileNavClose) {
    mobileNavClose.addEventListener('click', closeMobileNav);
  }

  // Close mobile nav on Escape key
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && mobileNav && mobileNav.classList.contains('is-open')) {
      closeMobileNav();
    }
  });

  // ========================================================================
  // STICKY HEADER — add shadow on scroll
  // ========================================================================

  var header = document.getElementById('mainHeader');

  if (header) {
    var lastScroll = 0;

    window.addEventListener('scroll', function () {
      var scrollY = window.pageYOffset || document.documentElement.scrollTop;

      if (scrollY > 10) {
        header.classList.add('mc-header--scrolled');
      } else {
        header.classList.remove('mc-header--scrolled');
      }

      lastScroll = scrollY;
    }, { passive: true });
  }

  // ========================================================================
  // FAQ ACCORDION
  // ========================================================================

  var faqItems = document.querySelectorAll('.mc-faq__item');

  faqItems.forEach(function (item) {
    var question = item.querySelector('.mc-faq__question');
    if (!question) return;

    question.addEventListener('click', function () {
      var isOpen = item.classList.contains('is-open');

      // Close all other items (single-open mode)
      faqItems.forEach(function (other) {
        if (other !== item) {
          other.classList.remove('is-open');
        }
      });

      // Toggle current
      if (isOpen) {
        item.classList.remove('is-open');
      } else {
        item.classList.add('is-open');
      }
    });
  });

  // ========================================================================
  // SCROLL REVEAL — IntersectionObserver
  // ========================================================================

  var reveals = document.querySelectorAll('.mc-reveal');

  if (reveals.length > 0 && 'IntersectionObserver' in window) {
    var observer = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.1,
      rootMargin: '0px 0px -40px 0px'
    });

    reveals.forEach(function (el) {
      observer.observe(el);
    });
  } else {
    // Fallback: show everything if IntersectionObserver not supported
    reveals.forEach(function (el) {
      el.classList.add('is-visible');
    });
  }

})();
