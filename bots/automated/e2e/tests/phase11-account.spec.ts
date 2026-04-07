import { test, expect } from '@playwright/test';
import { ENV } from '../playwright.config';
import { loginDesktop, loginWebapp, assertNoPageErrors } from '../helpers/auth';

/**
 * Phase 11 — Tài khoản & đổi mật khẩu
 * Plan: docs/E2E_TEST_PLAN.md §Phase 11
 *
 * Reference: USER_GUIDE_DESKTOP (header), USER_GUIDE_MOBILE §5
 *
 * Surfaces under test:
 *  - Desktop:  GET/POST /account/profile          (AccountController@profile)
 *  - Webapp:   GET   /brand/mobile/profile        (Brand\Webapp\ProfileController@index)
 *              GET   /brand/mobile/profile/edit   (...@edit)
 *              POST  /brand/mobile/profile/update (...@update — JSON response)
 *              GET   /brand/mobile/profile/password (...@password)
 *              POST  /brand/mobile/profile/password (...@updatePassword — JSON)
 *
 * Notes (per CLAUDE.md "if 2 docs disagree → fix the wrong one"):
 *  - The original Phase 11 plan said "Đổi mật khẩu (đổi rồi đổi lại 123456)"
 *    but the webapp `updatePassword` enforces `new_password: required|min:8`.
 *    Local seller password is `123456` (6 chars) — you cannot ask the
 *    webapp to set the password back to it. The plan wording was wrong.
 *  - Strategy: Use the webapp to change to a temp 8+ char password and
 *    verify the endpoint returns success, then **restore via the desktop
 *    AccountController** (which has no min-length on `password`) so the
 *    seller account survives subsequent test runs at `123456`.
 *  - Snapshot original first_name/last_name/phone for both surfaces and
 *    restore in afterEach so a green Phase 11 leaves no drift.
 */

const TEMP_PASSWORD = 'tempPass1234'; // 12 chars, satisfies min:8

// ---------- Desktop /account/profile ----------

test.describe('Phase 11 / Desktop / Tài khoản', () => {
  test.use({ viewport: { width: 1280, height: 800 } });

  let originalFirstName = '';
  let originalLastName = '';
  let originalPhone = '';
  let originalTimezone = 'Asia/Tokyo';
  let originalLanguageId = '';

  test.afterEach(async ({ page }) => {
    if (!originalFirstName) return;
    // Restore via the same endpoint
    await loginDesktop(page);
    const csrf = await getCsrf(page, `${ENV.BASE_APP}/account/profile`);
    await page.request.post(`${ENV.BASE_APP}/account/profile`, {
      multipart: {
        _token: csrf,
        first_name: originalFirstName,
        last_name: originalLastName,
        phone: originalPhone,
        timezone: originalTimezone,
        language_id: originalLanguageId,
      },
    });
  });

  test('GET /account/profile renders, POST round-trips first_name/last_name', async ({
    page,
  }) => {
    await loginDesktop(page);

    const res = await page.goto(`${ENV.BASE_APP}/account/profile`);
    expect(res?.status() ?? 0).toBeLessThan(500);
    await assertNoPageErrors(page, '/account/profile');

    // Snapshot original values from the form
    originalFirstName =
      (await page.locator('input[name="first_name"]').inputValue()) || 'Admin';
    originalLastName =
      (await page.locator('input[name="last_name"]').inputValue()) || 'ACM';
    originalPhone =
      (await page.locator('input[name="phone"]').inputValue()) || '0900000000';
    originalTimezone =
      (await page.locator('[name="timezone"]').first().inputValue()) ||
      'Asia/Tokyo';
    originalLanguageId =
      (await page.locator('[name="language_id"]').first().inputValue()) ||
      '';

    const newFirst = `E2E${Date.now()}`;
    const csrf = await getCsrf(page, `${ENV.BASE_APP}/account/profile`);
    const updateRes = await page.request.post(`${ENV.BASE_APP}/account/profile`, {
      multipart: {
        _token: csrf,
        first_name: newFirst,
        last_name: originalLastName,
        phone: originalPhone,
        timezone: originalTimezone,
        language_id: originalLanguageId,
      },
    });
    expect(updateRes.status(), 'profile POST status').toBeLessThan(400);

    // Reload and verify the new first_name persisted
    await page.goto(`${ENV.BASE_APP}/account/profile`);
    await assertNoPageErrors(page, '/account/profile (after update)');
    await expect(page.locator('input[name="first_name"]')).toHaveValue(newFirst);
  });
});

