import { test, expect } from '@playwright/test';
import { ENV } from '../playwright.config';
import { loginDesktop, assertNoPageErrors } from '../helpers/auth';

/**
 * Phase 9 — Kho hàng (Desktop only)
 * Plan: docs/E2E_TEST_PLAN.md §Phase 9
 *
 * Reference: USER_GUIDE_DESKTOP §6 (Kho hàng)
 *
 * Reality check (per CLAUDE.md "if 2 docs disagree → fix the wrong one"):
 *
 *   The user guide §6 lists 4 sub-features (Thông tin kho hàng, Xuất nhập tồn,
 *   Nhập hàng / Xuất hàng, Thống kê sản phẩm). In the actual codebase, only
 *   the **first** one exists:
 *
 *   - `Store\WarehouseController` is the only warehouse controller. Its only
 *     working actions are `index` (auto-redirects to the seller's single
 *     warehouse `edit` page), `edit` (renders the contact-info form) and
 *     `update` (PATCH the same form). list/create/store/show/destroy are
 *     all empty stubs (see app/Http/Controllers/Store/WarehouseController.php).
 *   - There are NO routes or controllers for stock movements, import slips,
 *     export slips, or inventory reports. The menu item "Kho hàng" lands on
 *     a single contact-info edit form for the seller's warehouse address —
 *     that's it.
 *
 *   The user guide is updated to reflect actual behavior. The phase only
 *   tests what is wired end-to-end; the missing features are flagged in the
 *   plan as ⚠️ Skipped (not implemented).
 *
 * Webapp: there is no Kho hàng surface in the mobile webapp (USER_GUIDE_MOBILE
 * has no §Kho hàng — desktop-only feature). Phase 9 is desktop-only.
 *
 * Goals:
 *  - GET /store/warehouse → redirects to /store/warehouse/{id}/edit, no PHP error
 *  - The contact-info form renders with the expected required fields
 *  - PATCH /store/warehouse/{id} round-trips a contact_name change end-to-end
 */

test.describe('Phase 9 / Desktop / Kho hàng', () => {
  test('list (index) auto-redirects to the seller warehouse edit form', async ({
    page,
  }) => {
    await loginDesktop(page);

    const res = await page.goto(`${ENV.BASE_APP}/store/warehouse`);
    expect(res?.status() ?? 0, '/store/warehouse status').toBeLessThan(500);
    await assertNoPageErrors(page, '/store/warehouse');

    // index() in WarehouseController always redirects to edit($warehouse->id)
    expect(page.url(), 'should land on the warehouse edit page').toMatch(
      /\/store\/warehouse\/\d+\/edit/,
    );

    // The form must have the 3 required fields per Warehouse::saveFromParams
    await expect(page.locator('input[name="contact_name"]')).toBeVisible();
    await expect(page.locator('input[name="contact_phone"]')).toBeVisible();
    await expect(page.locator('input[name="address"]')).toBeVisible();
  });

  test('PATCH /store/warehouse/{id} round-trips a contact_name change', async ({
    page,
  }) => {
    await loginDesktop(page);

    // Land on the edit page so we can read the warehouse id from the URL
    // and snapshot the original contact_name to restore at the end.
    await page.goto(`${ENV.BASE_APP}/store/warehouse`);
    const editUrl = page.url();
    const idMatch = editUrl.match(/\/store\/warehouse\/(\d+)\/edit/);
    expect(idMatch, 'should be able to extract warehouse id from URL').toBeTruthy();
    const warehouseId = idMatch![1];

    const originalName =
      (await page.locator('input[name="contact_name"]').inputValue()) || 'vBrand';
    const originalPhone =
      (await page.locator('input[name="contact_phone"]').inputValue()) || '0900000000';
    const originalAddress =
      (await page.locator('input[name="address"]').inputValue()) || 'HCM';

    const newName = `E2E Phase 9 ${Date.now()}`;

    // Submit the form via the page request context so the auth cookies +
    // CSRF token are picked up automatically. The form posts via standard
    // method-spoofing (`@method('PATCH')`).
    const csrf =
      (await page
        .locator('input[name="_token"]')
        .first()
        .getAttribute('value')) ?? '';

    const updateRes = await page.request.post(
      `${ENV.BASE_APP}/store/warehouse/${warehouseId}`,
      {
        form: {
          _token: csrf,
          _method: 'PATCH',
          warehouse: warehouseId,
          contact_name: newName,
          contact_phone: originalPhone,
          address: originalAddress,
          city: '',
          district: '',
          ward: '',
        },
      },
    );
    expect(updateRes.status(), 'PATCH response').toBeLessThan(400);

    // Reload and verify the new name is persisted
    await page.goto(editUrl);
    await assertNoPageErrors(page, 'warehouse edit (after update)');
    await expect(page.locator('input[name="contact_name"]')).toHaveValue(newName);

    // Restore original
    await page.request.post(`${ENV.BASE_APP}/store/warehouse/${warehouseId}`, {
      form: {
        _token: csrf,
        _method: 'PATCH',
        warehouse: warehouseId,
        contact_name: originalName,
        contact_phone: originalPhone,
        address: originalAddress,
        city: '',
        district: '',
        ward: '',
      },
    });
  });
});
