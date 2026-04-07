import { test, expect } from '@playwright/test';
import { ENV } from '../playwright.config';
import { loginDesktop, loginWebapp, assertNoPageErrors } from '../helpers/auth';
import {
  uniqueName,
  csrfHeaders,
  findProductIdByTitle,
  forceDeleteProduct,
} from '../helpers/api';

/**
 * Phase 2 — Sản phẩm (CRUD)
 * Plan: docs/E2E_TEST_PLAN.md §Phase 2
 *
 * Reference:
 *  - USER_GUIDE_DESKTOP §5.1 (Sản phẩm)
 *  - USER_GUIDE_MOBILE  §2 (Sản phẩm)
 *
 * Goals:
 *  - Create / list / search / edit / delete a product on the desktop dashboard
 *  - Same on the mobile webapp
 *  - Verify the product appears on the WP/Woo storefront after create
 *    and disappears after delete (cross-platform sync)
 *
 * Reliability strategy (shared with phase 3+):
 *  - Each test creates a fixture with a unique name (helpers/api.ts uniqueName)
 *  - Each test cleans itself up in afterEach via the brand-app delete endpoint
 *    (helpers/api.ts forceDeleteProduct), bypassing the UI confirm dialog
 *  - Forms are submitted via real UI clicks where the user guide describes a
 *    click, but verification uses the WP REST endpoint directly because that
 *    is the canonical source of truth (same data source the brand-app and
 *    webapp both read from)
 */

// ---------- Desktop dashboard ----------

test.describe('Phase 2 / Desktop / Sản phẩm', () => {
  let createdId: number | null = null;
  const title = uniqueName('e2e-desk');

  test.afterEach(async ({ page }) => {
    if (createdId != null) {
      await forceDeleteProduct(page, createdId);
      createdId = null;
    }
  });

  test('create → list → edit → delete', async ({ page }) => {
    await loginDesktop(page);

    // --- Create via the create form (USER_GUIDE_DESKTOP §5.1) ---
    await page.goto(`${ENV.BASE_APP}/store/products/create`);
    await assertNoPageErrors(page, '/store/products/create');
    await page.locator('input[name="title"]').fill(title);
    await page.locator('input[name="price"]').fill('123000');

    // The desktop form is a regular HTML POST → expect a redirect to /edit/{id}
    await Promise.all([
      page.waitForURL(/\/store\/products\/\d+\/edit/, { timeout: 30_000 }),
      page.locator('form#ProductForm button[type="submit"], form#ProductForm input[type="submit"]').first().click(),
    ]);
    const editUrl = page.url();
    const idMatch = editUrl.match(/\/store\/products\/(\d+)\/edit/);
    expect(idMatch, 'redirect URL should include product id').not.toBeNull();
    createdId = Number(idMatch![1]);
    await assertNoPageErrors(page, 'after create');

    // --- List shows the created product (search by keyword) ---
    await page.goto(`${ENV.BASE_APP}/store/products`);
    await assertNoPageErrors(page, '/store/products');
    // Find via the list endpoint (more reliable than scraping the AJAX UI)
    const foundId = await findProductIdByTitle(page, title);
    expect(foundId, `product "${title}" should appear in the list`).toBe(createdId);

    // --- Edit the title ---
    const newTitle = `${title}-edited`;
    await page.goto(`${ENV.BASE_APP}/store/products/${createdId}/edit`);
    await assertNoPageErrors(page, `/store/products/${createdId}/edit`);
    await page.locator('input[name="title"]').fill(newTitle);
    await Promise.all([
      page.waitForURL(/\/store\/products\/\d+\/edit/, { timeout: 30_000 }),
      page.locator('form#ProductForm button[type="submit"], form#ProductForm input[type="submit"]').first().click(),
    ]);

    // Verify the new title round-trips
    await page.goto(`${ENV.BASE_APP}/store/products/${createdId}/edit`);
    await expect(page.locator('input[name="title"]')).toHaveValue(newTitle);

    // --- Delete via the desktop endpoint ---
    const headers = await csrfHeaders(page);
    const delRes = await page.request.fetch(`${ENV.BASE_APP}/store/products/delete`, {
      method: 'DELETE',
      headers,
      form: { id: String(createdId) },
    });
    expect(delRes.status(), 'DELETE response').toBeLessThan(400);

    // Verify gone from the list
    const stillFound = await findProductIdByTitle(page, newTitle);
    expect(stillFound, 'product should be gone after delete').toBeNull();

    // Mark as cleaned so afterEach doesn't re-delete
    createdId = null;
  });
});

// ---------- Mobile webapp ----------