// ---------- Webapp profile ----------

test.describe('Phase 11 / Webapp / Tài khoản — view + edit profile', () => {
  test.use({ viewport: { width: 430, height: 932 } });

  let originalFirstName = '';
  let originalLastName = '';
  let originalPhone = '';

  test.afterEach(async ({ page }) => {
    if (!originalFirstName) return;
    await loginWebapp(page);
    const csrf = await getCsrf(page, `${ENV.BASE_APP}/brand/mobile/profile/edit`);
    await page.request.post(`${ENV.BASE_APP}/brand/mobile/profile/update`, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      form: {
        _token: csrf,
        first_name: originalFirstName,
        last_name: originalLastName,
        phone: originalPhone,
      },
    });
  });

  test('profile index + edit form render, update round-trips', async ({ page }) => {
    await loginWebapp(page);

    // Index
    const idxRes = await page.goto(`${ENV.BASE_APP}/brand/mobile/profile`);
    expect(idxRes?.status() ?? 0).toBeLessThan(500);
    await assertNoPageErrors(page, '/brand/mobile/profile');
    // Has the "Chỉnh sửa thông tin" + "Đổi mật khẩu" + "Xem website" links
    await expect(page.getByText('Thông tin cá nhân')).toBeVisible();
    await expect(page.getByText('Đổi mật khẩu')).toBeVisible();

    // Edit form
    const editRes = await page.goto(`${ENV.BASE_APP}/brand/mobile/profile/edit`);
    expect(editRes?.status() ?? 0).toBeLessThan(500);
    await assertNoPageErrors(page, '/brand/mobile/profile/edit');
    await expect(page.locator('input[name="first_name"]')).toBeVisible();
    await expect(page.locator('input[name="last_name"]')).toBeVisible();
    await expect(page.locator('input[name="phone"]')).toBeVisible();

    // Snapshot original values
    originalFirstName =
      (await page.locator('input[name="first_name"]').inputValue()) || 'Admin';
    originalLastName =
      (await page.locator('input[name="last_name"]').inputValue()) || 'ACM';
    originalPhone =
      (await page.locator('input[name="phone"]').inputValue()) || '';

    // Update via JSON endpoint
    const newFirst = `Webapp${Date.now()}`;
    const csrf = await getCsrf(page, `${ENV.BASE_APP}/brand/mobile/profile/edit`);
    const updateRes = await page.request.post(
      `${ENV.BASE_APP}/brand/mobile/profile/update`,
      {
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        form: {
          _token: csrf,
          first_name: newFirst,
          last_name: originalLastName,
          phone: originalPhone,
        },
      },
    );
    expect(updateRes.status(), 'webapp profile update status').toBeLessThan(400);
    const json = await updateRes.json();
    expect(json.message).toContain('thành công');

    // Reload edit form and verify
    await page.goto(`${ENV.BASE_APP}/brand/mobile/profile/edit`);
    await expect(page.locator('input[name="first_name"]')).toHaveValue(newFirst);
  });
});

// ---------- Webapp password change ----------

