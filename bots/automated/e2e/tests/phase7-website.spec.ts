import { test, expect } from '@playwright/test';
import { ENV } from '../playwright.config';
import { loginDesktop, loginWebapp, assertNoPageErrors } from '../helpers/auth';
import { csrfHeaders, listThemes, activeTheme } from '../helpers/api';

/**
 * Phase 7 — Website: Theme & Cấu hình nội dung
 * Plan: docs/E2E_TEST_PLAN.md §Phase 7
 *
 * Reference:
 *  - USER_GUIDE_DESKTOP §3 (Website — Giao diện & nội dung)
 *  - USER_GUIDE_MOBILE  §4 (Giao diện website — Chọn & đổi template)
 *
 * Goals:
 *  - Theme list page renders on both desktop AND webapp
 *  - WP exposes >1 theme (so the user can switch)
 *  - Activating a theme via desktop POST flips the active flag in WP
 *  - Activating a theme via webapp POST does the same
 *  - The cấu hình nội dung (theme options) page renders + accepts a POST
 *    round-trip (already verified by Phase 1 smoke that the page renders;
 *    here we additionally verify the save endpoint accepts a no-op POST)
 *
 * Snapshot/restore strategy:
 *  - Each theme-switching test snapshots the current active theme in
 *    beforeEach and restores it in afterEach so a green Phase 7 leaves the
 *    site on the same theme it started.
 */

// ---------- Theme list page ----------

test.describe('Phase 7 / Desktop / Website / Giao diện list', () => {
  test('list page renders + WP exposes >1 theme', async ({ page }) => {
    await loginDesktop(page);
    const res = await page.goto(`${ENV.BASE_APP}/brand/website-templates`);
    expect(res?.status() ?? 0).toBeLessThan(500);
    await assertNoPageErrors(page, '/brand/website-templates');

    const themes = await listThemes(page);
    expect(themes.length, 'WP should expose at least 2 themes for the switch test').toBeGreaterThan(1);
    expect(themes.filter((t) => t.active).length, 'exactly 1 active theme').toBe(1);
  });
});

test.describe('Phase 7 / Mobile webapp / Giao diện list', () => {
  test.use({ viewport: { width: 430, height: 932 } });

  test('webapp templates page renders', async ({ page }) => {
    await loginWebapp(page);
    const res = await page.goto(`${ENV.BASE_APP}/brand/mobile/templates`);
    expect(res?.status() ?? 0).toBeLessThan(500);
    await assertNoPageErrors(page, '/brand/mobile/templates');
  });
});

// ---------- Activate via desktop ----------

test.describe('Phase 7 / Desktop / Activate theme', () => {
  let originalThemeId: string | null = null;

  test.afterEach(async ({ page }) => {
    if (originalThemeId) {
      const headers = await csrfHeaders(page);
      await page.request
        .fetch(
          `${ENV.BASE_APP}/brand/website-templates/set-active/${encodeURIComponent(originalThemeId)}`,
          { method: 'POST', headers },
        )
        .catch(() => {});
      originalThemeId = null;
    }
  });

  test('POST set-active flips the active theme in WP', async ({ page }) => {
    await loginDesktop(page);

    // Snapshot current active theme + pick a different one
    const before = await listThemes(page);
    const current = before.find((t) => t.active);
    expect(current, 'should have a current active theme').toBeTruthy();
    originalThemeId = current!.id;

    const target = before.find((t) => t.id !== current!.id);
    expect(target, 'should have at least one other theme to switch to').toBeTruthy();

    // Activate via the desktop endpoint
    const headers = await csrfHeaders(page);
    const res = await page.request.fetch(
      `${ENV.BASE_APP}/brand/website-templates/set-active/${encodeURIComponent(target!.id)}`,
      { method: 'POST', headers },
    );
    // The desktop controller redirects on success
    expect(res.status(), 'set-active response').toBeLessThan(400);

    // Verify the active flag flipped in WP
    const after = await listThemes(page);
    const newActive = after.find((t) => t.active);
    expect(newActive, 'should still have an active theme').toBeTruthy();
    expect(newActive!.id, `WP should now report ${target!.id} as active`).toBe(target!.id);
  });
});

// ---------- Activate via webapp ----------

test.describe('Phase 7 / Mobile webapp / Activate theme', () => {
  test.use({ viewport: { width: 430, height: 932 } });

  let originalThemeId: string | null = null;

  test.afterEach(async ({ page }) => {
    if (originalThemeId) {
      const headers = await csrfHeaders(page);
      await page.request
        .fetch(
          `${ENV.BASE_APP}/brand/mobile/templates/${encodeURIComponent(originalThemeId)}/activate`,
          { method: 'POST', headers },
        )
        .catch(() => {});
      originalThemeId = null;
    }
  });

  test('POST activate flips the active theme in WP', async ({ page }) => {
    await loginWebapp(page);

    const before = await listThemes(page);
    const current = before.find((t) => t.active);
    expect(current).toBeTruthy();
    originalThemeId = current!.id;

    const target = before.find((t) => t.id !== current!.id);
    expect(target).toBeTruthy();

    const headers = await csrfHeaders(page);
    const res = await page.request.fetch(
      `${ENV.BASE_APP}/brand/mobile/templates/${encodeURIComponent(target!.id)}/activate`,
      { method: 'POST', headers },
    );
    expect(res.status(), 'webapp activate response').toBeLessThan(400);

    const newActive = await activeTheme(page);
    expect(newActive!.id, `webapp should activate ${target!.id}`).toBe(target!.id);
  });
});

// ---------- Cấu hình nội dung (theme options) ----------

test.describe('Phase 7 / Desktop / Cấu hình nội dung', () => {
  test('theme options page renders + accepts a no-op save', async ({ page }) => {
    await loginDesktop(page);
    const res = await page.goto(`${ENV.BASE_APP}/brand/website/theme/options`);
    expect(res?.status() ?? 0).toBeLessThan(500);
    await assertNoPageErrors(page, '/brand/website/theme/options');

    // Save with empty payload — `themeOptions()` POST handler calls
    // fillSchema() then themeUpdateOptions(). Even if the active theme has
    // no builder schema (the case Phase 1 fixed), the controller should
    // not crash. Verifies the round-trip works end-to-end.
    const headers = await csrfHeaders(page);
    const saveRes = await page.request.fetch(
      `${ENV.BASE_APP}/brand/website/theme/options`,
      { method: 'POST', headers, form: {} },
    );
    expect(saveRes.status(), 'theme options save').toBeLessThan(500);
  });
});
