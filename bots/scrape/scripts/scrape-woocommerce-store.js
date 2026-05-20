/**
 * WooCommerce Store API Scraper
 *
 * Scrapes ANY WooCommerce site that exposes the public Store API at
 *   /wp-json/wc/store/v1/products
 *   /wp-json/wc/store/v1/products/categories
 *
 * Output matches the standard format expected by import-to-woocommerce.js:
 *   shops/<slug>/
 *     shop-info.json
 *     categories.json
 *     products.json
 *     images/products/<product_id>.jpg (downloaded primary image)
 *
 * Unlike Lazada/Shopee scrapers, this is a pure HTTP scraper — no browser needed.
 *
 * Usage:
 *   node scrape-woocommerce-store.js <site_url> [options]
 *
 * Examples:
 *   node scrape-woocommerce-store.js https://ductrico.com
 *   node scrape-woocommerce-store.js https://ductrico.com --slug ductrico --limit 10
 *
 * Options:
 *   --slug <name>     Output folder slug (default: derived from hostname)
 *   --output <dir>    Override output directory (default: bots/scrape/shops/<slug>/)
 *   --limit <n>       Max products (default: all)
 *   --per-page <n>    Items per page (default: 50, max 100)
 *   --no-images       Skip image downloads (faster)
 *   --concurrency <n> Parallel image downloads (default: 5)
 */

const fs = require('fs');
const path = require('path');
const https = require('https');
const http = require('http');
const { URL } = require('url');

const BOTS_DIR = '/Users/luan/apps/vbrand/bots/scrape/shops';

const args = process.argv.slice(2);
let siteUrl = '';
let opts = { slug: '', output: '', limit: 0, perPage: 50, noImages: false, concurrency: 5 };

for (let i = 0; i < args.length; i++) {
    switch (args[i]) {
        case '--slug':        opts.slug = args[++i]; break;
        case '--output':      opts.output = args[++i]; break;
        case '--limit':       opts.limit = parseInt(args[++i]); break;
        case '--per-page':    opts.perPage = parseInt(args[++i]); break;
        case '--no-images':   opts.noImages = true; break;
        case '--concurrency': opts.concurrency = parseInt(args[++i]); break;
        default:
            if (!args[i].startsWith('--')) siteUrl = args[i].replace(/\/$/, '');
    }
}

if (!siteUrl) {
    console.error('Usage: node scrape-woocommerce-store.js <site_url> [--slug name] [--limit N] [--no-images]');
    process.exit(1);
}

const slug = (opts.slug || new URL(siteUrl).hostname.replace(/[^a-z0-9-]/gi, '-')).toLowerCase();
const outputDir = opts.output || path.join(BOTS_DIR, slug);

const STORE_API = `${siteUrl}/wp-json/wc/store/v1`;
const WP_API    = `${siteUrl}/wp-json/wp/v2`;

