/**
 * Import scraped shop data into WooCommerce via vbrandsync REST API
 *
 * Reads data from bots/scrape/shops/<shop>/ and imports into a WooCommerce site
 * via the vbrandsync plugin REST API. Works with both local and remote sites.
 *
 * Usage:
 *   node import-to-woocommerce.js <site_url> <shop_dir> [options]
 *
 * Examples:
 *   node import-to-woocommerce.js http://localhost:8080 /path/to/shops/nike-flagship-store/
 *   node import-to-woocommerce.js https://nike.b-teka.com /path/to/shops/nike-flagship-store/ --clean
 *   node import-to-woocommerce.js https://nike.b-teka.com /path/to/shops/nike-flagship-store/ --limit 50
 *
 * Options:
 *   --clean           Clean all existing products/categories before import
 *   --limit <n>       Max products to import (default: all)
 *   --skip-images     Skip image import (faster for testing)
 *   --dry-run         Show what would be imported without actually importing
 */

const fs = require('fs');
const path = require('path');

// === PARSE ARGS ===
const args = process.argv.slice(2);
let siteUrl = '';
let shopDir = '';
let opts = { clean: false, limit: 0, skipImages: false, dryRun: false, overwriteSiteInfo: false };

for (let i = 0; i < args.length; i++) {
    switch (args[i]) {
        case '--clean': opts.clean = true; break;
        case '--limit': opts.limit = parseInt(args[++i]); break;
        case '--skip-images': opts.skipImages = true; break;
        case '--dry-run': opts.dryRun = true; break;
        case '--overwrite-site-info': opts.overwriteSiteInfo = true; break;
        default:
            if (!args[i].startsWith('--')) {
                if (!siteUrl) siteUrl = args[i].replace(/\/$/, '');
                else if (!shopDir) shopDir = args[i];
            }
    }
}

if (!siteUrl || !shopDir) {
    console.error('Usage: node import-to-woocommerce.js <site_url> <shop_dir> [--clean] [--limit N] [--skip-images] [--dry-run]');
    console.error('');
    console.error('Examples:');
    console.error('  node import-to-woocommerce.js https://nike.b-teka.com ./shops/nike-flagship-store/');
    console.error('  node import-to-woocommerce.js http://localhost:8080 ./shops/nike-flagship-store/ --clean --limit 50');
    process.exit(1);
}

const API_BASE = `${siteUrl}/wp-json/vbrandsync/v1`;

// === HELPERS ===
async function apiGet(endpoint) {
    const url = `${API_BASE}${endpoint}`;
    const res = await fetch(url);
    if (!res.ok) throw new Error(`GET ${endpoint} → ${res.status} ${res.statusText}`);
    return res.json();
}

async function apiPost(endpoint, data) {
    const url = `${API_BASE}${endpoint}`;
    const formData = new URLSearchParams();
    for (const [key, value] of Object.entries(data)) {
        if (Array.isArray(value)) {
            value.forEach((v, i) => formData.append(`${key}[${i}]`, v));
        } else if (value !== null && value !== undefined) {
            formData.append(key, String(value));
        }
    }
    const res = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: formData.toString(),
    });
    if (!res.ok) {
        const text = await res.text().catch(() => '');
        throw new Error(`POST ${endpoint} → ${res.status}: ${text.substring(0, 200)}`);
    }
    return res.json();
}

function sleep(ms) { return new Promise(r => setTimeout(r, ms)); }

