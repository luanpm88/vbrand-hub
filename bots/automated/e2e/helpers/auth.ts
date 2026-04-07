import { expect, Page } from '@playwright/test';
import { ENV } from '../playwright.config';

/**
 * Login to the **desktop** dashboard at /login.
 * Reference: USER_GUIDE_DESKTOP §"Đăng nhập"
 */
export async function loginDesktop(
  page: Page,
  email = ENV.SELLER_EMAIL,
  password = ENV.SELLER_PASSWORD,
) {
  await page.goto(`${ENV.BASE_APP}/login`);
  await page.locator('input[name="email"]').fill(email);
  await page.locator('input[name="password"]').fill(password);
  await Promise.all([
    page.waitForURL((u) => !u.pathname.endsWith('/login'), { timeout: 30_000 }),
    page.locator('form button[type="submit"], form input[type="submit"]').first().click(),
  ]);
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
  await page.goto(`${ENV.BASE_APP}/brand/mobile/login`);
  await page.locator('input[name="email"]').fill(email);
  await page.locator('input[name="password"]').fill(password);
  await Promise.all([
    page.waitForURL((u) => !u.pathname.endsWith('/brand/mobile/login'), { timeout: 30_000 }),
    page.locator('form button[type="submit"], form input[type="submit"]').first().click(),
  ]);
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