function ensureDir(d) { if (!fs.existsSync(d)) fs.mkdirSync(d, { recursive: true }); }
function saveJson(fp, data) { fs.writeFileSync(fp, JSON.stringify(data, null, 2)); console.log(`  ✓ ${path.basename(fp)}`); }
function decodeHtml(s = '') {
    return String(s)
        .replace(/&#8211;/g, '–').replace(/&#8217;/g, '’')
        .replace(/&amp;/g, '&').replace(/&quot;/g, '"').replace(/&#039;/g, "'")
        .replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&nbsp;/g, ' ');
}

function httpJson(url) {
    return new Promise((resolve, reject) => {
        const lib = url.startsWith('https') ? https : http;
        const req = lib.get(url, { headers: { 'User-Agent': 'Mozilla/5.0 vBrand-scraper/1.0' } }, (res) => {
            const chunks = [];
            res.on('data', c => chunks.push(c));
            res.on('end', () => {
                const body = Buffer.concat(chunks).toString('utf8');
                if (res.statusCode >= 400) return reject(new Error(`HTTP ${res.statusCode} ${url}\n${body.substring(0, 300)}`));
                try { resolve({ data: JSON.parse(body), headers: res.headers }); }
                catch (e) { reject(new Error(`Bad JSON from ${url}: ${e.message}`)); }
            });
        });
        req.on('error', reject);
        req.setTimeout(30000, () => { req.destroy(new Error(`Timeout ${url}`)); });
    });
}

function downloadImage(url, filePath) {
    return new Promise((resolve) => {
        if (!url) return resolve(false);
        if (fs.existsSync(filePath)) return resolve(true);
        const lib = url.startsWith('https') ? https : http;
        const req = lib.get(url, { headers: { 'User-Agent': 'Mozilla/5.0' } }, (res) => {
            if (res.statusCode === 301 || res.statusCode === 302) {
                return downloadImage(res.headers.location, filePath).then(resolve);
            }
            if (res.statusCode !== 200) { res.resume(); return resolve(false); }
            const f = fs.createWriteStream(filePath);
            res.pipe(f);
            f.on('finish', () => f.close(() => resolve(true)));
            f.on('error', () => resolve(false));
        });
        req.on('error', () => resolve(false));
        req.setTimeout(20000, () => { req.destroy(); resolve(false); });
    });
}

async function pool(items, worker, n) {
    const results = new Array(items.length);
    let idx = 0;
    async function next() {
        while (idx < items.length) {
            const i = idx++;
            try { results[i] = await worker(items[i], i); } catch (e) { results[i] = null; }
        }
    }
    await Promise.all(Array.from({ length: n }, next));
    return results;
}

async function main() {
    console.log('🛒 WooCommerce Store API Scraper');
    console.log(`   Site:   ${siteUrl}`);
    console.log(`   Slug:   ${slug}`);
    console.log(`   Output: ${outputDir}`);
    console.log('');

    ensureDir(outputDir);
    ensureDir(path.join(outputDir, 'images'));
    ensureDir(path.join(outputDir, 'images', 'products'));

    // ============================================================
    // STEP 1: Shop info — site metadata from /wp-json/
    // ============================================================
    console.log('🏪 Fetching site info...');
    let siteName = slug;
    let siteDesc = '';
    try {
        const root = await httpJson(`${siteUrl}/wp-json/`);
        siteName = decodeHtml(root.data.name || siteName);
        siteDesc = decodeHtml(root.data.description || '');
        console.log(`   ${siteName}`);
        if (siteDesc) console.log(`   "${siteDesc}"`);
    } catch (e) {
        console.log(`   ⚠ Could not fetch site root: ${e.message}`);
    }

    const shopInfo = {
        name: siteName,
        description: siteDesc,
        url: siteUrl,
        source: 'woocommerce',
        scrapedAt: new Date().toISOString(),
    };
    saveJson(path.join(outputDir, 'shop-info.json'), shopInfo);

    // ============================================================
    // STEP 2: Categories
    // ============================================================
    console.log('\n📂 Fetching categories...');
    let categories = [];
    try {
        // Walk pagination — WC Store API caps at 100/page
        let page = 1, total = Infinity;
        while (categories.length < total) {
            const url = `${STORE_API}/products/categories?per_page=100&page=${page}`;
            const { data, headers } = await httpJson(url);
            if (page === 1) total = parseInt(headers['x-wp-total'] || data.length);
            if (!data.length) break;
            categories.push(...data);
            page++;
            if (page > 50) break;
        }
        categories = categories.map(c => ({
            id: c.id,
            name: decodeHtml(c.name),
            slug: c.slug,
            parent: c.parent || 0,
            count: c.count,
            description: decodeHtml(c.description || ''),
            image: c.image && c.image.src ? c.image.src : '',
        }));
        console.log(`   ${categories.length} categories`);
    } catch (e) {
        console.log(`   ⚠ ${e.message}`);
    }
    saveJson(path.join(outputDir, 'categories.json'), categories);

    // ============================================================
    // STEP 3: Products — walk pagination
    // ============================================================
    console.log('\n📦 Fetching products...');
    const perPage = Math.min(opts.perPage || 50, 100);
    let allProducts = [];
    let page = 1, totalProducts = Infinity;
    while (allProducts.length < totalProducts) {
        const url = `${STORE_API}/products?per_page=${perPage}&page=${page}&orderby=date&order=desc`;
        try {
            const { data, headers } = await httpJson(url);
            if (page === 1) {
                totalProducts = parseInt(headers['x-wp-total'] || data.length);
                console.log(`   Total: ${totalProducts} products`);
            }
            if (!data.length) break;
            allProducts.push(...data);
            console.log(`   page ${page}: +${data.length} (running total ${allProducts.length}/${totalProducts})`);
            page++;
            if (opts.limit && allProducts.length >= opts.limit) break;
            if (page > 200) { console.log('   ⚠ Hit page cap (200)'); break; }
        } catch (e) {
            console.log(`   ✗ page ${page}: ${e.message}`);
            break;
        }
    }

    if (opts.limit) allProducts = allProducts.slice(0, opts.limit);

    // Normalize to standard format expected by import-to-woocommerce.js
    const normalized = allProducts.map(p => {
        const primaryImage = (p.images && p.images[0]) ? p.images[0].src : '';
        const allImages = (p.images || []).map(img => img.src).filter(Boolean);
        const regularPrice = p.prices ? parseInt(p.prices.regular_price || p.prices.price || 0, 10) : 0;
        const salePrice = p.prices && p.prices.sale_price && p.prices.sale_price !== p.prices.regular_price
            ? parseInt(p.prices.sale_price, 10) : null;
        return {
            id: String(p.id),
            name: decodeHtml(p.name || ''),
            slug: p.slug,
            sku: p.sku || '',
            description: p.description || '',
            short_description: p.short_description || '',
            price: regularPrice,
            priceRaw: regularPrice,
            salePrice,
            originalPriceRaw: regularPrice,
            currency: p.prices ? p.prices.currency_code : 'VND',
            image: primaryImage,
            images: allImages,
            categories: (p.categories || []).map(c => decodeHtml(c.name)),
            rating: parseFloat(p.average_rating || 0) || 0,
            reviews: p.review_count || 0,
            sold: 0,
            url: p.permalink,
            source: 'woocommerce',
            type: p.type || 'simple',
            in_stock: p.is_in_stock !== false,
        };
    });

    console.log(`\n   ✅ ${normalized.length} products normalized`);
    saveJson(path.join(outputDir, 'products.json'), normalized);

    // ============================================================
    // STEP 4: Download primary images
    // ============================================================
    if (!opts.noImages) {
        console.log('\n🖼  Downloading primary images...');
        const toDownload = normalized.filter(p => p.image);
        let done = 0, ok = 0;
        await pool(toDownload, async (p) => {
            const ext = (path.extname(new URL(p.image).pathname) || '.jpg').split('?')[0].substring(0, 5);
            const fp = path.join(outputDir, 'images', 'products', `${p.id}${ext || '.jpg'}`);
            const success = await downloadImage(p.image, fp);
            done++;
            if (success) ok++;
            if (done % 10 === 0 || done === toDownload.length) {
                console.log(`   ${done}/${toDownload.length} (ok: ${ok})`);
            }
            if (success) p.localImage = path.relative(outputDir, fp);
        }, opts.concurrency);
        saveJson(path.join(outputDir, 'products.json'), normalized);
        console.log(`   Images: ${ok}/${toDownload.length} downloaded`);
    } else {
        console.log('\n   (Skipped image downloads)');
    }

    // ============================================================
    // STEP 5: Summary
    // ============================================================
    console.log('\n' + '='.repeat(60));
    console.log('✅ Scrape complete');
    console.log(`   Site:       ${siteName}`);
    console.log(`   Products:   ${normalized.length}`);
    console.log(`   Categories: ${categories.length}`);
    console.log(`   Output:     ${outputDir}`);
    console.log('='.repeat(60));
}

main().catch(e => { console.error('❌', e.message); process.exit(1); });
