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

  // Close mobile nav on Escape key (also closes desktop dropdowns)
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      if (mobileNav && mobileNav.classList.contains('is-open')) {
        closeMobileNav();
      }
      closeAllDropdowns();
    }
  });

  // ========================================================================
  // DESKTOP DROPDOWNS
  // ========================================================================

  var dropdowns = document.querySelectorAll('[data-dropdown]');
  var hoverTimeout = null;
  var HOVER_DELAY = 80;

  function openDropdown(dropdown) {
    dropdowns.forEach(function (other) {
      if (other !== dropdown) closeDropdown(other);
    });
    dropdown.classList.add('is-open');
    var trigger = dropdown.querySelector('.mc-header__dropdown-trigger');
    if (trigger) trigger.setAttribute('aria-expanded', 'true');
  }

  function closeDropdown(dropdown) {
    dropdown.classList.remove('is-open');
    var trigger = dropdown.querySelector('.mc-header__dropdown-trigger');
    if (trigger) trigger.setAttribute('aria-expanded', 'false');
  }

  function closeAllDropdowns() {
    dropdowns.forEach(function (d) { closeDropdown(d); });
  }

  dropdowns.forEach(function (dropdown) {
    var trigger = dropdown.querySelector('.mc-header__dropdown-trigger');

    // Hover open/close with delay
    dropdown.addEventListener('mouseenter', function () {
      clearTimeout(hoverTimeout);
      hoverTimeout = setTimeout(function () { openDropdown(dropdown); }, HOVER_DELAY);
    });

    dropdown.addEventListener('mouseleave', function () {
      clearTimeout(hoverTimeout);
      hoverTimeout = setTimeout(function () { closeDropdown(dropdown); }, HOVER_DELAY);
    });

    // Click toggle (touch fallback)
    if (trigger) {
      trigger.addEventListener('click', function (e) {
        e.preventDefault();
        if (dropdown.classList.contains('is-open')) {
          closeDropdown(dropdown);
        } else {
          openDropdown(dropdown);
        }
      });

      // Keyboard: Enter/Space toggle, Escape close
      trigger.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          if (dropdown.classList.contains('is-open')) {
            closeDropdown(dropdown);
          } else {
            openDropdown(dropdown);
          }
        }
      });
    }

    // Arrow key navigation within panel
    dropdown.addEventListener('keydown', function (e) {
      if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
        e.preventDefault();
        var items = dropdown.querySelectorAll('.mc-header__dropdown-item');
        var currentIndex = Array.prototype.indexOf.call(items, document.activeElement);
        var next;
        if (e.key === 'ArrowDown') {
          next = currentIndex + 1 < items.length ? currentIndex + 1 : 0;
        } else {
          next = currentIndex - 1 >= 0 ? currentIndex - 1 : items.length - 1;
        }
        items[next].focus();
      }
    });
  });

  // Close dropdowns on outside click
  document.addEventListener('click', function (e) {
    if (!e.target.closest('[data-dropdown]')) {
      closeAllDropdowns();
    }
  });

  // ========================================================================
  // MOBILE NAV ACCORDION
  // ========================================================================

  var mobileGroups = document.querySelectorAll('[data-mobile-group]');

  mobileGroups.forEach(function (group) {
    var trigger = group.querySelector('.mc-mobile-nav__group-trigger');
    if (!trigger) return;

    trigger.addEventListener('click', function () {
      var isOpen = group.classList.contains('is-open');

      // Close all other groups (single-open mode)
      mobileGroups.forEach(function (other) {
        if (other !== group) {
          other.classList.remove('is-open');
          var otherTrigger = other.querySelector('.mc-mobile-nav__group-trigger');
          if (otherTrigger) otherTrigger.setAttribute('aria-expanded', 'false');
        }
      });

      if (isOpen) {
        group.classList.remove('is-open');
        trigger.setAttribute('aria-expanded', 'false');
      } else {
        group.classList.add('is-open');
        trigger.setAttribute('aria-expanded', 'true');
      }
    });
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
