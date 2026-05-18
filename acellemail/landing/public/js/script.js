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

    // Click toggle (touch fallback). For anchor triggers (mega-menu parent
    // links — Features/Developers), click should navigate normally. Hover
    // + focus-within still open the panel via the listeners above.
    if (trigger) {
      var isAnchorTrigger = trigger.tagName === 'A' && trigger.hasAttribute('href');

      trigger.addEventListener('click', function (e) {
        if (isAnchorTrigger) {
          // Let the anchor follow its href. Don't trap the click.
          return;
        }
        e.preventDefault();
        if (dropdown.classList.contains('is-open')) {
          closeDropdown(dropdown);
        } else {
          openDropdown(dropdown);
        }
      });

      // Keyboard: Enter on a button = toggle; Enter on an anchor = navigate.
      // Space always toggles (Space on anchor wouldn't navigate by default).
      trigger.addEventListener('keydown', function (e) {
        if (e.key === ' ' || (e.key === 'Enter' && !isAnchorTrigger)) {
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


  // ========================================================================
  // DEVELOPERS DOC PAGES — sticky-TOC scroll-spy + table overflow wrap
  // ------------------------------------------------------------------------
  // Every /developers/<slug> page extends layouts/app and uses the shared
  // <x-developers.doc-page> component. The component emits a sticky TOC
  // <aside data-doc-toc> and an article <article data-doc-content> with
  // h2/h3 headings whose ids match the TOC link href targets. This block
  // does two things on those pages:
  //
  //   1. Wraps every <table> in .mc-developers-doc__table-wrap so wide
  //      schema tables get a horizontal scroll instead of pushing the
  //      article past the viewport on narrow screens.
  //
  //   2. Highlights the TOC entry whose target is currently the topmost
  //      visible heading in the article. Uses IntersectionObserver with a
  //      "look only at the top 30% of the viewport" rootMargin so the
  //      highlight follows the heading the reader is on, not whatever's
  //      mid-screen. Click handler also flags the clicked entry active
  //      immediately so the highlight responds to the user's intent
  //      before the smooth-scroll animation finishes.
  // ========================================================================

  var docToc = document.querySelector('[data-doc-toc]');
  var docContent = document.querySelector('[data-doc-content]');

  if (docToc && docContent) {

    // -- 1. Table overflow wrap (idempotent) --
    Array.prototype.forEach.call(docContent.querySelectorAll('table'), function (table) {
      if (table.parentNode && table.parentNode.classList.contains('mc-developers-doc__table-wrap')) {
        return; // already wrapped
      }
      var wrap = document.createElement('div');
      wrap.className = 'mc-developers-doc__table-wrap';
      table.parentNode.insertBefore(wrap, table);
      wrap.appendChild(table);
    });

    // -- 2. Scroll-spy --
    var tocLinks = Array.prototype.slice.call(docToc.querySelectorAll('[data-toc-target]'));
    if (tocLinks.length && 'IntersectionObserver' in window) {
      // Map each id → its TOC link for O(1) lookup.
      var linksById = {};
      tocLinks.forEach(function (link) {
        linksById[link.getAttribute('data-toc-target')] = link;
      });

      // Resolve every heading element once, in TOC order. Keeping the
      // ordered list lets us answer "which heading has been scrolled
      // past most recently" with a single linear scan, which is what
      // we want for sections that are mid-screen but not in the spy
      // band (rootMargin clips out everything except the top 30%).
      var sections = tocLinks
        .map(function (link) {
          var id = link.getAttribute('data-toc-target');
          var el = document.getElementById(id);
          return el ? { id: id, el: el } : null;
        })
        .filter(Boolean);

      function setActive(id) {
        tocLinks.forEach(function (link) {
          link.classList.toggle(
            'mc-developers-doc__toc-link--active',
            link.getAttribute('data-toc-target') === id
          );
        });
      }

      // Compute the active section by scroll position rather than by
      // intersection alone — pick the last heading whose top has crossed
      // the spy line (~25% from the top of the viewport). This handles
      // the case where a heading scrolls past the spy band but the user
      // is still reading inside that section: the previous IO-only
      // implementation reset to the first link in that case, which is
      // wrong. Linear scan is fine — TOCs have <20 entries.
      function recomputeActive() {
        var spyLine = window.innerHeight * 0.25;
        var current = sections[0] ? sections[0].id : null;
        for (var i = 0; i < sections.length; i++) {
          var top = sections[i].el.getBoundingClientRect().top;
          if (top - spyLine <= 0) {
            current = sections[i].id;
          } else {
            break; // headings are in document order; first one past the
                   // line wins, the rest are below.
          }
        }
        if (current) setActive(current);
      }

      // Throttled scroll handler — rAF gives one update per paint frame
      // which is plenty for a sidebar highlight.
      var rafId = null;
      function onScroll() {
        if (rafId !== null) return;
        rafId = window.requestAnimationFrame(function () {
          rafId = null;
          recomputeActive();
        });
      }

      window.addEventListener('scroll', onScroll, { passive: true });
      window.addEventListener('resize', onScroll, { passive: true });

      // Click → flag immediately so the highlight responds before the
      // smooth-scroll animation lands. The scroll handler will re-affirm
      // once the page settles at the target position.
      tocLinks.forEach(function (link) {
        link.addEventListener('click', function () {
          setActive(link.getAttribute('data-toc-target'));
        });
      });

      // Prime the highlight on first paint.
      recomputeActive();
    }
  }

})();

// ============================================================
// Auth modal + AJAX form submit (2026-05-16, Wave 0 follow-up)
//   - Opens via [data-auth-modal-open]; data-auth-tab="register"
//     pre-selects the Register tab.
//   - Closes via ESC, backdrop click, [data-auth-modal-close].
//   - Focus trap keeps tab/shift+tab inside the dialog.
//   - Form submits hit the SAME server endpoints as the standalone
//     pages but with X-Requested-With → JSON path in AuthController.
//   - On success the page navigates to data.redirect (which is the
//     `intended` URL the modal trigger carried, scoped same-host).
// ============================================================
(function () {
  'use strict';

  var modal = document.getElementById('authModal');
  if (!modal) return; // Authenticated users get no modal — nothing to bind.

  var dialog = modal.querySelector('.mc-auth-modal__dialog');
  var FOCUSABLE = 'a[href], button:not([disabled]), input:not([disabled]), textarea:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])';
  var lastFocus = null;

  function selectTab(name) {
    modal.querySelectorAll('[data-auth-modal-tab]').forEach(function (btn) {
      var active = btn.getAttribute('data-auth-modal-tab') === name;
      btn.classList.toggle('is-active', active);
      btn.setAttribute('aria-selected', active ? 'true' : 'false');
    });
    modal.querySelectorAll('[data-auth-modal-panel]').forEach(function (panel) {
      panel.classList.toggle('is-active', panel.getAttribute('data-auth-modal-panel') === name);
    });
    // Focus the first field of the active panel for keyboard-only users.
    var first = modal.querySelector('[data-auth-modal-panel="' + name + '"] input:not([type="hidden"]):not([tabindex="-1"])');
    if (first) {
      // Defer slightly so the panel's display:block transition completes.
      window.requestAnimationFrame(function () { first.focus(); });
    }
  }

  function openModal(name) {
    lastFocus = document.activeElement;
    modal.hidden = false;
    document.body.classList.add('has-auth-modal');
    selectTab(name === 'register' ? 'register' : 'login');
  }

  function closeModal() {
    modal.hidden = true;
    document.body.classList.remove('has-auth-modal');
    if (lastFocus && typeof lastFocus.focus === 'function') {
      lastFocus.focus();
    }
  }

  function trapFocus(e) {
    if (modal.hidden) return;
    if (e.key !== 'Tab') return;
    var nodes = Array.prototype.slice.call(dialog.querySelectorAll(FOCUSABLE))
      .filter(function (n) { return n.offsetParent !== null; });
    if (!nodes.length) return;
    var first = nodes[0];
    var last = nodes[nodes.length - 1];
    if (e.shiftKey && document.activeElement === first) {
      e.preventDefault();
      last.focus();
    } else if (!e.shiftKey && document.activeElement === last) {
      e.preventDefault();
      first.focus();
    }
  }

  // Open triggers — anywhere on the page.
  document.addEventListener('click', function (e) {
    var trigger = e.target.closest('[data-auth-modal-open]');
    if (trigger) {
      e.preventDefault();
      var tab = trigger.getAttribute('data-auth-tab') || 'login';
      // Pull `intended` from the trigger href, if it points at /auth/login?intended=…
      var href = trigger.getAttribute('href') || '';
      var match = href.match(/intended=([^&]+)/);
      if (match) {
        modal.querySelectorAll('input[name="intended"]').forEach(function (input) {
          input.value = decodeURIComponent(match[1]);
        });
      }
      openModal(tab);
      return;
    }
    if (e.target.closest('[data-auth-modal-close]')) {
      e.preventDefault();
      closeModal();
      return;
    }
    // Tab buttons inside the modal.
    var tabBtn = e.target.closest('[data-auth-modal-tab]');
    if (tabBtn && modal.contains(tabBtn)) {
      e.preventDefault();
      selectTab(tabBtn.getAttribute('data-auth-modal-tab'));
    }
  });

  // ESC closes; Tab traps.
  document.addEventListener('keydown', function (e) {
    if (modal.hidden) return;
    if (e.key === 'Escape') {
      e.preventDefault();
      closeModal();
      return;
    }
    trapFocus(e);
  });

  // AJAX submit for any form tagged data-auth-form (modal + future surfaces).
  // Standalone /auth/login + /auth/register pages don't tag the data-auth-form
  // attribute on their forms (they live outside the modal), so this handler
  // doesn't interfere with the GET-flow standalone surface.
  modal.addEventListener('submit', function (e) {
    var form = e.target;
    if (!form.matches || !form.matches('[data-auth-form]')) return;
    e.preventDefault();

    var errBox = form.parentElement.querySelector('[data-auth-modal-errors]');
    if (errBox) { errBox.hidden = true; errBox.innerHTML = ''; }

    var submitBtn = form.querySelector('button[type="submit"]');
    if (submitBtn) submitBtn.disabled = true;

    var body = new URLSearchParams(new FormData(form));
    fetch(form.action, {
      method: 'POST',
      credentials: 'same-origin',
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
      body: body,
    }).then(function (r) {
      return r.json().then(function (data) { return { ok: r.ok, status: r.status, data: data }; });
    }).then(function (resp) {
      if (resp.ok && resp.data && resp.data.redirect) {
        // Navigate to the intended URL (article or home) — the server has
        // already vetted same-host, so this is safe.
        window.location.href = resp.data.redirect;
        return;
      }
      // 422 validation failure: paint messages in the error box.
      var msgs = [];
      if (resp.data && resp.data.errors) {
        Object.keys(resp.data.errors).forEach(function (field) {
          (resp.data.errors[field] || []).forEach(function (m) { msgs.push(m); });
        });
      } else if (resp.data && resp.data.message) {
        msgs.push(resp.data.message);
      } else {
        msgs.push('Something went wrong. Please try again.');
      }
      if (errBox) {
        errBox.innerHTML = msgs.map(function (m) {
          var p = document.createElement('p'); p.textContent = m; return p.outerHTML;
        }).join('');
        errBox.hidden = false;
      }
    }).catch(function () {
      if (errBox) {
        errBox.innerHTML = '<p>Network error. Please try again.</p>';
        errBox.hidden = false;
      }
    }).finally(function () {
      if (submitBtn) submitBtn.disabled = false;
    });
  });
})();

// ============================================================
// CTA inline — Wave 3 (2026-05-16)
//   - Fires `shown` impression when a CTA scrolls into view.
//   - Fires `clicked` impression when its button is clicked (no
//     preventDefault — let the link navigate normally).
//   - Fires `dismissed` + adds slug to `cta_dismissed` cookie
//     (7-day TTL) when the X is clicked, then hides the section.
//   - Countdown ticker updates d/h/m/s every second; hides the
//     CTA when ends_at passes.
// All network calls are fire-and-forget (best-effort sendBeacon
// fallback to fetch). No retries — losing one impression beats
// blocking the user's click.
// ============================================================
(function () {
  'use strict';

  function csrfToken() {
    var meta = document.querySelector('meta[name="csrf-token"]');
    if (meta) return meta.getAttribute('content') || '';
    // Fallback: pull from any form on the page.
    var t = document.querySelector('input[name="_token"]');
    return t ? t.value : '';
  }

  function track(slug, action) {
    if (!slug || !action) return;
    var url = '/cta/' + encodeURIComponent(slug) + '/track';
    var body = new URLSearchParams();
    body.append('_token', csrfToken());
    body.append('action', action);
    body.append('page_path', window.location.pathname + window.location.search);
    // fetch + keepalive: behaves like sendBeacon for unload-adjacent
    // dispatches (browser keeps the request in flight even after the
    // user navigates away on the click path) and stays fully observable
    // to Playwright's `page.on('request')`. sendBeacon-issued requests
    // sometimes bypass Playwright's network listener in the test runner,
    // so we standardize on fetch to keep the network surface testable.
    fetch(url, {
      method: 'POST',
      credentials: 'same-origin',
      headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      body: body,
      keepalive: true,
    }).catch(function () { /* fire-and-forget */ });
  }

  // ----- Cookie helpers ----------------------------------------
  function readDismissed() {
    var match = document.cookie.match(/(?:^|; )cta_dismissed=([^;]+)/);
    if (!match) return [];
    try {
      return decodeURIComponent(match[1]).split(',').map(function (s) { return s.trim(); }).filter(Boolean);
    } catch (e) { return []; }
  }
  function writeDismissed(slugs) {
    var v = encodeURIComponent(slugs.join(','));
    var maxAge = 60 * 60 * 24 * 7; // 7 days
    var attrs = ['path=/', 'max-age=' + maxAge, 'samesite=lax'];
    if (window.location.protocol === 'https:') attrs.push('secure');
    document.cookie = 'cta_dismissed=' + v + '; ' + attrs.join('; ');
  }
  function addDismissed(slug) {
    var set = readDismissed();
    if (set.indexOf(slug) === -1) {
      set.push(slug);
      writeDismissed(set);
    }
  }

  // ----- Impression-on-scroll-into-view -----------------------
  function fireShownOnView(section) {
    if (section.__shownFired) return;
    section.__shownFired = true;
    var slug = section.getAttribute('data-cta-slug');
    track(slug, 'shown');
  }

  function bindImpressionObserver() {
    var sections = document.querySelectorAll('[data-testid="cta-inline"]');
    if (!sections.length) return;
    if (typeof IntersectionObserver !== 'function') {
      // Old browser — fire immediately on bind. Worst case: every CTA
      // counts a shown even if the user never sees it. Acceptable v1.
      sections.forEach(fireShownOnView);
      return;
    }
    var obs = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) {
          fireShownOnView(e.target);
          obs.unobserve(e.target);
        }
      });
    }, { rootMargin: '0px', threshold: 0.25 });
    sections.forEach(function (s) { obs.observe(s); });
  }

  // ----- Click + dismiss ---------------------------------------
  function bindClickDismiss() {
    document.addEventListener('click', function (e) {
      var clickEl = e.target.closest('[data-cta-click]');
      if (clickEl) {
        var section = clickEl.closest('[data-testid="cta-inline"]');
        var slug = section && section.getAttribute('data-cta-slug');
        if (slug) track(slug, 'clicked');
        return; // Let the link navigate normally.
      }
      var dismissEl = e.target.closest('[data-cta-dismiss]');
      if (dismissEl) {
        var sec = dismissEl.closest('[data-testid="cta-inline"]');
        var s = sec && sec.getAttribute('data-cta-slug');
        if (s) {
          addDismissed(s);
          track(s, 'dismissed');
        }
        if (sec) sec.remove();
      }
    });
  }

  // ----- Countdown ticker --------------------------------------
  function fmt(n) { return n < 10 ? '0' + n : String(n); }

  function tickCountdown(node) {
    var ends = node.getAttribute('data-ends-at');
    if (!ends) return;
    var endsTs = new Date(ends).getTime();
    if (isNaN(endsTs)) return;
    var diff = endsTs - Date.now();
    if (diff <= 0) {
      // Time's up — hide the whole CTA section, not just the timer.
      var section = node.closest('[data-testid="cta-inline"]');
      if (section) section.remove();
      else node.hidden = true;
      return;
    }
    var s = Math.floor(diff / 1000);
    var d = Math.floor(s / 86400); s -= d * 86400;
    var h = Math.floor(s / 3600);  s -= h * 3600;
    var m = Math.floor(s / 60);    s -= m * 60;

    var sel = function (k) { return node.querySelector('[data-cta-countdown-' + k + ']'); };
    var setText = function (k, v) { var el = sel(k); if (el) el.textContent = (k === 'd' ? String(d) : fmt(v)); };
    setText('d', d);
    setText('h', h);
    setText('m', m);
    setText('s', s);
  }

  function bindCountdowns() {
    var nodes = document.querySelectorAll('[data-cta-countdown]');
    if (!nodes.length) return;
    nodes.forEach(function (node) {
      tickCountdown(node);
      // Tick every second. Browsers throttle background tabs which is
      // fine — when the tab is active the timer updates smoothly; when
      // background, it'll catch up on tab focus.
      window.setInterval(function () { tickCountdown(node); }, 1000);
    });
  }

  function init() {
    bindImpressionObserver();
    bindClickDismiss();
    bindCountdowns();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();

/* ============================================================
 * Smooth scroll-to-result for form submissions (Wave 5, 2026-05-17).
 *
 * Problem: with `scroll-behavior: smooth` on <html> + a URL anchor
 * like `/#newsletter-footer`, the browser jumps to the anchor while
 * the page is still painting fonts + images. Layout shifts mid-scroll
 * → visible "giật" (jerk). Final scroll position is wrong because the
 * target's Y kept changing during the animation.
 *
 * Fix: don't rely on URL anchor. After load+layout settle, find the
 * form-result block (success/error) and scroll it into view with
 * precise alignment. One single smooth movement, lands exactly on
 * the message regardless of page length, sticky header, or font swap.
 *
 * Respects prefers-reduced-motion. Idempotent — safe to ship inline
 * regardless of which page hits it.
 * ============================================================ */
(function () {
  function smoothScrollToFormResult() {
    // First match wins. Newsletter success > contact success > error
    // summary — priority follows visual prominence on the page.
    var target = document.querySelector(
      '.mc-newsletter--success, .mc-form-success, .mc-form-error-summary'
    );
    if (!target) return;

    var reduced = window.matchMedia &&
                  window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Two rAF ticks so we run AFTER browser has applied initial scroll
    // restoration + layout. Without this, the scroll fires before the
    // newsletter component's animation has reserved its height and the
    // target moves out from under us.
    requestAnimationFrame(function () {
      requestAnimationFrame(function () {
        // Compute target scroll-Y manually instead of using
        // scrollIntoView({block:'center'}). Reason: on tall pages near
        // the bottom (footer newsletter is the canonical case), 'center'
        // alignment requires more scroll than the page can supply →
        // browser silently leaves the target below the viewport. Manual
        // clamp to [0, scrollMax] guarantees the element ends up inside.
        var rect = target.getBoundingClientRect();
        var elemTop = rect.top + window.pageYOffset;
        var elemHeight = rect.height;
        var vh = window.innerHeight;
        var headerOffset = 100;  // matches CSS scroll-margin-top

        // Preferred Y centers the element in the lower 2/3 of viewport —
        // feels intentional (eye-level) without sitting under the
        // sticky header. For short elements we still center; for tall
        // ones we top-align with the header offset.
        var preferred;
        if (elemHeight + headerOffset > vh) {
          preferred = elemTop - headerOffset;
        } else {
          preferred = elemTop - (vh - elemHeight) / 2;
        }

        // Clamp to the actually-scrollable range.
        var scrollMax = Math.max(0, document.documentElement.scrollHeight - vh);
        var targetY = Math.max(0, Math.min(preferred, scrollMax));

        try {
          window.scrollTo({
            top: targetY,
            left: 0,
            behavior: reduced ? 'auto' : 'smooth',
          });
        } catch (_) {
          // Older browsers without smooth-scrollTo support.
          window.scrollTo(0, targetY);
        }

        // Move focus for screen readers so the success state is
        // announced. tabindex=-1 makes the element focusable without
        // adding it to the tab order.
        if (!target.hasAttribute('tabindex')) {
          target.setAttribute('tabindex', '-1');
        }
        try { target.focus({ preventScroll: true }); } catch (_) {}
      });
    });
  }

  // Pop-in animation on .mc-newsletter--success runs ~350ms AND late-
  // loading images can push scrollHeight by hundreds of pixels for a
  // full second after `load` fires. A single shot can land the visitor
  // mid-page (target computed pre-grow, page then grew, target moved
  // out of viewport). Re-run while the page is still growing.
  var SCROLL_INITIAL_DELAY_MS = 400;
  var SCROLL_SETTLE_WINDOW_MS = 2000;
  var SCROLL_POLL_INTERVAL_MS = 250;

  function watchAndScroll() {
    var lastHeight = -1;
    var elapsed = 0;
    var stableTicks = 0;

    smoothScrollToFormResult();   // first shot at SCROLL_INITIAL_DELAY_MS
    lastHeight = document.documentElement.scrollHeight;

    var interval = setInterval(function () {
      elapsed += SCROLL_POLL_INTERVAL_MS;
      var h = document.documentElement.scrollHeight;
      if (h !== lastHeight) {
        // Page still growing — re-scroll to the (now-moved) target.
        smoothScrollToFormResult();
        lastHeight = h;
        stableTicks = 0;
      } else {
        // Two consecutive stable ticks = settled. Stop polling.
        stableTicks++;
        if (stableTicks >= 2 || elapsed >= SCROLL_SETTLE_WINDOW_MS) {
          clearInterval(interval);
        }
      }
    }, SCROLL_POLL_INTERVAL_MS);
  }

  function start() {
    setTimeout(watchAndScroll, SCROLL_INITIAL_DELAY_MS);
  }

  if (document.readyState === 'complete') {
    start();
  } else {
    window.addEventListener('load', start);
  }
})();