// === MAIN ===
async function main() {
    console.log('📦 WooCommerce Import via vbrandsync API');
    console.log(`   Site: ${siteUrl}`);
    console.log(`   Shop: ${shopDir}`);
    if (opts.clean) console.log('   Mode: CLEAN + IMPORT');
    if (opts.limit) console.log(`   Limit: ${opts.limit} products`);
    if (opts.skipImages) console.log('   Skip images: YES');
    if (opts.dryRun) console.log('   DRY RUN: YES');
    console.log('');

    // ============================================================
    // STEP 0: Verify connection
    // ============================================================
    console.log('🔌 Checking API connection...');
    try {
        const status = await apiGet('/import/status');
        console.log(`   ✓ Connected: ${status.site_name} (${status.site_url})`);
        console.log(`   Current: ${status.products} products, ${status.categories} categories`);
    } catch(e) {
        console.error(`   ✗ Cannot connect to ${API_BASE}`);
        console.error(`   Error: ${e.message}`);
        process.exit(1);
    }

    // ============================================================
    // STEP 1: Load scraped data
    // ============================================================
    console.log('\n📂 Loading scraped data...');
    const shopInfoPath = path.join(shopDir, 'shop-info.json');
    const productsPath = path.join(shopDir, 'products.json');
    const categoriesPath = path.join(shopDir, 'categories.json');

    if (!fs.existsSync(productsPath)) {
        console.error(`   ✗ products.json not found at ${productsPath}`);
        process.exit(1);
    }

    const shopInfo = fs.existsSync(shopInfoPath) ? JSON.parse(fs.readFileSync(shopInfoPath, 'utf8')) : null;
    const allProducts = JSON.parse(fs.readFileSync(productsPath, 'utf8'));
    const categories = fs.existsSync(categoriesPath) ? JSON.parse(fs.readFileSync(categoriesPath, 'utf8')) : [];

    const products = opts.limit > 0 ? allProducts.slice(0, opts.limit) : allProducts;
    console.log(`   Shop info: ${shopInfo ? shopInfo.name : 'N/A'}`);
    console.log(`   Products: ${products.length}${opts.limit ? ` (limited from ${allProducts.length})` : ''}`);
    console.log(`   Categories: ${categories.length}`);

    if (opts.dryRun) {
        console.log('\n🏁 DRY RUN — no changes made.');
        return;
    }

    // ============================================================
    // STEP 2: Clean existing data
    // ============================================================
    if (opts.clean) {
        console.log('\n🧹 Cleaning existing data...');
        const cleanResult = await apiPost('/import/clean', {});
        console.log(`   Deleted: ${cleanResult.deleted_products} products, ${cleanResult.deleted_categories} categories, ${cleanResult.deleted_media} media`);
    }

    // ============================================================
    // STEP 3: Import shop info
    // When importing source-shop data into an already-branded destination
    // (e.g. ductrico data → khomaynenkhi.com), we MUST NOT overwrite the
    // destination's blogname/logo with the source's. By default we only
    // track metadata (shop_id / seller_id / shop_url). Pass --overwrite-site-info
    // to also set blogname + logo from the source (use when migrating an entire
    // shop identity to a new domain).
    // ============================================================
    if (shopInfo) {
        console.log('\n🏪 Importing shop info...');
        const shopData = {
            shop_id: shopInfo.shopId || '',
            seller_id: shopInfo.sellerId || '',
            shop_url: shopInfo.url || '',
        };
        if (opts.overwriteSiteInfo) {
            shopData.name = shopInfo.name || '';
            const logoUrl = shopInfo.logo || shopInfo.avatar || '';
            if (logoUrl && !opts.skipImages) shopData.logo_url = logoUrl;
            console.log('   ⚠ --overwrite-site-info: replacing destination blogname/logo with source');
        }
        const result = await apiPost('/import/shop-info', shopData);
        console.log(`   Updated: ${result.updated.join(', ')}`);
    }

    // ============================================================
    // STEP 4: Import categories
    // ============================================================
    const categoryMap = {}; // name → WP term ID
    if (categories.length > 0) {
        console.log(`\n📂 Importing ${categories.length} categories...`);
        for (const cat of categories) {
            try {
                const result = await apiPost('/import/category', { name: cat.name });
                categoryMap[cat.name] = result.id;
                console.log(`   ${result.action}: "${cat.name}" → ID ${result.id}`);
            } catch(e) {
                console.log(`   ✗ "${cat.name}": ${e.message}`);
            }
        }
    }

    // ============================================================
    // STEP 5: Import products
    // ============================================================
    console.log(`\n📦 Importing ${products.length} products...`);
    let imported = 0;
    let skipped = 0;
    let failed = 0;

    for (let i = 0; i < products.length; i++) {
        const p = products[i];

        // Build image URLs — gallery + primary
        let imageUrl = '';
        let galleryUrls = [];
        if (!opts.skipImages) {
            const normalize = (u) => {
                if (!u) return '';
                let url = u;
                if (url.startsWith('//')) url = 'https:' + url;
                return url.replace(/_\d+x\d+q\d+/, '_720x720q80'); // Lazada: get higher res
            };
            imageUrl = normalize(p.image || '');
            galleryUrls = (p.images || []).map(normalize).filter(Boolean);
            // Ensure primary is first; de-dupe
            if (imageUrl && !galleryUrls.includes(imageUrl)) galleryUrls.unshift(imageUrl);
            galleryUrls = [...new Set(galleryUrls)];
        }

        // Standard format (cross-platform) with Lazada backward-compat fallbacks.
        // p.price can be a number (API scrape) or a localized string like "₫ 450.000"
        // (DOM fallback). Strip currency + separators before sending — WC parses "." as
        // decimal separator and would otherwise turn "450.000" into 450 VND.
        const toIntPrice = (v) => {
            if (typeof v === 'number') return v;
            if (!v) return 0;
            const digits = String(v).replace(/[^\d]/g, '');
            return digits ? parseInt(digits, 10) : 0;
        };
        const regularPrice = toIntPrice(p.price) || p.originalPriceRaw || p.priceRaw || 0;
        const salePrice = p.salePrice ?? (
            p.priceRaw && p.originalPriceRaw && p.priceRaw < p.originalPriceRaw ? p.priceRaw : null
        );
        const soldCount = p.sold ?? p.soldLastMonth ?? 0;

        const categoryIds = (p.categories || [])
            .map(name => categoryMap[name])
            .filter(Boolean);

        const productData = {
            title: p.name || p.nameRaw || '',
            description: p.description || '',
            short_description: p.short_description || '',
            slug: p.slug || '',
            price: regularPrice || '',
            discount_price: salePrice || '',
            product_id: p.id || '',
            source: p.source || 'unknown',
            sku: p.sku || '',
            rating: p.rating || '',
            review_count: p.reviews || '',
            sold_count: soldCount || '',
            brand_id: p.brandId || '',
            product_url: p.url || '',
            in_stock: p.in_stock === false ? '0' : '1',
        };

        if (imageUrl) {
            productData.image_url = imageUrl;
        }
        if (galleryUrls.length) {
            productData.image_urls = galleryUrls;
        }
        if (categoryIds.length) {
            productData.category_ids = categoryIds;
        }

        try {
            const result = await apiPost('/import/product', productData);
            if (result.action === 'created') imported++;
            else if (result.action === 'skipped') skipped++;
        } catch(e) {
            failed++;
            if (failed <= 3) console.log(`   ✗ "${(p.name || '').substring(0, 50)}": ${e.message.substring(0, 100)}`);
        }

        if ((i + 1) % 25 === 0 || i === products.length - 1) {
            console.log(`   ${i + 1}/${products.length} (imported: ${imported}, skipped: ${skipped}, failed: ${failed})`);
        }

        // Small delay to avoid overwhelming the server
        if ((i + 1) % 10 === 0) await sleep(100);
    }

    console.log(`\n   ✅ Import complete: ${imported} imported, ${skipped} skipped, ${failed} failed`);

    // ============================================================
    // STEP 6: Verify
    // ============================================================
    console.log('\n📊 Verifying...');
    await sleep(1000);
    const finalStatus = await apiGet('/import/status');
    console.log(`   Site: ${finalStatus.site_name} (${finalStatus.site_url})`);
    console.log(`   Products: ${finalStatus.products}`);
    console.log(`   Categories: ${finalStatus.categories}`);

    // Storefront sanity check: WC's "Coming Soon" mode replaces /shop/ and the
    // shop page with a "Great things are on the horizon" placeholder, so a
    // successful API import can still leave a site that LOOKS empty to a
    // customer. Hit the homepage and warn loudly if we see the placeholder.
    try {
        const home = await fetch(siteUrl, { redirect: 'follow' });
        const html = await home.text();
        if (/Great things are on the horizon|woocommerce[_-]coming[_-]soon/i.test(html)) {
            console.log('\n⚠️  WARNING: WooCommerce Coming Soon mode is ENABLED on this site.');
            console.log('   Imported products will be HIDDEN from /shop/ and product pages.');
            console.log('   Fix on the server with:');
            console.log(`     wp --path=/home/<DIR_NAME>/wordpress option update woocommerce_coming_soon no`);
            console.log(`     wp --path=/home/<DIR_NAME>/wordpress option update woocommerce_store_pages_only no`);
            console.log(`     wp --path=/home/<DIR_NAME>/wordpress cache flush`);
            console.log('   Or run bots/automated/enforce-cod-vbrand-express.php which now disables it too.');
        } else {
            console.log('   Storefront: visible (coming-soon off) ✓');
        }
    } catch (e) {
        console.log(`   Storefront check skipped: ${e.message}`);
    }

    console.log('\n✅ Done!');
}

main().catch(e => { console.error('❌', e.message); process.exit(1); });
