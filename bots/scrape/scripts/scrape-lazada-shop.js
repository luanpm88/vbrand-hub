/**
 * Lazada Mall Shop Scraper v2
 *
 * Scrape shop info, categories, ALL products (via internal API), images from Lazada Mall shops.
 * Uses Lazada's internal shop API for rich product data (price, rating, reviews, SKU, etc.)
 * No login required — Lazada is accessible with headless browser + stealth plugin.
 *
 * Usage:
 *   cd /private/tmp && node <path>/scrape-lazada-shop.js <shop_url> [options]
 *
 * Examples:
 *   node scrape-lazada-shop.js "https://www.lazada.vn/shop/nike-flagship-store/"
 *   node scrape-lazada-shop.js "https://www.lazada.vn/shop/nike-flagship-store/" --products --limit 20
 *   node scrape-lazada-shop.js "https://www.lazada.vn/shop/nike-flagship-store/" --all
 *
 * Options:
 *   --shop-info       Scrape shop info only
 *   --products        Scrape all products
 *   --categories      Scrape categories
 *   --all             Everything (default)
 *   --output <dir>    Output directory
 *   --limit <n>       Max products (default: all)
 */

const puppeteer = require('puppeteer-extra');
const StealthPlugin = require('puppeteer-extra-plugin-stealth');
const fs = require('fs');
const path = require('path');
const https = require('https');
const http = require('http');

puppeteer.use(StealthPlugin());

const BOTS_DIR = '/Users/luan/apps/vbrand/bots/scrape/shops';

// === PARSE ARGS ===
const args = process.argv.slice(2);
let shopUrl = '';
let opts = {
    shopInfo: false, products: false, categories: false, all: true,
    output: '', limit: 0,
};

for (let i = 0; i < args.length; i++) {
    switch (args[i]) {
        case '--shop-info': opts.shopInfo = true; opts.all = false; break;
        case '--products': opts.products = true; opts.all = false; break;
        case '--categories': opts.categories = true; opts.all = false; break;
        case '--all': opts.all = true; break;
        case '--output': opts.output = args[++i]; break;
        case '--limit': opts.limit = parseInt(args[++i]); break;
        default: if (!args[i].startsWith('--')) shopUrl = args[i];
    }
}

if (opts.all) { opts.shopInfo = true; opts.products = true; opts.categories = true; }

if (!shopUrl) {
    console.error('Usage: node scrape-lazada-shop.js <shop_url> [--all|--products|--shop-info|--categories] [--limit N]');
    process.exit(1);
}

// Extract shop slug from URL
function extractShopSlug(url) {
    const m = url.match(/lazada\.vn\/shop\/([^\/\?]+)/);
    return m ? m[1] : 'unknown_shop';
}

const shopSlug = extractShopSlug(shopUrl);
const shopSlugSafe = shopSlug.replace(/[^a-zA-Z0-9_-]/g, '_');
const outputDir = opts.output || path.join(BOTS_DIR, shopSlugSafe);

// === HELPERS ===
function ensureDir(d) { if (!fs.existsSync(d)) fs.mkdirSync(d, { recursive: true }); }
function saveJson(fp, data) { fs.writeFileSync(fp, JSON.stringify(data, null, 2)); console.log(`  ✓ ${path.basename(fp)}`); }
function sleep(ms) { return new Promise(r => setTimeout(r, ms)); }

function downloadImage(url, filePath) {
    return new Promise((resolve) => {
        if (!url) return resolve(false);
        let u = url.startsWith('//') ? 'https:' + url : url;
        if (!u.startsWith('http')) return resolve(false);
        // Get higher res version
        u = u.replace(/_\d+x\d+q\d+/, '_720x720q80');
        const proto = u.startsWith('https') ? https : http;
        const file = fs.createWriteStream(filePath);
        proto.get(u, { headers: { 'User-Agent': 'Mozilla/5.0', 'Referer': 'https://www.lazada.vn/' } }, (res) => {
            if ([301, 302].includes(res.statusCode)) {
                file.close(); try { fs.unlinkSync(filePath); } catch(e) {}
                return downloadImage(res.headers.location, filePath).then(resolve);
            }
            if (res.statusCode !== 200) { file.close(); try { fs.unlinkSync(filePath); } catch(e) {} return resolve(false); }
            res.pipe(file);
            file.on('finish', () => { file.close(); resolve(true); });
        }).on('error', () => { file.close(); try { fs.unlinkSync(filePath); } catch(e) {} resolve(false); });
    });
}

