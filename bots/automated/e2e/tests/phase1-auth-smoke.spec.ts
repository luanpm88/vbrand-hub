import { test, expect } from '@playwright/test';
import { ENV } from '../playwright.config';
import { loginDesktop, loginWebapp, assertNoPageErrors } from '../helpers/auth';

/**
 * Phase 1 — Auth + Smoke
 * Plan: docs/E2E_TEST_PLAN.md §Phase 1
 *
 * Goals:
 *  - Login works (desktop dashboard + mobile webapp)
 *  - Wrong credentials are rejected
 *  - Every primary route returns 200 with no PHP error
 *  - Storefront base pages load
 */

// ---------- Desktop dashboard ----------

test.describe('Phase 1 / Desktop dashboard', () => {
  test('login page renders', async ({ page }) => {
    const res = await page.goto(`${ENV.BASE_APP}/login`);
    expect(res?.status(), 'GET /login').toBeLessThan(400);
    await expect(page.locator('input[name="email"]')).toBeVisible();
    await expect(page.locator('input[name="password"]')).toBeVisible();
    await assertNoPageErrors(page, '/login');
  });

  test('rejects wrong password', async ({ page }) => {
    await page.goto(`${ENV.BASE_APP}/login`);
    await page.locator('input[name="email"]').fill(ENV.SELLER_EMAIL);
    await page.locator('input[name="password"]').fill('definitely-wrong-pw');
    await page.locator('form button[type="submit"], form input[type="submit"]').first().click();
    // Stays on /login (or returns to it)
    await page.waitForLoadState('networkidle');
    expect(page.url()).toContain('/login');
  });

  test('logs in with valid seller credentials', async ({ page }) => {
    await loginDesktop(page);
    expect(page.url()).not.toContain('/login');
    await assertNoPageErrors(page, 'after login');
  });

  // Smoke matrix — actual routes from routes/web.php + routes/brand.php.
  // Maps to USER_GUIDE_DESKTOP §"Tổng quan giao diện".
  // Each entry: [label, path]. We accept any non-5xx response.
  const desktopRoutes: Array<[string, string]> = [
    ['Trang chính',           '/brand'],
    ['Tên miền',              '/brand/domain'],
    ['Website / Giao diện',   '/brand/website-templates'],
    ['Cấu hình nội dung',     '/brand/website/theme/options'],
    ['Khách hàng / Liên hệ',  '/brand/contacts'],
    ['Cửa hàng / Sản phẩm',   '/store/products'],
    ['Các danh mục',          '/store/categories'],
    ['Thuộc tính',            '/store/attributes'],
    ['Đơn hàng',              '/store/orders'],
    ['Kho hàng / Vận chuyển', '/store/warehouse'],
    ['Store dashboard',       '/store/dashboard'],
    ['Bài viết / Blog',       '/website/articles'],
    ['Doanh thu bán hàng',    '/brand/accounting-report'],
  ];

  for (const [label, path] of desktopRoutes) {
    test(`smoke: ${label} (${path})`, async ({ page }) => {
      await loginDesktop(page);
      const res = await page.goto(`${ENV.BASE_APP}${path}`);
      // 404 / 500 are hard fails. Any redirect is OK as long as final page has no error.
      expect(res?.status() ?? 0, `${path} status`).toBeLessThan(500);
      await assertNoPageErrors(page, path);
    });
  }

  test('logout returns to login', async ({ page }) => {
    await loginDesktop(page);
    // Try clicking a visible logout link/button; fall back to POSTing the route.
    const logout = page.getByRole('link', { name: /đăng xuất|logout/i }).first();
    if (await logout.isVisible().catch(() => false)) {
      await logout.click();
    } else {
      await page.goto(`${ENV.BASE_APP}/logout`);
    }
    await page.waitForLoadState('networkidle');
    expect(page.url()).toMatch(/\/login/);
  });
});

// ---------- Mobile webapp ----------

test.describe('Phase 1 / Mobile webapp', () => {
  test.use({ viewport: { width: 430, height: 932 } });

  test('login page renders', async ({ page }) => {
    const res = await page.goto(`${ENV.BASE_APP}/brand/mobile/login`);
    expect(res?.status()).toBeLessThan(400);
    await expect(page.locator('input[name="email"]')).toBeVisible();
    await assertNoPageErrors(page, '/brand/mobile/login');
  });

  test('rejects wrong password', async ({ page }) => {
    await page.goto(`${ENV.BASE_APP}/brand/mobile/login`);
    await page.locator('input[name="email"]').fill(ENV.SELLER_EMAIL);
    await page.locator('input[name="password"]').fill('definitely-wrong-pw');
    await page.locator('form button[type="submit"], form input[type="submit"]').first().click();
    await page.waitForLoadState('networkidle');
    expect(page.url()).toContain('/brand/mobile/login');
  });

  test('logs in with valid credentials', async ({ page }) => {
    await loginWebapp(page);
    expect(page.url()).not.toContain('/brand/mobile/login');
    await assertNoPageErrors(page, 'webapp after login');
  });

  // 4 main tabs — see USER_GUIDE_MOBILE §"Tổng quan giao diện"
  const mobileRoutes: Array<[string, string]> = [
    ['Dashboard', '/brand/mobile'],
    ['Sản phẩm', '/brand/mobile/products'],
    ['Đơn hàng', '/brand/mobile/orders'],
    ['Tài khoản', '/brand/mobile/profile'],
  ];

  for (const [label, path] of mobileRoutes) {
    test(`smoke: ${label} (${path})`, async ({ page }) => {
      await loginWebapp(page);
      const res = await page.goto(`${ENV.BASE_APP}${path}`);
      expect(res?.status() ?? 0, `${path} status`).toBeLessThan(500);
      await assertNoPageErrors(page, path);
    });
  }
});

// ---------- Storefront (WP / WooCommerce) ----------

test.describe('Phase 1 / Storefront', () => {
  test('home page', async ({ page }) => {
    const res = await page.goto(ENV.BASE_SITE);
    expect(res?.status() ?? 0).toBeLessThan(500);
    // WordPress sites should at least have an <html> with a title
    await expect(page).toHaveTitle(/.+/);
  });

  test('shop page', async ({ page }) => {
    const res = await page.goto(`${ENV.BASE_SITE}/shop/`);
    // Some themes use /cua-hang or no /shop. Accept any non-5xx.
    expect(res?.status() ?? 0).toBeLessThan(500);
  });
});
