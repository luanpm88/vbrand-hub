import { expect, Page } from '@playwright/test';
import { ENV } from '../playwright.config';

/**
 * Login the page to the brand-app via a given login URL (`/login` or
 * `/brand/mobile/login`). If the same browser context is already
 * authenticated, the login URL auto-redirects elsewhere — in that case we
 * skip filling the form (the existing session is reused).
 *
 * This matters for cross-surface tests (e.g. Phase 4.2 customer → seller →
 * admin) where the same user can be logged in once and accessed via multiple
 * surface URLs.
 *
 * If the caller wants to switch to a *different* user, call `forceLogout`
 * first.
 */
async function loginVia(page: Page, loginPath: string, email: string, password: string) {
  await page.goto(`${ENV.BASE_APP}${loginPath}`);
  // If we got redirected away from the login page → already authenticated.
  if (!page.url().includes(loginPath)) {
    return;
  }
  await page.locator('input[name="email"]').fill(email);
  await page.locator('input[name="password"]').fill(password);
  await Promise.all([
    page.waitForURL((u) => !u.pathname.endsWith(loginPath), { timeout: 30_000 }),
    page.locator('form button[type="submit"], form input[type="submit"]').first().click(),
  ]);
}

/**
 * Force-logout the current browser context. Use before re-logging in as a
 * different user mid-test.
 */
export async function forceLogout(page: Page) {
  await page.goto(`${ENV.BASE_APP}/logout`).catch(() => {});
}

/**
 * Login to the **desktop** dashboard at /login.
 * Reference: USER_GUIDE_DESKTOP §"Đăng nhập"
 */
export async function loginDesktop(
  page: Page,
  email = ENV.SELLER_EMAIL,
  password = ENV.SELLER_PASSWORD,
) {
  await loginVia(page, '/login', email, password);
}

/**
 * Login to the **mobile webapp** at /brand/mobile/login.
 * Reference: USER_GUIDE_MOBILE §"Đăng nhập"
 */
export async function loginWebapp(
  page: Page,
  email = ENV.SELLER_EMAIL,
  password = ENV.SELLER_PASSWORD,
) {
  await loginVia(page, '/brand/mobile/login', email, password);
}

/**
 * Login as **admin** (sgconnect admin tổng) via the same /login route.
 * The HomeController dispatches admins to /admin after successful login.
 * Reference: SALES_HANDOVER §6.A
 */
export async function loginAdmin(
  page: Page,
  email = ENV.ADMIN_EMAIL,
  password = ENV.ADMIN_PASSWORD,
) {
  await loginVia(page, '/login', email, password);
}

/**
 * Assert the current page does not contain a Laravel error / exception trace.
 * Mirrors `assertNoPageErrors` from Dusk helpers.
 */
export async function assertNoPageErrors(page: Page, label = 'page') {
  const html = await page.content();
  const markers = [
    'Whoops, looks like something went wrong',
    'Symfony\\Component\\HttpKernel\\Exception',
    'Stack trace:',
    'ErrorException',
    'ParseError',
    'Class &quot;',
    'Undefined variable',
  ];
  for (const m of markers) {
    expect(html, `${label}: page contains error marker "${m}"`).not.toContain(m);
  }
}