// Clean product name: remove [VOUCHER...] prefix and promo text
function cleanProductName(raw) {
    if (!raw) return '';
    return raw
        .replace(/^\[.*?\]\s*/g, '')   // [VOUCHER 42% ...]
        .replace(/^\(.*?\)\s*/g, '')   // (VOUCHER ...)
        .trim();
}

// === MAIN ===
async function main() {
    console.log('🛒 Lazada Mall Scraper v2 (API-based)');
    console.log(`   Shop: ${shopSlug}`);
    console.log(`   Output: ${outputDir}`);
    if (opts.limit) console.log(`   Limit: ${opts.limit} products`);
    console.log('');

    ensureDir(outputDir);
    ensureDir(path.join(outputDir, 'images/products'));
    ensureDir(path.join(outputDir, 'images/shop'));
    ensureDir(path.join(outputDir, 'screenshots'));

    // Launch browser
    console.log('🚀 Launching browser...');
    const browser = await puppeteer.launch({
        headless: 'new',
        args: ['--no-sandbox', '--disable-setuid-sandbox', '--window-size=1440,900'],
        defaultViewport: { width: 1440, height: 900 },
    });

    const page = await browser.newPage();
    await page.setUserAgent('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36');
    await page.setExtraHTTPHeaders({ 'Accept-Language': 'vi-VN,vi;q=0.9' });

    // ============================================================
    // STEP 1: Load shop page & capture API params
    // ============================================================
    console.log('📍 Loading shop page...');
    const cleanShopUrl = `https://www.lazada.vn/shop/${shopSlug}/`;

    // Intercept API responses to capture shopId, sellerId, campaignId, promotionTag
    let apiParams = null;
    page.on('response', async (response) => {
        const url = response.url();
        if (url.includes('campaignTppProducts') && url.includes('type=3') && !apiParams) {
            try {
                const u = new URL(url);
                apiParams = {
                    shopId: u.searchParams.get('shopId'),
                    sellerId: u.searchParams.get('sellerId'),
                    campaignId: u.searchParams.get('campaignId'),
                    promotionTag: u.searchParams.get('promotionTag'),
                };
            } catch(e) {}
        }
    });

    try {
        await page.goto(cleanShopUrl, { waitUntil: 'networkidle2', timeout: 30000 });
    } catch(e) {
        console.log('  timeout — continuing...');
    }
    await sleep(3000);

    // Scroll to trigger API calls
    for (let i = 0; i < 5; i++) {
        await page.evaluate(() => window.scrollBy(0, 600));
        await sleep(500);
    }
    await sleep(2000);

    await page.screenshot({ path: path.join(outputDir, 'screenshots/shop-page.png'), fullPage: true });
    console.log('  📸 Screenshot saved');

    // ============================================================
    // STEP 2: Shop info
    // ============================================================
    if (opts.shopInfo) {
        console.log('\n🏪 Scraping shop info...');
        const shopInfo = await page.evaluate(() => {
            const info = { name: '', logo: '', followers: '', rating: '', chatResponse: '', sellerId: '' };

            // Shop name from title
            info.name = document.title?.replace(/ Việt Nam.*$/, '').replace(/ Cửa Hàng.*$/, '').replace(/ \|.*$/, '').trim() || '';

            // Seller ID from page scripts
            const scripts = document.querySelectorAll('script');
            for (const s of scripts) {
                const t = s.textContent || '';
                const m = t.match(/sellerId:\s*(\d+)/);
                if (m) { info.sellerId = m[1]; break; }
            }

            // Shop logo
            const logoSelectors = [
                'img[src*="lazcdn"][class*="logo"]',
                '[class*="shop-header"] img',
                '[class*="seller"] img[src*="lazcdn"]',
                'img[alt*="logo"]',
            ];
            for (const sel of logoSelectors) {
                const img = document.querySelector(sel);
                if (img?.src) { info.logo = img.src; break; }
            }
            if (!info.logo) {
                const og = document.querySelector('meta[property="og:image"]');
                if (og?.content) info.logo = og.content;
            }

            // Shop stats
            const text = document.body?.innerText || '';
            const followMatch = text.match(/([\d,.]+)\s*(Followers|Người theo dõi)/i);
            if (followMatch) info.followers = followMatch[1];

            const ratingMatch = text.match(/([\d.]+)%?\s*(Positive|Tích cực)/i);
            if (ratingMatch) info.rating = ratingMatch[1];

            const responseMatch = text.match(/Chat Response\s*([\d%]+)/i);
            if (responseMatch) info.chatResponse = responseMatch[1];

            return info;
        });

        shopInfo.slug = shopSlug;
        shopInfo.url = cleanShopUrl;
        if (apiParams) {
            shopInfo.shopId = apiParams.shopId;
            if (!shopInfo.sellerId) shopInfo.sellerId = apiParams.sellerId;
        }

        // Download logo
        if (shopInfo.logo) {
            if (await downloadImage(shopInfo.logo, path.join(outputDir, 'images/shop/logo.jpg')))
                shopInfo.localLogo = 'images/shop/logo.jpg';
        }

        saveJson(path.join(outputDir, 'shop-info.json'), shopInfo);
        console.log(`  Name: ${shopInfo.name}`);
    }

    // ============================================================
    // STEP 3: Categories
    // ============================================================
    if (opts.categories) {
        console.log('\n📂 Scraping categories...');
        const categories = await page.evaluate(() => {
            const cats = [];
            const seen = new Set();
            document.querySelectorAll('a').forEach(a => {
                const href = a.href || '';
                const name = a.textContent?.trim();
                if (!name || name.length < 2 || name.length > 80) return;
                if (seen.has(name)) return;
                const isCat = href.includes('/category/') || href.includes('tab=category') ||
                    (href.includes('/shop/') && href.includes('tab='));
                if (!isCat) return;
                seen.add(name);
                cats.push({ name, url: href });
            });
            return cats;
        });

        saveJson(path.join(outputDir, 'categories.json'), categories);
        console.log(`  Found ${categories.length} categories`);
    }

    // ============================================================
    // STEP 4: Products via internal API (rich data, no CAPTCHA)
    // ============================================================
    if (opts.products) {
        console.log('\n📦 Scraping products via API...');

        if (!apiParams) {
            console.log('  ⚠️ No API params captured — falling back to DOM scraping...');
            // Fallback: extract from DOM + listing text for price
            const products = await extractProductsFromDOM(page);
            await finishProducts(products, page);
        } else {
            console.log(`  API params: shopId=${apiParams.shopId}, campaignId=${apiParams.campaignId}`);

            const allProducts = new Map();
            let offset = 0;
            let emptyCount = 0;

            while (emptyCount < 2) {
                if (opts.limit > 0 && allProducts.size >= opts.limit) break;

                const apiUrl = `https://www.lazada.vn/shop/site/api/shop/campaignTppProducts/query?shopId=${apiParams.shopId}&sellerId=${apiParams.sellerId}&campaignId=${apiParams.campaignId}&lang=en&limit=30&promotionTag=${apiParams.promotionTag}&offset=${offset}&sourceType=pc&type=3`;

                const result = await page.evaluate(async (url) => {
                    try {
                        const r = await fetch(url, { credentials: 'include' });
                        const json = await r.json();
                        return json?.result?.data || [];
                    } catch(e) {
                        return [];
                    }
                }, apiUrl);

                let newCount = 0;
                for (const p of result) {
                    const id = String(p.auctionId);
                    if (!allProducts.has(id)) {
                        allProducts.set(id, p);
                        newCount++;
                    }
                }

                console.log(`  Offset ${offset}: ${result.length} returned, ${newCount} new → total: ${allProducts.size}`);
                if (newCount === 0) emptyCount++;
                else emptyCount = 0;

                offset++;
                if (offset > 100) { console.log('  ⚠️ Offset limit reached'); break; }
                await sleep(300);
            }

            console.log(`\n  Total unique products from API: ${allProducts.size}`);

            // Transform API data to our format
            const products = [...allProducts.values()].map(p => ({
                id: String(p.auctionId),
                name: cleanProductName(p.title) || p.title || '',
                nameRaw: p.title || '',
                url: p.pdpUrl || p.mobileUrl || '',
                image: p.imageUrl ? (p.imageUrl.startsWith('//') ? 'https:' + p.imageUrl : p.imageUrl) : '',
                // Standard fields (cross-platform)
                price: p.price || 0,
                salePrice: (p.discountPrice && p.discountPrice < p.price) ? p.discountPrice : null,
                sold: p.volumePayOrdPrdQty1m || 0,
                source: 'lazada',
                // Lazada-specific (kept for backward compat)
                priceFormatted: p.discountPriceFormatted || p.priceFormatted || '',
                priceRaw: p.discountPrice || p.price || 0,
                originalPrice: p.price !== p.discountPrice ? p.priceFormatted : '',
                originalPriceRaw: p.price || 0,
                rating: p.rating || 0,
                reviews: p.reviews || 0,
                soldLastMonth: p.volumePayOrdPrdQty1m || 0,
                soldLastWeek: p.volumePayOrdPrdQty1w || 0,
                sku: p.sku || '',
                skuId: p.skuId ? String(p.skuId) : '',
                brandId: p.brandId ? String(p.brandId) : '',
                categoryId: p.categoryId ? String(p.categoryId) : '',
                categories: p.categories || [],
                inStock: p.inStock === 1,
                freeShipping: p.freeShipping || false,
            }));

            await finishProducts(products, page);
        }
    } else {
        await browser.close();
    }

    async function finishProducts(products, page) {
        // Apply limit
        let finalProducts = opts.limit > 0 ? products.slice(0, opts.limit) : products;

        // Close browser before downloading images
        await browser.close();
        console.log('  Browser closed\n');

        // ============================================================
        // Download images
        // ============================================================
        console.log('  📸 Downloading images...');
        const imgDir = path.join(outputDir, 'images/products');
        let imgCount = 0;

        for (let i = 0; i < finalProducts.length; i++) {
            const p = finalProducts[i];
            if (p.image) {
                if (await downloadImage(p.image, path.join(imgDir, `${p.id}.jpg`))) {
                    p.localImage = `images/products/${p.id}.jpg`;
                    imgCount++;
                }
            }
            if ((i + 1) % 50 === 0) console.log(`    Images: ${imgCount} (${i + 1}/${finalProducts.length})`);
        }
        console.log(`  ✅ ${imgCount} images downloaded`);

        // Save products JSON
        saveJson(path.join(outputDir, 'products.json'), finalProducts);

        // Save products markdown
        let md = `# ${shopSlug} — Products\n\n`;
        md += `**Shop:** [${shopSlug}](${cleanShopUrl})  \n`;
        md += `**Total:** ${finalProducts.length} products  \n`;
        md += `**Scraped:** ${new Date().toISOString()}  \n\n`;
        md += `| # | ID | Name | Price | Rating | Reviews | Sold/mo |\n`;
        md += `|---|----|------|-------|--------|---------|--------|\n`;
        for (let i = 0; i < finalProducts.length; i++) {
            const p = finalProducts[i];
            md += `| ${i + 1} | ${p.id} | ${(p.name || '').substring(0, 55)} | ${p.price || '-'} | ${p.rating ? p.rating.toFixed(1) : '-'} | ${p.reviews || '-'} | ${p.soldLastMonth || '-'} |\n`;
        }
        fs.writeFileSync(path.join(outputDir, 'products.md'), md);
        console.log(`  ✓ products.md`);
    }

    // Save summary
    saveJson(path.join(outputDir, 'scrape-summary.json'), {
        shop: shopSlug, url: cleanShopUrl,
        scrapedAt: new Date().toISOString(), outputDir,
        apiParams: apiParams || null,
    });

    console.log('\n✅ Scrape complete!');
    console.log(`   ${outputDir}`);
}

