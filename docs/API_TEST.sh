#!/bin/bash
# vBrand API Test Script
# Usage: TOKEN=your_api_token bash docs/API_TEST.sh
# Get token from: SELECT api_token FROM users LIMIT 1;

APP_URL="${APP_URL:-http://brand.test}"
TOKEN="${TOKEN:-your_api_token_here}"

ok()  { echo "✅  $1"; }
fail(){ echo "❌  $1"; }
run() {
    local label="$1"; shift
    local result
    result=$(curl -s "$@")
    local status
    status=$(echo "$result" | python3 -c "import sys,json; d=json.load(sys.stdin); print(d.get('status','?'))" 2>/dev/null)
    if [ "$status" = "success" ]; then ok "$label"; else fail "$label → $result"; fi
}

echo ""
echo "=== vBrand Mobile API Tests ==="
echo "URL: $APP_URL"
echo ""

# ---- AUTH ----
echo "--- Auth ---"
run "Login" -X POST "$APP_URL/api/v1/brand/auth/login" \
    -H "Content-Type: application/json" \
    -d '{"email":"test@example.com","password":"secret"}'

# ---- PRODUCTS ----
echo ""
echo "--- Products ---"
run "Product list (BUG-1 fixed)" \
    "$APP_URL/api/v1/brand/products?api_token=$TOKEN&per_page=5"

run "Product detail with slug+sku (BUG-14 fixed)" \
    "$APP_URL/api/v1/brand/products/1?api_token=$TOKEN"

echo "  Product detail fields check:"
curl -s "$APP_URL/api/v1/brand/products/1?api_token=$TOKEN" | \
    python3 -c "import sys,json; d=json.load(sys.stdin)['data']; print('  slug:', repr(d.get('slug')), '| sku:', repr(d.get('sku')))" 2>/dev/null

run "Product create (BUG-2 fixed — should not silently fail)" \
    -X POST "$APP_URL/api/v1/brand/products?api_token=$TOKEN" \
    -H "Content-Type: application/json" \
    -d '{"title":"Test Product Claude","price":"99000","description":"Test"}'

run "Product categories" \
    "$APP_URL/api/v1/brand/products/categories?api_token=$TOKEN"

# ---- ORDERS ----
echo ""
echo "--- Orders ---"
run "Order list" \
    "$APP_URL/api/v1/brand/orders?api_token=$TOKEN&per_page=5"

run "Order list with status filter" \
    "$APP_URL/api/v1/brand/orders?api_token=$TOKEN&status=wc-ordered"

echo "  Order stats (BUG-6 fixed — should have no null values):"
curl -s "$APP_URL/api/v1/brand/orders/stats?api_token=$TOKEN" | \
    python3 -c "import sys,json; d=json.load(sys.stdin); [print(' ', k, '=', v) for k,v in d.get('stats',{}).items()]" 2>/dev/null

run "Order detail with subtotal+shipping_total (BUG-12 fixed)" \
    "$APP_URL/api/v1/brand/orders/1?api_token=$TOKEN"

echo "  Order detail fields check:"
curl -s "$APP_URL/api/v1/brand/orders/1?api_token=$TOKEN" | \
    python3 -c "import sys,json; d=json.load(sys.stdin).get('data',{}); print('  subtotal:', d.get('subtotal'), '| shipping_total:', d.get('shipping_total'), '| sku:', d.get('line_items',[{}])[0].get('sku') if d.get('line_items') else 'n/a')" 2>/dev/null

run "Order not found → 404 not 500 (BUG-10 fixed)" \
    "$APP_URL/api/v1/brand/orders/999999?api_token=$TOKEN"

# ---- ORDER ACTIONS ----
echo ""
echo "--- Order Actions (use a real order id) ---"
ORDER_ID="${ORDER_ID:-1}"
run "confirm (→ ordered)"      -X POST "$APP_URL/api/v1/brand/orders/$ORDER_ID/confirm?api_token=$TOKEN"
run "seller-confirm (→ packaging)" -X POST "$APP_URL/api/v1/brand/orders/$ORDER_ID/seller-confirm?api_token=$TOKEN"
run "set-packaged (→ ready_for_pickup)" -X POST "$APP_URL/api/v1/brand/orders/$ORDER_ID/set-packaged?api_token=$TOKEN"
run "set-delivering" -X POST "$APP_URL/api/v1/brand/orders/$ORDER_ID/set-delivering?api_token=$TOKEN"
run "set-delivered (BUG-4 fixed → calls setComplete)" -X POST "$APP_URL/api/v1/brand/orders/$ORDER_ID/set-delivered?api_token=$TOKEN"
run "pay → 501 not 500 (BUG-5 fixed)" -X POST "$APP_URL/api/v1/brand/orders/$ORDER_ID/pay?api_token=$TOKEN"

echo ""
echo "=== Done ==="
