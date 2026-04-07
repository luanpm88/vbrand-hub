import { test, expect } from '@playwright/test';
import { ENV } from '../playwright.config';
import {
  anyProductId,
  customerAddToCart,
  customerCheckout,
  findOrderById,
  forceDeleteOrder,
} from '../helpers/api';

/**
 * Phase 4.1 — Storefront customer checkout (COD + vBrand Express)
 * Plan: docs/E2E_TEST_PLAN.md §Phase 4.1
 *
 * Reference:
 *  - SALES_HANDOVER §1 (Thanh toán: COD; Vận chuyển: vBrand Express)
 *  - CLAUDE.md §"Site standardization" — every vBrand site must have COD as
 *    the ONLY payment gateway and vBrand Express as the ONLY shipping method.
 *  - bots/automated/enforce-cod-vbrand-express.php — the wp-cli script that
 *    enforces this on every site.
 *
 * Goals:
 *  - Cart + checkout pages render with no PHP error
 *  - With a product in the cart, only COD shows as a payment option
 *  - With a product in the cart, only vBrand Express shows as a shipping option
 *  - A real customer can place a COD order via the WC Store API end-to-end
 *    (no admin/seller login — this is the public-facing storefront flow)
 *
 * Why WC Store API instead of clicking the React checkout block:
 *  - The storefront uses the modern WC Blocks checkout (React/Vue), driven
 *    entirely by the JSON Store API. Driving the visual UI is slow and brittle.
 *  - The Store API is the same code path the React UI uses — verifying it
 *    end-to-end proves a real customer can place an order.
 *  - The structural HTML check on /checkout/ still confirms the page renders.
 */

test.describe('Phase 4.1 / Storefront / cart + checkout pages', () => {
  test('cart page renders without PHP error', async ({ page }) => {
    const res = await page.goto(`${ENV.BASE_SITE}/cart/`);
    expect(res?.status() ?? 0).toBeLessThan(500);
    const html = await page.content();
    expect(html, '/cart/').not.toMatch(/Stack trace|Whoops|ErrorException|Undefined array key/);
  });

  test('checkout page renders without PHP error', async ({ page }) => {
    const res = await page.goto(`${ENV.BASE_SITE}/checkout/`);
    expect(res?.status() ?? 0).toBeLessThan(500);
    const html = await page.content();
    expect(html, '/checkout/').not.toMatch(/Stack trace|Whoops|ErrorException|Undefined array key/);
  });
});

test.describe('Phase 4.1 / Storefront / payment + shipping standardization', () => {
  test('with a product in cart, only COD payment + only vBrand Express shipping are advertised', async ({
    page,
  }) => {
    const productId = await anyProductId(page);
    await customerAddToCart(page, productId, 1);

    // The WC Store API /cart endpoint returns the same payment_methods +
    // shipping_rates the React checkout block consumes.
    const res = await page.request.fetch(`${ENV.BASE_SITE}/wp-json/wc/store/v1/cart`);
    expect(res.ok(), 'cart endpoint after add').toBeTruthy();
    const cart = (await res.json()) as {
      payment_methods?: string[];
      shipping_rates?: Array<{
        shipping_rates: Array<{ rate_id: string; method_id: string; name: string; selected: boolean }>;
      }>;
    };

    // --- Payment methods: exactly [cod] ---
    expect(cart.payment_methods, 'payment_methods on cart endpoint').toEqual(['cod']);

    // --- Shipping rates: vBrand Express on every package, nothing else ---
    expect(cart.shipping_rates?.length ?? 0, 'cart should have at least one package').toBeGreaterThan(0);
    for (const pkg of cart.shipping_rates ?? []) {
      const ids = pkg.shipping_rates.map((r) => r.method_id);
      expect(ids, 'shipping rates per package').toEqual(['vbrand_shipping_method']);
      expect(pkg.shipping_rates[0].name).toContain('vBrand Express');
    }
  });
});

test.describe('Phase 4.1 / Storefront / customer can place a COD order', () => {
  let orderId: number | null = null;

  test.afterEach(async ({ page }) => {
    if (orderId != null) {
      await forceDeleteOrder(page, orderId);
      orderId = null;
    }
  });

  test('add-to-cart → checkout → place order → verify order in WP', async ({ page }) => {
    const productId = await anyProductId(page);

    // 1. Customer adds the product to their cart (Store API)
    const cartAfterAdd = await customerAddToCart(page, productId, 1);
    expect(cartAfterAdd.items_count).toBe(1);
    expect(cartAfterAdd.items[0].id).toBe(Number(productId));

    // 2. Customer places the order with COD (Store API checkout endpoint)
    orderId = await customerCheckout(page, {
      firstName: 'E2E',
      lastName: 'Customer',
      phone: '0900000001',
      email: 'e2e-customer@test.local',
    });
    expect(orderId, 'checkout should return an order id').toBeGreaterThan(0);

    // 3. Verify the order really lives in WP and is in a sane post-checkout state
    const wpOrder = await findOrderById(page, orderId);
    expect(wpOrder, `order ${orderId} should be findable in WP after checkout`).not.toBeNull();
    // After checkout COD orders land in `processing` (WC default for COD)
    // — for vBrand we map this to `ordered` via the vbrandsync hooks. Either
    // is acceptable as "the order was created"; the seller workflow phase
    // covers the status transitions.
    expect(['ordered', 'processing', 'on-hold']).toContain(wpOrder!.status);
    expect(wpOrder!.first_name).toBe('E2E');
    expect(wpOrder!.phone).toBe('0900000001');
  });
});