test.describe('Phase 2 / Mobile webapp / Sản phẩm', () => {
  test.use({ viewport: { width: 430, height: 932 } });

  let createdId: number | null = null;

  test.afterEach(async ({ page }) => {
    if (createdId != null) {
      await forceDeleteProduct(page, createdId);
      createdId = null;
    }
  });

  test('list page renders + has search input', async ({ page }) => {
    await loginWebapp(page);
    await page.goto(`${ENV.BASE_APP}/brand/mobile/products`);
    await assertNoPageErrors(page, '/brand/mobile/products');
    // search input from USER_GUIDE_MOBILE §2 ("ô tìm kiếm phía trên")
    await expect(
      page.locator('input[type="search"], input[name="keyword"], input[placeholder*="Tìm"]').first(),
    ).toBeVisible();
  });

  test('create → list → edit → delete', async ({ page }) => {
    const title = uniqueName('e2e-webapp');

    await loginWebapp(page);

    // --- Open the create form ---
    await page.goto(`${ENV.BASE_APP}/brand/mobile/products/create`);
    await assertNoPageErrors(page, '/brand/mobile/products/create');

    await page.locator('input[name="title"]').fill(title);
    await page.locator('input[name="price"]').fill('99000');

    // The webapp form is AJAX (Alpine submitForm) → POST returns JSON {redirect}
    // and Alpine sets window.location.href. Wait for the redirect to /edit.
    await Promise.all([
      page.waitForURL(/\/brand\/mobile\/products\/\d+\/edit/, { timeout: 30_000 }),
      page.locator('#product-form button[type="submit"]').first().click(),
    ]);

    const m = page.url().match(/\/brand\/mobile\/products\/(\d+)\/edit/);
    expect(m, 'webapp create redirect should include product id').not.toBeNull();
    createdId = Number(m![1]);
    await assertNoPageErrors(page, 'webapp after create');

    // --- Verify it shows up in the list (find by title) ---
    const foundId = await findProductIdByTitle(page, title);
    expect(foundId, `webapp-created "${title}" should be findable`).toBe(createdId);

    // --- Edit via the webapp edit page ---
    const newTitle = `${title}-edited`;
    await page.goto(`${ENV.BASE_APP}/brand/mobile/products/${createdId}/edit`);
    await assertNoPageErrors(page, `/brand/mobile/products/${createdId}/edit`);
    await page.locator('input[name="title"]').fill(newTitle);

    // Webapp update is AJAX (Alpine `@submit.prevent="save($el)"`). Dispatch the
    // submit event programmatically — that's exactly what the header save button
    // in webapp/products/edit.blade.php does, and it avoids a race where clicking
    // the in-form submit button can be intercepted before Alpine binds.
    const updateRes = page.waitForResponse(
      (r) =>
        r.url().includes(`/brand/mobile/products/${createdId}/update`) &&
        r.request().method() === 'POST',
      { timeout: 30_000 },
    );
    await page.evaluate(() => {
      document
        .getElementById('product-form')!
        .dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
    });
    const upd = await updateRes;
    expect(upd.status(), 'webapp update response').toBeLessThan(400);

    // Verify round-trip
    await page.goto(`${ENV.BASE_APP}/brand/mobile/products/${createdId}/edit`);
    await expect(page.locator('input[name="title"]')).toHaveValue(newTitle);

    // --- Delete via the webapp delete endpoint ---
    const headers = await csrfHeaders(page);
    const delRes = await page.request.fetch(
      `${ENV.BASE_APP}/brand/mobile/products/${createdId}/delete`,
      { method: 'POST', headers },
    );
    expect(delRes.status(), 'webapp delete response').toBeLessThan(400);

    // Verify gone
    const stillFound = await findProductIdByTitle(page, newTitle);
    expect(stillFound, 'webapp product should be gone after delete').toBeNull();

    createdId = null;
  });
});

// ---------- Storefront sync (cross-platform) ----------

test.describe('Phase 2 / Storefront sync', () => {
  let createdId: number | null = null;
  const title = uniqueName('e2e-sync');

  test.afterEach(async ({ page }) => {
    if (createdId != null) {
      await forceDeleteProduct(page, createdId);
      createdId = null;
    }
  });

  test('product created on dashboard appears on storefront, removed when deleted', async ({
    page,
  }) => {
    await loginDesktop(page);
    await page.goto(`${ENV.BASE_APP}/store/products/create`);
    await page.locator('input[name="title"]').fill(title);
    await page.locator('input[name="price"]').fill('77000');
    await Promise.all([
      page.waitForURL(/\/store\/products\/\d+\/edit/, { timeout: 30_000 }),
      page.locator('form#ProductForm button[type="submit"]').first().click(),
    ]);
    createdId = Number(page.url().match(/\/store\/products\/(\d+)\/edit/)![1]);

    // --- Verify on storefront (WP/Woo) ---
    // Hit the WP REST endpoint directly — `vbrandsync/v1/product/list?keyword=` is
    // the same endpoint the brand-app uses, so it's the canonical "is this product
    // really in WP" check (and avoids being defeated by full-page cache on /shop).
    let appears = false;
    for (let i = 0; i < 5; i++) {
      const res = await page.request.fetch(
        `${ENV.BASE_SITE}/wp-json/vbrandsync/v1/product/list?keyword=${encodeURIComponent(title)}`,
      );
      const body = await res.text();
      if (body.includes(title)) {
        appears = true;
        break;
      }
      await page.waitForTimeout(500);
    }
    expect(appears, 'storefront WP should expose the new product').toBe(true);

    // --- Delete via desktop endpoint ---
    const headers = await csrfHeaders(page);
    await page.request.fetch(`${ENV.BASE_APP}/store/products/delete`, {
      method: 'DELETE',
      headers,
      form: { id: String(createdId) },
    });
    createdId = null;

    // --- Verify gone from storefront ---
    let cleared = false;
    for (let i = 0; i < 5; i++) {
      const res2 = await page.request.fetch(
        `${ENV.BASE_SITE}/wp-json/vbrandsync/v1/product/list?keyword=${encodeURIComponent(title)}`,
      );
      const body2 = await res2.text();
      if (!body2.includes(title)) {
        cleared = true;
        break;
      }
      await page.waitForTimeout(500);
    }
    expect(cleared, 'storefront should no longer list the deleted product').toBe(true);
  });
});
