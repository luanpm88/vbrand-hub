import { defineConfig, devices } from '@playwright/test';

/**
 * vBrand E2E config.
 *
 * Targets are read from env vars so the same suite runs against local / staging / prod.
 *
 *   BASE_APP        Brand Laravel app (default: http://brand.test)
 *   BASE_SITE       WP/Woo storefront (default: http://brand-site.test)
 *   SELLER_EMAIL    Seller account (default: luanpm88@gmail.com)
 *   SELLER_PASSWORD Seller password (default: 123456)
 *   ADMIN_EMAIL     Admin account (default: admin@sgconnect.vn)
 *   ADMIN_PASSWORD  Admin password (default: aA456321@)
 */

export const ENV = {
  BASE_APP: process.env.BASE_APP ?? 'http://brand.test',
  BASE_SITE: process.env.BASE_SITE ?? 'http://brand-site.test',
  SELLER_EMAIL: process.env.SELLER_EMAIL ?? 'admin@acm.com',
  SELLER_PASSWORD: process.env.SELLER_PASSWORD ?? '123456',
  // On local, admin@acm.com is both a seller AND an admin (same DB user has
  // customer_id + an admin relation). On staging/prod, override to
  // admin@sgconnect.vn / aA456321@ via env vars.
  ADMIN_EMAIL: process.env.ADMIN_EMAIL ?? 'admin@acm.com',
  ADMIN_PASSWORD: process.env.ADMIN_PASSWORD ?? '123456',
  // The seller's customer uid (Acelle\Model\Customer::uid) — needed for admin
  // routes scoped by customer (e.g. /admin/store/{customer_uid}/orders/...).
  // Default is admin@acm.com on local. For staging/prod, override per env.
  SELLER_CUSTOMER_UID: process.env.SELLER_CUSTOMER_UID ?? '679906f87e366',
};

export default defineConfig({
  testDir: './tests',
  fullyParallel: false,
  forbidOnly: !!process.env.CI,
  retries: process.env.CI ? 1 : 0,
  workers: 1,
  reporter: [
    ['list'],
    ['html', { open: 'never', outputFolder: 'playwright-report' }],
  ],
  timeout: 60_000,
  expect: { timeout: 10_000 },

  use: {
    baseURL: ENV.BASE_APP,
    trace: 'retain-on-failure',
    screenshot: 'only-on-failure',
    video: 'retain-on-failure',
    ignoreHTTPSErrors: true,
    actionTimeout: 15_000,
    navigationTimeout: 30_000,
  },

  projects: [
    {
      name: 'desktop',
      use: { ...devices['Desktop Chrome'], viewport: { width: 1280, height: 800 } },
    },
    {
      name: 'mobile',
      // iPhone 14 Pro viewport — match Dusk tests
      use: { ...devices['iPhone 14 Pro'] },
    },
  ],
});