// Fallback: DOM-based product extraction (when API params not captured)
async function extractProductsFromDOM(page) {
    const allProducts = [];
    const seen = new Set();

    // Scroll to load products
    for (let i = 0; i < 15; i++) {
        await page.evaluate(() => window.scrollBy(0, 600));
        await sleep(500);
    }

    const domProducts = await page.evaluate(() => {
        const products = [];
        const seenIds = new Set();

        document.querySelectorAll('a').forEach(a => {
            const href = a.href || '';
            const idMatch = href.match(/-i(\d+)-/);
            if (!idMatch) return;
            const id = idMatch[1];
            if (seenIds.has(id)) return;
            seenIds.add(id);

            const raw = a.textContent?.trim() || '';
            // Extract name (before price pattern) and price
            let name = raw.replace(/₫[\s\d,.]+.*$/s, '').replace(/Buy Now.*$/s, '').replace(/\d{2,3},\d{3}.*$/s, '').trim();
            const priceMatch = raw.match(/₫\s*([\d,.]+)/);

            const img = a.querySelector('img');
            const image = img?.src || img?.getAttribute('data-src') || '';

            if (name.length > 5) {
                products.push({
                    id, name: name.substring(0, 300), url: href, image,
                    price: priceMatch ? '₫ ' + priceMatch[1] : '',
                });
            }
        });

        return products;
    });

    for (const p of domProducts) {
        if (!seen.has(p.id)) { seen.add(p.id); allProducts.push(p); }
    }

    console.log(`  DOM fallback: ${allProducts.length} products`);
    return allProducts;
}

main().catch(e => { console.error('❌', e.message); process.exit(1); });