test.describe('Phase 11 / Webapp / Đổi mật khẩu', () => {
  test.use({ viewport: { width: 430, height: 932 } });

  // After this test we must restore the original `123456` password via the
  // desktop endpoint (webapp enforces min:8 — see top-of-file note).
  test.afterEach(async ({ browser }) => {
    // Use a fresh context so we are not relying on whatever state the
    // failing test left behind.
    const ctx = await browser.newContext();
    const page = await ctx.newPage();
    try {
      // Try to login with the temp password first; if that succeeds, we
      // know the test changed it. Otherwise the original was already in
      // place (test failed before changing) — nothing to restore.
      await page.goto(`${ENV.BASE_APP}/login`);
      await page.locator('input[name="email"]').fill(ENV.SELLER_EMAIL);
      await page.locator('input[name="password"]').fill(TEMP_PASSWORD);
      await page
        .locator('form button[type="submit"], form input[type="submit"]')
        .first()
        .click();
      await page.waitForLoadState('networkidle').catch(() => {});

      if (page.url().includes('/login')) {
        // Temp password did not work → original is intact, no restore needed.
        return;
      }

      // Logged in with temp → restore original via desktop /account/profile
      const profileRes = await page.goto(`${ENV.BASE_APP}/account/profile`);
      if ((profileRes?.status() ?? 0) >= 400) return;
      const csrf = await getCsrf(page, `${ENV.BASE_APP}/account/profile`);
      const firstName =
        (await page.locator('input[name="first_name"]').inputValue()) || 'Admin';
      const lastName =
        (await page.locator('input[name="last_name"]').inputValue()) || 'ACM';
      const phone =
        (await page.locator('input[name="phone"]').inputValue()) || '0900000000';
      const timezone =
        (await page.locator('[name="timezone"]').first().inputValue()) ||
        'Asia/Tokyo';
      const languageId =
        (await page.locator('[name="language_id"]').first().inputValue()) ||
        '';

      await page.request.post(`${ENV.BASE_APP}/account/profile`, {
        multipart: {
          _token: csrf,
          first_name: firstName,
          last_name: lastName,
          phone,
          timezone,
          language_id: languageId,
          password: ENV.SELLER_PASSWORD, // restore original (no min check on desktop)
          password_confirmation: ENV.SELLER_PASSWORD,
        },
      });
    } finally {
      await ctx.close();
    }
  });

  test('POST /brand/mobile/profile/password changes the seller password', async ({
    page,
    browser,
  }) => {
    await loginWebapp(page);

    // Form page renders
    const res = await page.goto(`${ENV.BASE_APP}/brand/mobile/profile/password`);
    expect(res?.status() ?? 0).toBeLessThan(500);
    await assertNoPageErrors(page, '/brand/mobile/profile/password');
    await expect(page.locator('input[name="current_password"]')).toBeVisible();
    await expect(page.locator('input[name="new_password"]')).toBeVisible();
    await expect(
      page.locator('input[name="new_password_confirmation"]'),
    ).toBeVisible();

    // Submit JSON update
    const csrf = await getCsrf(page, `${ENV.BASE_APP}/brand/mobile/profile/password`);
    const updateRes = await page.request.post(
      `${ENV.BASE_APP}/brand/mobile/profile/password`,
      {
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        form: {
          _token: csrf,
          current_password: ENV.SELLER_PASSWORD,
          new_password: TEMP_PASSWORD,
          new_password_confirmation: TEMP_PASSWORD,
        },
      },
    );
    expect(updateRes.status(), 'password update status').toBeLessThan(400);
    const json = await updateRes.json();
    expect(json.message).toContain('thành công');

    // Verify the new password actually authenticates by logging in via a
    // fresh context with the temp password.
    const ctx = await browser.newContext();
    const verifyPage = await ctx.newPage();
    await verifyPage.goto(`${ENV.BASE_APP}/login`);
    await verifyPage.locator('input[name="email"]').fill(ENV.SELLER_EMAIL);
    await verifyPage.locator('input[name="password"]').fill(TEMP_PASSWORD);
    await Promise.all([
      verifyPage.waitForURL((u) => !u.pathname.endsWith('/login'), {
        timeout: 30_000,
      }),
      verifyPage
        .locator('form button[type="submit"], form input[type="submit"]')
        .first()
        .click(),
    ]);
    expect(verifyPage.url(), 'should redirect away from /login with temp pass')
      .not.toContain('/login');
    await ctx.close();
  });
});

// ---------- helper ----------

async function getCsrf(page: import('@playwright/test').Page, url: string): Promise<string> {
  // Visit the page so a fresh _token is rendered, then read it from the
  // first hidden _token input.
  if (page.url() !== url) {
    await page.goto(url);
  }
  const token =
    (await page
      .locator('input[name="_token"]')
      .first()
      .getAttribute('value')) ?? '';
  return token;
}
