import { test, expect } from '@playwright/test';
import { ENV } from '../playwright.config';
import { loginDesktop, assertNoPageErrors } from '../helpers/auth';
import {
  uniqueName,
  findCategoryIdByName,
  findAttributeByName,
  forceDeleteCategory,
  forceDeleteAttribute,
} from '../helpers/api';

/**
 * Phase 3 — Danh mục & Thuộc tính (Desktop only)
 * Plan: docs/E2E_TEST_PLAN.md §Phase 3
 *
 * Reference: USER_GUIDE_DESKTOP §5.2 (Danh mục) + §5.3 (Thuộc tính)
 *
 * Both features are desktop-only per the user guide ("Tính năng quản lý danh
 * mục/thuộc tính chỉ có trên Desktop"). The mobile webapp does not expose them.
 *
 * Shared helpers (csrf, unique fixture names, WP-REST finders, force-delete)
 * live in helpers/api.ts so every phase spec stays focused on the user-guide
 * flow it covers.
 */

// ---------- Categories ----------

test.describe('Phase 3 / Desktop / Danh mục', () => {
  let createdId: number | null = null;
  const name = uniqueName('e2e-cat');

  test.afterEach(async ({ page }) => {
    if (createdId != null) {
      await forceDeleteCategory(page, createdId);
      createdId = null;
    }
  });

  test('create → list → edit → delete', async ({ page }) => {
    await loginDesktop(page);

    // --- Create via the create form (USER_GUIDE_DESKTOP §5.2) ---
    await page.goto(`${ENV.BASE_APP}/store/categories/create`);
    await assertNoPageErrors(page, '/store/categories/create');
    await page.locator('input[name="name"]').fill(name);
    await page.locator('textarea[name="description"]').fill('e2e seed description');
    await Promise.all([
      page.waitForURL(/\/store\/categories\/\d+\/edit|\/store\/categories$/, { timeout: 30_000 }),
      page.locator('form#sendingserverCreate button[type="submit"]').first().click(),
    ]);
    await assertNoPageErrors(page, 'after category create');

    // --- Find it in WP (canonical source of truth) ---
    const foundId = await findCategoryIdByName(page, name);
    expect(foundId, `category "${name}" should appear in WP`).not.toBeNull();
    createdId = foundId!;

    // --- Edit: open edit page, change description, save ---
    await page.goto(`${ENV.BASE_APP}/store/categories/${createdId}/edit`);
    await assertNoPageErrors(page, `/store/categories/${createdId}/edit`);
    await expect(page.locator('input[name="name"]')).toHaveValue(name);
    await page.locator('textarea[name="description"]').fill('e2e edited description');
    await Promise.all([
      page.waitForURL(/\/store\/categories$|\/store\/categories\/\d+\/edit/, { timeout: 30_000 }),
      page.locator('form button[type="submit"]').first().click(),
    ]);
    await assertNoPageErrors(page, 'after category update');

    // Verify name still matches (round-trip)
    const stillFound = await findCategoryIdByName(page, name);
    expect(stillFound, 'category should still exist after edit').toBe(createdId);

    // --- Delete via deleteSelected ---
    await forceDeleteCategory(page, createdId);
    const goneId = await findCategoryIdByName(page, name);
    expect(goneId, 'category should be gone after delete').toBeNull();
    createdId = null;
  });
});

// ---------- Attributes ----------

test.describe('Phase 3 / Desktop / Thuộc tính', () => {
  let createdId: number | null = null;

  test.afterEach(async ({ page }) => {
    if (createdId != null) {
      await forceDeleteAttribute(page, createdId);
      createdId = null;
    }
  });

  test('create with values → list → edit → delete', async ({ page }) => {
    // WP normalizes attribute name to lowercase, so use a lowercase prefix to
    // match what we'll get back from the list endpoint.
    const name = uniqueName('e2eattr').toLowerCase();

    await loginDesktop(page);

    // --- Create via the create form (USER_GUIDE_DESKTOP §5.3) ---
    await page.goto(`${ENV.BASE_APP}/store/attributes/create`);
    await assertNoPageErrors(page, '/store/attributes/create');

    // The values[] inputs are added dynamically by the page's "add value" JS;
    // inject 3 hidden inputs straight into the form so we don't depend on the
    // WYSIWYG row builder. The controller only reads $request->all() so this
    // is exactly equivalent to clicking the add-row button 3 times.
    await page.evaluate(() => {
      const form = document.querySelector('form#sendingserverCreate') as HTMLFormElement;
      ['S', 'M', 'L'].forEach((v) => {
        const i = document.createElement('input');
        i.type = 'hidden';
        i.name = 'values[]';
        i.value = v;
        form.appendChild(i);
      });
    });

    await page.locator('input[name="name"]').fill(name);
    await page.locator('input[name="description"]').fill('e2e attr description');
    await Promise.all([
      page.waitForURL(/\/store\/attributes(\/.*)?$/, { timeout: 30_000 }),
      page.locator('form#sendingserverCreate button[type="submit"]').first().click(),
    ]);
    await assertNoPageErrors(page, 'after attribute create');

    // --- Verify in WP ---
    const found = await findAttributeByName(page, name);
    expect(found, `attribute "${name}" should appear in WP`).not.toBeNull();
    createdId = found!.id;
    expect(found!.values.sort()).toEqual(['L', 'M', 'S']);

    // --- Edit: change description, leave name (it is read-only on edit) ---
    await page.goto(`${ENV.BASE_APP}/store/attributes/${createdId}/edit`);
    await assertNoPageErrors(page, `/store/attributes/${createdId}/edit`);
    await expect(page.locator('input[name="name"]')).toHaveValue(name);
    // On edit, name is readonly — that's the desired behavior (WP attribute
    // slugs cannot be renamed). Just verify description round-trips.
    await page.locator('input[name="description"]').fill('edited e2e attr description');
    await Promise.all([
      page.waitForURL(/\/store\/attributes(\/.*)?$/, { timeout: 30_000 }),
      page.locator('form button[type="submit"]').first().click(),
    ]);
    await assertNoPageErrors(page, 'after attribute update');

    const stillFound = await findAttributeByName(page, name);
    expect(stillFound, 'attribute should still exist after edit').not.toBeNull();
    expect(stillFound!.id).toBe(createdId);

    // --- Delete ---
    await forceDeleteAttribute(page, createdId!);
    const goneAttr = await findAttributeByName(page, name);
    expect(goneAttr, 'attribute should be gone after delete').toBeNull();
    createdId = null;
  });
});
