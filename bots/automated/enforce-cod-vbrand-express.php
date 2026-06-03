<?php
/**
 * vBrand site standardisation: COD-only payment + vBrand Express-only shipping.
 *
 * Run on the server with `wp --path=/home/<dir>/wordpress eval-file <this>`.
 * Idempotent — safe to run on a fresh site or one that was already configured.
 *
 * Source of truth: docs/USER_GUIDE_DESKTOP.md §"Cửa hàng → Đơn hàng" + CLAUDE.md
 *  > Tất cả vBrand sites phải mặc định có **COD là phương thức thanh toán duy nhất**
 *  > và **vBrand Express là phương thức vận chuyển duy nhất**. Các gateway/method
 *  > khác phải bị disable.
 */

// ----- Payment gateways -----------------------------------------------------

// COD: enable + give it a Vietnamese title that matches the user guide.
$cod = get_option('woocommerce_cod_settings');
if (!is_array($cod)) {
    $cod = [];
}
$cod = array_merge($cod, [
    'enabled'            => 'yes',
    'title'              => 'Thanh toán khi nhận hàng',
    'description'        => 'Trả tiền mặt khi giao hàng.',
    'instructions'       => 'Trả tiền mặt khi giao hàng.',
    'enable_for_methods' => [],
    'enable_for_virtual' => 'yes',
]);
update_option('woocommerce_cod_settings', $cod);
echo "✓ COD enabled\n";

// Disable every other gateway WC knows about. We MERGE into the existing
// settings array (preserving keys like title/description/merchant_id) — some
// gateway constructors read those keys directly and crash if a half-written
// settings array is left behind. We also call init_settings() on each gateway
// first so the defaults from init_form_fields() are populated before we touch
// `enabled`.
$gateways = WC()->payment_gateways->payment_gateways();
$disabled = [];
foreach ($gateways as $id => $gateway) {
    if ($id === 'cod') continue;
    $opt_key = 'woocommerce_' . $id . '_settings';
    $existing = get_option($opt_key);
    if (!is_array($existing)) {
        // Build a default settings array from the gateway's own form_fields
        // so we never write a partial/empty array into the option.
        $existing = [];
        if (method_exists($gateway, 'get_form_fields')) {
            foreach ($gateway->get_form_fields() as $key => $field) {
                $existing[$key] = $field['default'] ?? '';
            }
        }
    }
    $existing['enabled'] = 'no';
    update_option($opt_key, $existing);
    $disabled[] = $id;
}
echo "✓ Disabled gateways: " . implode(', ', $disabled ?: ['(none)']) . "\n";

// ----- Shipping methods -----------------------------------------------------

// Wipe every existing instance from every zone (including the "rest of world"
// zone 0), then attach exactly one vBrand Express instance to zone 0.
$zones = WC_Shipping_Zones::get_zones();
// get_zones() does NOT include the rest-of-world zone 0; load it explicitly.
$zone_objects = [];
foreach ($zones as $z) {
    $zone_objects[] = new WC_Shipping_Zone($z['id']);
}
$zone_objects[] = new WC_Shipping_Zone(0); // rest-of-world

foreach ($zone_objects as $zone) {
    $zid = $zone->get_id();
    foreach ($zone->get_shipping_methods() as $instance_id => $method) {
        if ($method->id === 'vbrand_shipping_method') continue;
        $zone->delete_shipping_method($instance_id);
        echo "  - removed {$method->id} (instance $instance_id) from zone $zid\n";
    }
}

$rest_of_world = new WC_Shipping_Zone(0);
$has_vbrand = false;
foreach ($rest_of_world->get_shipping_methods() as $m) {
    if ($m->id === 'vbrand_shipping_method') {
        $has_vbrand = true;
        // Make sure it's enabled.
        if (!$m->is_enabled()) {
            $rest_of_world->enable_shipping_method($m->instance_id);
        }
        break;
    }
}
if (!$has_vbrand) {
    $instance_id = $rest_of_world->add_shipping_method('vbrand_shipping_method');
    if ($instance_id) {
        echo "✓ Attached vBrand Express to rest-of-world zone (instance $instance_id)\n";
    } else {
        echo "! Failed to attach vBrand Express — is the plugin loaded?\n";
        exit(1);
    }
} else {
    echo "✓ vBrand Express already attached to rest-of-world zone\n";
}

// ----- Storefront visibility -------------------------------------------------

// WooCommerce 8.x ships with "Coming Soon" mode enabled by default on every
// fresh install. When it's on, /shop/, the configured shop page (e.g.
// /thuc-don/), and every product detail page get replaced with the WC
// "Great things are on the horizon" placeholder — even after products are
// imported. Sellers/auditors then think the import failed.
// We force it off here so any new site auto-published as soon as it's
// standardised. Idempotent — no-op if it's already off.
$coming_soon = get_option('woocommerce_coming_soon');
if ($coming_soon !== 'no') {
    update_option('woocommerce_coming_soon', 'no');
    echo "✓ Disabled WooCommerce coming-soon mode\n";
} else {
    echo "✓ Coming-soon already off\n";
}
$store_only = get_option('woocommerce_store_pages_only');
if ($store_only !== 'no') {
    update_option('woocommerce_store_pages_only', 'no');
    echo "✓ Disabled store-pages-only restriction\n";
}

// ----- Verify ---------------------------------------------------------------

// Inspect each gateway's `enabled` flag directly. We can't use
// WC()->payment_gateways->get_available_payment_gateways() here because that
// also runs each gateway's is_available() check which depends on the cart
// state — and inside `wp eval-file` there's no real cart, so is_available()
// returns false even for COD. The on-storefront e2e test verifies the
// runtime checkout instead.
$enabled_gateways = [];
foreach (WC()->payment_gateways->payment_gateways() as $id => $gateway) {
    if ($gateway->enabled === 'yes') {
        $enabled_gateways[] = $id;
    }
}
echo "Enabled gateways: " . implode(', ', $enabled_gateways ?: ['(none)']) . "\n";

$rest_of_world = new WC_Shipping_Zone(0);
$active_methods = array_map(fn($m) => $m->id, $rest_of_world->get_shipping_methods());
echo "Active shipping methods (rest-of-world): " . implode(', ', $active_methods) . "\n";

if ($enabled_gateways !== ['cod']) {
    echo "FAIL: expected exactly [cod] enabled, got [" . implode(',', $enabled_gateways) . "]\n";
    exit(1);
}
if (!in_array('vbrand_shipping_method', $active_methods, true) || count($active_methods) !== 1) {
    echo "FAIL: expected exactly [vbrand_shipping_method] in rest-of-world zone\n";
    exit(1);
}
echo "OK — site is COD-only + vBrand Express-only\n";
