/**
 * Shopee Mall Shop Scraper
 *
 * Shopee yêu cầu login + verify khi dùng browser mới.
 * Script hỗ trợ 2 cách authenticate:
 *
 * 1. --login-wait: Mở visible browser, tự fill credentials, CHỜ user hoàn thành
 *    verify (CAPTCHA/OTP/email) thủ công, rồi tự động scrape.
 *    Lần đầu dùng cách này, sau đó save cookies để lần sau dùng lại.
 *
 * 2. --cookies <file>: Load cookies từ file JSON (export từ lần login trước).
 *    Không cần mở browser login lại.
 *
 * Usage:
 *   cd /private/tmp && node <path>/scrape-shopee-shop.js <shop_name> [options]
 *
 * Examples:
 *   # HTML mode: user copy HTML từ DevTools → parse (không cần browser/login)
 *   node scrape.js baseus.flagship.vn --html /tmp/shopee-baseus.html
 *
 *   # Lần đầu: login thủ công, save cookies
 *   node scrape.js "https://shopee.vn/baseus.flagship.vn" --login-wait --user luanpm3108 --pass 'xxx'
 *
 *   # Lần sau: dùng saved cookies
 *   node scrape.js "https://shopee.vn/baseus.flagship.vn" --cookies shopee-cookies.json
 *
 *   # Chỉ scrape products, limit 10
 *   node scrape.js "https://shopee.vn/baseus.flagship.vn" --products --limit 10 --cookies shopee-cookies.json
 */

const fs = require('fs');
const path = require('path');
const https = require('https');
const http = require('http');

// Load puppeteer: stealth for browser automation, plain core for HTML parse mode
let puppeteer;
function loadPuppeteer() {
    if (puppeteer) return;
    const candidates = ['puppeteer-extra', 'puppeteer', 'puppeteer-core'];
    for (const name of candidates) {
        try { puppeteer = require(name); break; } catch (e) {}
    }
    if (!puppeteer) throw new Error('No puppeteer package found. Run: npm install puppeteer-core');
    if (puppeteer.use) {
        try { puppeteer.use(require('puppeteer-extra-plugin-stealth')()); } catch (e) {}
    }
}

const BOTS_DIR = '/Users/luan/apps/vbrand/bots/scrape/shops';
const COOKIES_FILE = '/Users/luan/apps/vbrand/bots/scrape/shopee-cookies.json';

// === PARSE ARGS ===
const args = process.argv.slice(2);
let shopUrl = '';
let opts = {
    shopInfo: false, products: false, categories: false, all: true,
    output: '', limit: 0,
    user: '', pass: '',
    loginWait: false,   // visible browser, wait for user to complete verify
    cookiesFile: '',    // load cookies from file
    chromeProfile: '',  // use existing Chrome profile (reuse login session)
    htmlFile: '',       // parse from manually-copied HTML file (no browser needed)
};

for (let i = 0; i < args.length; i++) {
    switch (args[i]) {
        case '--shop-info': opts.shopInfo = true; opts.all = false; break;
        case '--products': opts.products = true; opts.all = false; break;
        case '--categories': opts.categories = true; opts.all = false; break;
        case '--all': opts.all = true; break;
        case '--output': opts.output = args[++i]; break;
        case '--limit': opts.limit = parseInt(args[++i]); break;
        case '--user': opts.user = args[++i]; break;
        case '--pass': opts.pass = args[++i]; break;
        case '--login-wait': opts.loginWait = true; break;
        case '--cookies': opts.cookiesFile = args[++i]; break;
        case '--chrome-profile': opts.chromeProfile = args[++i]; break;
        case '--chrome': opts.chromeProfile = 'default'; break; // use Default Chrome profile
        case '--html': opts.htmlFile = args[++i]; break;
        default: if (!args[i].startsWith('--')) shopUrl = args[i];
    }
}

if (opts.all) { opts.shopInfo = true; opts.products = true; opts.categories = true; }

if (!shopUrl) {
    console.error('Usage: node scrape-shopee-shop.js <shop_name_or_url> [--html file.html] [--login-wait --user U --pass P] [--cookies file.json] [--all|--products] [--limit N]');
    process.exit(1);
}

function extractShopName(url) {
    // URL format: https://shopee.vn/shopname or just "shopname" or "shopname --html ..."
    const m = url.match(/shopee\.vn\/([^?\/]+)/);
    if (m) return m[1];
    // If user passed just shop name (not full URL), use as-is
    if (!url.startsWith('http') && !url.startsWith('/')) return url;
    return 'unknown_shop';
}

const shopNameRaw = extractShopName(shopUrl);
const shopNameSafe = shopNameRaw.replace(/\./g, '_');
const outputDir = opts.output || path.join(BOTS_DIR, shopNameSafe);

function ensureDir(d) { if (!fs.existsSync(d)) fs.mkdirSync(d, { recursive: true }); }
function saveJson(fp, data) { fs.writeFileSync(fp, JSON.stringify(data, null, 2)); console.log(`  ✓ ${path.basename(fp)}`); }
function sleep(ms) { return new Promise(r => setTimeout(r, ms)); }
function shopeeImg(h) { return !h ? '' : h.startsWith('http') ? h : `https://down-vn.img.susercontent.com/file/${h}`; }

function downloadImage(url, filePath) {
    return new Promise((resolve) => {
        if (!url) return resolve(false);
        let u = url.startsWith('//') ? 'https:' + url : url;
        if (!u.startsWith('http')) u = 'https://' + u;
        const proto = u.startsWith('https') ? https : http;
        const file = fs.createWriteStream(filePath);
        proto.get(u, { headers: { 'User-Agent': 'Mozilla/5.0', 'Referer': 'https://shopee.vn/' } }, (res) => {
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

// === MAIN ===
async function main() {
    console.log('🛒 Shopee Scraper');
    console.log(`   Shop: ${shopNameRaw}`);
    console.log(`   Output: ${outputDir}`);

    // ---- HTML mode: short-circuit browser automation ----
    if (opts.htmlFile) {
        const htmlPath = path.isAbsolute(opts.htmlFile) ? opts.htmlFile : path.resolve(opts.htmlFile);
        if (!fs.existsSync(htmlPath)) {
            console.error(`❌ HTML file not found: ${htmlPath}`);
            process.exit(1);
        }
        await parseFromHtmlFile(htmlPath, outputDir);
        return;
    }

    console.log(`   Auth: ${opts.loginWait ? 'login-wait (manual verify)' : opts.cookiesFile ? 'cookies file' : 'none'}`);
    console.log('');

    ensureDir(outputDir);
    ensureDir(path.join(outputDir, 'images/products'));
    ensureDir(path.join(outputDir, 'images/shop'));
    ensureDir(path.join(outputDir, 'screenshots'));

    // ============================================================
    // LAUNCH BROWSER
    // ============================================================
    loadPuppeteer();
    const useHeadless = !opts.loginWait && !opts.chromeProfile;
    console.log(`🚀 Launching ${useHeadless ? 'headless' : 'visible'} browser...`);

    const launchArgs = [
        '--no-sandbox', '--disable-setuid-sandbox',
        '--disable-blink-features=AutomationControlled',
        '--window-size=1440,900', '--window-position=50,50',
    ];

    // Chrome profile support: reuse existing Chrome login session
    let chromeProfilePath = '';
    if (opts.chromeProfile) {
        const homeDir = process.env.HOME || '/Users/luan';
        if (opts.chromeProfile === 'default') {
            chromeProfilePath = `${homeDir}/Library/Application Support/Google/Chrome`;
        } else {
            chromeProfilePath = opts.chromeProfile;
        }
        console.log(`  📂 Using Chrome profile: ${chromeProfilePath}`);
        console.log(`  ⚠️ NOTE: Close Chrome first to avoid lock conflicts.`);
        launchArgs.push(`--user-data-dir=${chromeProfilePath}`);
    }

    const browser = await puppeteer.launch({
        headless: useHeadless ? 'new' : false,
        args: launchArgs,
        defaultViewport: { width: 1440, height: 900 },
    });

    const page = await browser.newPage();
    await page.setUserAgent('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36');
    await page.setExtraHTTPHeaders({ 'Accept-Language': 'vi-VN,vi;q=0.9,en-US;q=0.8,en;q=0.7' });

    // === SETUP API INTERCEPTION ===
    const captured = { shopDetail: null, categories: [], items: [] };

    page.on('response', async (response) => {
        const url = response.url();
        try {
            if (url.includes('/api/v4/shop/get_shop_detail') || url.includes('/api/v4/shop/get_shop_base')) {
                const json = await response.json();
                if (json.data) captured.shopDetail = json.data;
            }
            if (url.includes('get_categories')) {
                const json = await response.json();
                const cats = json.data || json.categories || [];
                if (Array.isArray(cats) && cats.length > 0) captured.categories = cats;
            }
            if (url.includes('search_items') || url.includes('rcmd_items') ||
                url.includes('recommend') || url.includes('shop_page_product') ||
                url.includes('collection_items')) {
                const json = await response.json();
                let items = json.items || json.data?.items ||
                    (json.data?.sections || []).flatMap(s => s.data?.item || []);
                if (items?.length > 0) {
                    captured.items.push(...items);
                    console.log(`  📡 +${items.length} products (total: ${captured.items.length})`);
                }
            }
        } catch (e) {}
    });

    // ============================================================
    // AUTHENTICATE
    // ============================================================
    if (opts.cookiesFile) {
        // Load cookies from file
        const cookiePath = path.isAbsolute(opts.cookiesFile) ? opts.cookiesFile : path.resolve(opts.cookiesFile);
        console.log(`🍪 Loading cookies from: ${cookiePath}`);
        try {
            const cookies = JSON.parse(fs.readFileSync(cookiePath, 'utf8'));
            await page.setCookie(...cookies);
            console.log(`  ✅ Loaded ${cookies.length} cookies`);
        } catch (e) {
            console.log(`  ❌ Failed to load cookies: ${e.message}`);
        }
    }

    if (opts.loginWait) {
        // Manual login flow: open visible browser, fill creds, wait for user
        console.log('🔐 Starting login flow...');
        console.log('   After login + verify, the script will continue automatically.\n');

        await page.goto('https://shopee.vn/', { waitUntil: 'networkidle2', timeout: 30000 }).catch(() => {});
        await sleep(3000);

        // Handle language selection
        const hasLangPopup = await page.evaluate(() => {
            const t = document.body?.innerText || '';
            return t.includes('Chọn ngôn ngữ') || (t.includes('Tiếng Việt') && t.includes('English') && t.length < 500);
        });
        if (hasLangPopup) {
            console.log('  🌐 Selecting Tiếng Việt...');
            await page.evaluate(() => {
                for (const el of document.querySelectorAll('button, a, div')) {
                    if (el.textContent?.trim() === 'Tiếng Việt') { el.click(); return; }
                }
            });
            await sleep(5000);
        }

        // Check if on login page
        const onLogin = page.url().includes('login');
        if (onLogin && opts.user && opts.pass) {
            console.log('  Filling credentials...');
            await sleep(2000);
            const userInput = await page.$('input[name="loginKey"], input[placeholder*="Email"], input[placeholder*="điện thoại"]');
            if (userInput) { await userInput.click({ clickCount: 3 }); await userInput.type(opts.user, { delay: 60 }); }
            await sleep(300);
            const passInput = await page.$('input[type="password"]');
            if (passInput) { await passInput.click({ clickCount: 3 }); await passInput.type(opts.pass, { delay: 60 }); }
            await sleep(300);

            // Click login
            await page.evaluate(() => {
                for (const b of document.querySelectorAll('button')) {
                    if (['ĐĂNG NHẬP', 'Đăng nhập', 'Log In'].includes(b.textContent?.trim())) { b.click(); return; }
                }
            });
            console.log('  ✅ Credentials submitted');
        }

        // WAIT for user to complete verify manually
        console.log('\n  ╔══════════════════════════════════════════════════════════╗');
        console.log('  ║  Complete verification in the browser window!           ║');
        console.log('  ║  (CAPTCHA / OTP / Email link — you have 5 minutes)     ║');
        console.log('  ║  Script auto-continues after verify completes.          ║');
        console.log('  ╚══════════════════════════════════════════════════════════╝\n');

        // Poll until we're on a non-login/non-verify page
        const maxWait = 300; // 5 minutes
        for (let i = 0; i < maxWait; i++) {
            await sleep(1000);
            const url = page.url();
            if (!url.includes('login') && !url.includes('verify') && !url.includes('captcha')) {
                console.log(`  ✅ Verification complete! Now at: ${url}`);
                break;
            }
            if (i > 0 && i % 15 === 0) {
                console.log(`  Still waiting... (${i}s) — URL: ${url}`);
            }
        }

        // Save cookies for next time
        const cookies = await page.cookies();
        const savePath = COOKIES_FILE;
        fs.writeFileSync(savePath, JSON.stringify(cookies, null, 2));
        console.log(`  💾 Cookies saved to: ${savePath} (use --cookies next time)\n`);

    } else if (!opts.cookiesFile) {
        // No auth — just visit homepage
        console.log('🍪 Visiting Shopee (no auth)...');
        await page.goto('https://shopee.vn/', { waitUntil: 'networkidle2', timeout: 30000 }).catch(() => {});
        await sleep(3000);

        // Language selection
        const hasLangPopup = await page.evaluate(() => {
            const t = document.body?.innerText || '';
            return t.includes('Chọn ngôn ngữ') || (t.includes('Tiếng Việt') && t.includes('English') && t.length < 500);
        });
        if (hasLangPopup) {
            await page.evaluate(() => {
                for (const el of document.querySelectorAll('button, a, div')) {
                    if (el.textContent?.trim() === 'Tiếng Việt') { el.click(); return; }
                }
            });
            await sleep(5000);
        }
    } else {
        // Cookies loaded — go to homepage to establish session
        console.log('🍪 Establishing session with saved cookies...');
        await page.goto('https://shopee.vn/', { waitUntil: 'networkidle2', timeout: 30000 }).catch(() => {});
        await sleep(3000);
    }

    // Close any popups
    await page.evaluate(() => {
        document.querySelectorAll('[class*="shopee-popup__close"], [class*="close-btn"]').forEach(el => el.click());
        document.dispatchEvent(new KeyboardEvent('keydown', { key: 'Escape', bubbles: true }));
    }).catch(() => {});
    await sleep(1000);

    // ============================================================
    // NAVIGATE TO SHOP
    // ============================================================
    console.log(`📍 Navigating to shop: ${shopUrl}`);
    await page.goto(shopUrl, { waitUntil: 'networkidle2', timeout: 30000 }).catch(e => console.log(`  ⚠️ ${e.message}`));
    await sleep(5000);

    await page.screenshot({ path: path.join(outputDir, 'screenshots/shop-page.png'), fullPage: false });

    const pageText = await page.evaluate(() => document.body?.innerText?.substring(0, 500) || '');
    const isBlocked = pageText.includes('không khả dụng') || pageText.includes('Đăng nhập lại') ||
                      (pageText.includes('Chọn ngôn ngữ') && pageText.length < 500);

    if (isBlocked) {
        console.log('  ❌ Shop page not accessible (need login or cookies expired)');
        console.log('  Run with --login-wait first to get valid cookies');
        console.log(`  Page: ${pageText.substring(0, 100).replace(/\n/g, ' ')}`);

        // Still save shop info from API if captured
        if (captured.shopDetail && opts.shopInfo) {
            saveShopInfo(captured.shopDetail, outputDir);
        }
        saveJson(path.join(outputDir, 'products.json'), []);
        await browser.close();
        return;
    }

    console.log('  ✅ Shop page loaded!');

    // Close shop popups
    await page.evaluate(() => {
        document.querySelectorAll('[class*="popup"] [class*="close"], [class*="modal"] [class*="close"]').forEach(el => el.click());
    }).catch(() => {});

    // ============================================================
    // SCROLL TO LOAD ALL PRODUCTS
    // ============================================================
    if (opts.products) {
        console.log('\n📦 Loading products...');

        // Click "Sản Phẩm" / Products tab if available
        await page.evaluate(() => {
            for (const el of document.querySelectorAll('a, div, span')) {
                const t = el.textContent?.trim();
                if (t === 'Sản Phẩm' || t === 'Products' || t === 'All Products') {
                    el.click(); return true;
                }
            }
            return false;
        }).catch(() => {});
        await sleep(3000);

        let lastCount = 0;
        let stale = 0;

        for (let i = 0; i < 80; i++) {
            await page.evaluate(() => window.scrollBy({ top: 600, behavior: 'smooth' }));
            await sleep(700);

            const count = captured.items.length;
            if (count === lastCount) {
                stale++;
                if (stale >= 5) {
                    // Try load more button
                    const clicked = await page.evaluate(() => {
                        for (const b of document.querySelectorAll('button')) {
                            const t = b.textContent?.trim() || '';
                            if (t.includes('Xem Thêm') || t.includes('See More')) { b.click(); return t; }
                        }
                        return null;
                    }).catch(() => null);

                    if (clicked) { stale = 0; await sleep(2000); }
                    else if (stale >= 10) { console.log('  Done scrolling'); break; }
                }
            } else { stale = 0; }
            lastCount = count;

            if (opts.limit > 0 && count >= opts.limit) break;
            if (i > 0 && i % 15 === 0) console.log(`  Scroll ${i}: ${count} products`);
        }

        // If still need more, try DOM scraping + in-page fetch
        if (captured.items.length === 0) {
            console.log('  Trying DOM scraping...');
            const domItems = await page.evaluate(() => {
                const items = []; const seen = new Set();
                document.querySelectorAll('a[href*="/product/"]').forEach(a => {
                    const m = a.href.match(/\/product\/(\d+)\/(\d+)/);
                    if (m && !seen.has(m[2])) {
                        seen.add(m[2]);
                        const card = a.closest('[class*="item"]') || a;
                        items.push({
                            itemid: parseInt(m[2]), shopid: parseInt(m[1]),
                            name: card.querySelector('[class*="name"]')?.textContent?.trim() || '',
                            image: card.querySelector('img')?.src || '',
                        });
                    }
                });
                return items;
            });
            if (domItems.length > 0) {
                captured.items.push(...domItems.map(d => ({ item_basic: d })));
                console.log(`  +${domItems.length} from DOM`);
            }
        }

        // Try in-page API fetch as last resort
        if (captured.items.length === 0 && captured.shopDetail?.shopid) {
            console.log('  Trying in-page API calls...');
            const sid = captured.shopDetail.shopid;
            for (let off = 0; off < 500; off += 30) {
                const result = await page.evaluate(async (shopId, offset) => {
                    const urls = [
                        `https://shopee.vn/api/v4/search/search_items?by=pop&limit=30&match_id=${shopId}&newest=${offset}&order=desc&page_type=shop&scenario=PAGE_OTHERS&version=2`,
                        `https://shopee.vn/api/v4/recommend/recommend?bundle=shop_page_product_tab_main&limit=30&offset=${offset}&section=shop_page_product_tab_main_sec&shopid=${shopId}&sort_type=1&tab_name=popular`,
                    ];
                    for (const url of urls) {
                        try {
                            const r = await fetch(url, { credentials: 'include' });
                            if (!r.ok) continue;
                            const j = await r.json();
                            const items = j.items || j.data?.items || (j.data?.sections || []).flatMap(s => s.data?.item || []);
                            if (items?.length > 0) return items;
                        } catch (e) {}
                    }
                    return [];
                }, sid, off);

                if (result.length > 0) {
                    captured.items.push(...result);
                    console.log(`  📡 API: +${result.length} (total: ${captured.items.length})`);
                    await sleep(800);
                } else {
                    if (off === 0) console.log('  API calls also failed');
                    break;
                }
            }
        }
    }

    // ============================================================
    // PROCESS & SAVE DATA
    // ============================================================
    const shopId = captured.shopDetail?.shopid;
    console.log(`\n📊 Results: shop=${captured.shopDetail ? '✅' : '❌'}, categories=${captured.categories.length}, products=${captured.items.length}`);

    // Shop info
    if (opts.shopInfo && captured.shopDetail) {
        saveShopInfo(captured.shopDetail, outputDir);
    }

    // Categories
    if (opts.categories) {
        const cats = captured.categories.map(c => ({
            id: c.catid || c.category_id,
            name: c.display_name || c.name || '',
            image: c.image ? shopeeImg(c.image) : '',
            itemCount: c.item_count || 0,
        }));
        saveJson(path.join(outputDir, 'categories.json'), cats);
    }

    // Products
    if (opts.products) {
        const seen = new Set();
        const products = [];

        for (const raw of captured.items) {
            const item = raw.item_basic || raw;
            const id = item.itemid || item.item_id;
            if (!id || seen.has(id)) continue;
            seen.add(id);
            if (opts.limit > 0 && products.length >= opts.limit) break;

            products.push({
                id,
                shopId: item.shopid || shopId,
                name: item.name || '',
                description: item.description || '',
                price: item.price ? item.price / 100000 : 0,
                priceMin: item.price_min ? item.price_min / 100000 : 0,
                priceMax: item.price_max ? item.price_max / 100000 : 0,
                originalPrice: item.price_before_discount ? item.price_before_discount / 100000 : 0,
                discount: item.raw_discount || item.discount || 0,
                currency: 'VND',
                stock: item.stock || 0,
                sold: item.sold || item.historical_sold || 0,
                rating: item.item_rating?.rating_star || 0,
                ratingCount: item.item_rating?.rating_count?.[0] || 0,
                liked: item.liked_count || 0,
                image: item.image ? (item.image.startsWith('http') ? item.image : shopeeImg(item.image)) : '',
                images: (item.images || []).map(i => shopeeImg(i)),
                categoryId: item.catid || '',
                brandName: item.brand || '',
                sku: item.sku || '',
                attributes: (item.attributes || []).map(a => ({ name: a.name, value: a.value })),
                models: (item.models || []).map(m => ({
                    name: m.name || '', price: m.price ? m.price / 100000 : 0,
                    stock: m.stock || 0, sku: m.sku || '',
                })),
                tierVariations: (item.tier_variations || []).map(tv => ({
                    name: tv.name, options: tv.options || [],
                    images: (tv.images || []).filter(Boolean).map(i => shopeeImg(i)),
                })),
                url: `https://shopee.vn/product/${item.shopid || shopId}/${id}`,
            });
        }

        console.log(`  Unique products: ${products.length}`);

        // Enrich with detail API
        if (products.length > 0 && shopId) {
            console.log('  Fetching details...');
            let enriched = 0;
            for (let i = 0; i < products.length; i++) {
                const p = products[i];
                try {
                    const detail = await page.evaluate(async (sid, iid) => {
                        try {
                            const r = await fetch(`https://shopee.vn/api/v4/item/get?itemid=${iid}&shopid=${sid}`, { credentials: 'include' });
                            if (!r.ok) return null;
                            return (await r.json()).data || null;
                        } catch (e) { return null; }
                    }, shopId, p.id);

                    if (detail) {
                        p.description = detail.description || p.description;
                        p.attributes = (detail.attributes || []).map(a => ({ name: a.name, value: a.value }));
                        p.models = (detail.models || []).map(m => ({
                            name: m.name || '', price: m.price ? m.price / 100000 : 0,
                            stock: m.stock || 0, sku: m.sku || '',
                        }));
                        p.tierVariations = (detail.tier_variations || []).map(tv => ({
                            name: tv.name, options: tv.options || [],
                            images: (tv.images || []).filter(Boolean).map(i => shopeeImg(i)),
                        }));
                        if (detail.images) p.images = detail.images.map(i => shopeeImg(i));
                        if (detail.weight) p.weight = detail.weight;
                        enriched++;
                    }
                } catch (e) {}
                if ((i + 1) % 20 === 0) console.log(`  Details: ${i + 1}/${products.length}`);
                await sleep(300);
            }
            console.log(`  ✅ Enriched ${enriched}/${products.length}`);
        }

        // Close browser
        await browser.close();
        console.log('  Browser closed\n');

        // Download images
        console.log('  📸 Downloading images...');
        let imgCount = 0;
        const imgDir = path.join(outputDir, 'images/products');

        for (let i = 0; i < products.length; i++) {
            const p = products[i];
            if (p.image) {
                if (await downloadImage(p.image, path.join(imgDir, `${p.id}.jpg`))) {
                    p.localImage = `images/products/${p.id}.jpg`;
                    imgCount++;
                }
            }
            if (p.images?.length > 0) {
                p.localImages = [];
                for (let j = 0; j < Math.min(p.images.length, 5); j++) {
                    if (await downloadImage(p.images[j], path.join(imgDir, `${p.id}_${j}.jpg`)))
                        p.localImages.push(`images/products/${p.id}_${j}.jpg`);
                }
            }
            if ((i + 1) % 30 === 0) console.log(`  Images: ${imgCount} (${i + 1}/${products.length})`);
        }
        console.log(`  ✅ ${imgCount} images`);

        saveJson(path.join(outputDir, 'products.json'), products);
        saveProductsMd(products, captured.shopDetail, shopNameRaw, outputDir);
    } else {
        await browser.close();
    }

    saveJson(path.join(outputDir, 'scrape-summary.json'), {
        shop: shopNameRaw, shopId, url: shopUrl, scrapedAt: new Date().toISOString(), outputDir,
        counts: { products: captured.items.length, categories: captured.categories.length },
    });

    console.log('\n✅ Done!');
    console.log(`   ${outputDir}`);
}

// ============================================================
// HTML PARSE MODE — parse manually-copied Shopee HTML
// ============================================================
async function parseFromHtmlFile(htmlFile, outputDir) {
    console.log(`\n📄 HTML Parse Mode`);
    console.log(`   File: ${htmlFile}`);
    console.log('');

    ensureDir(outputDir);
    ensureDir(path.join(outputDir, 'images/products'));
    ensureDir(path.join(outputDir, 'images/shop'));

    const html = fs.readFileSync(htmlFile, 'utf8');

    // Save full HTML for reference
    const savedHtml = path.join(outputDir, 'full.html');
    fs.copyFileSync(htmlFile, savedHtml);
    console.log(`  ✓ full.html saved`);

    // --- Strategy 1: Extract JSON from script tags ---
    let jsonProducts = [];
    const jsonPatterns = [
        /"item_basic"\s*:\s*\{[^}]*"itemid"\s*:\s*(\d+)/g,  // detect if item_basic present
    ];
    // Try to find large JSON blobs with itemid arrays
    const scriptMatches = html.match(/<script[^>]*>([\s\S]*?)<\/script>/g) || [];
    for (const scriptTag of scriptMatches) {
        const content = scriptTag.replace(/<script[^>]*>|<\/script>/g, '');
        // Look for itemid patterns in script content
        if (content.includes('"itemid"') && content.includes('"name"') && content.length > 500) {
            try {
                // Try to find items array
                const itemsMatch = content.match(/"items"\s*:\s*(\[[^\]]{100,}\])/);
                if (itemsMatch) {
                    const items = JSON.parse(itemsMatch[1]);
                    if (Array.isArray(items) && items.length > 0 && items[0].itemid) {
                        jsonProducts = items;
                        console.log(`  📡 Strategy 1: found ${items.length} products in script JSON`);
                        break;
                    }
                }
            } catch (e) {}
        }
    }

    // --- Strategy 2+3: DOM extraction via Puppeteer ---
    console.log(`  🔍 Launching Puppeteer to parse DOM...`);
    loadPuppeteer();
    const browser = await puppeteer.launch({
        headless: 'new',
        args: ['--no-sandbox', '--disable-setuid-sandbox', '--disable-gpu'],
    });
    const page = await browser.newPage();
    await page.setRequestInterception(true);
    page.on('request', req => req.abort()); // block all network — parse only from HTML

    await page.setContent(html, { waitUntil: 'domcontentloaded', timeout: 15000 }).catch(() => {});
    await sleep(1000);

    // Extract shop info from meta tags
    const shopMeta = await page.evaluate(() => {
        const og = (prop) => document.querySelector(`meta[property="og:${prop}"]`)?.content || '';
        const title = document.title || og('title') || '';
        const image = og('image') || '';
        const url   = og('url')   || document.querySelector('link[rel="canonical"]')?.href || '';
        return { title, image, url };
    });

    // Extract products from DOM
    const domProducts = await page.evaluate(() => {
        const results = []; const seen = new Set();

        function parseVndPrice(text) {
            if (!text) return { price: 0, priceMin: 0, priceMax: 0 };
            const clean = text.replace(/[₫đ\s\.]/g, '').replace(/,/g, '');
            const nums = clean.match(/\d+/g) || [];
            const vals = nums.map(n => parseInt(n)).filter(n => n > 1000);
            return { price: vals[0] || 0, priceMin: vals[0] || 0, priceMax: vals[1] || vals[0] || 0 };
        }

        function parseSold(text) {
            if (!text) return 0;
            const m = text.match(/(\d[\d\.,]*)/);
            return m ? parseInt(m[1].replace(/[,\.]/g, '')) : 0;
        }

        function getBestImg(card) {
            const imgs = card.querySelectorAll('img');
            for (const img of imgs) {
                const src = img.src || img.dataset?.src || img.getAttribute('data-src') || '';
                if (src && src.includes('susercontent') && !src.includes('100x100')) return src;
            }
            for (const img of imgs) {
                const src = img.src || img.dataset?.src || img.getAttribute('data-src') || '';
                if (src && src.startsWith('http')) return src;
            }
            return '';
        }

        // Find all product links: href contains "-i.SHOPID.ITEMID"
        const links = document.querySelectorAll('a[href*="-i."]');
        links.forEach(a => {
            const m = a.href.match(/-i\.(\d+)\.(\d+)/);
            if (!m) return;
            const shopId = m[1], itemId = m[2];
            if (seen.has(itemId)) return;
            seen.add(itemId);

            // Walk up to product card
            const card = a.closest('li, [class*="shopee-search-item"], [class*="col-"], [class*="item"]') || a;

            // Name: try img alt first, then text nodes
            const img = card.querySelector('img');
            let name = img?.alt?.trim() || '';
            if (!name) {
                const nameEl = card.querySelector('[class*="name"], [class*="title"], [class*="text"]');
                name = nameEl?.textContent?.trim() || a.textContent?.trim() || '';
            }
            // Clean up name (remove price/sold noise)
            name = name.split('\n')[0].trim().substring(0, 200);

            // Price: Shopee uses aria-label="promotion price" as an empty accessibility span
            // The actual price is in the next sibling div, e.g: <span>2.619.000</span><span>₫</span>
            let price = 0, priceMin = 0, priceMax = 0;
            const priceA11y = card.querySelector('[aria-label="promotion price"]');
            if (priceA11y) {
                // Walk up to parent wrapper, then find all spans with digit patterns
                const priceWrapper = priceA11y.parentElement || priceA11y;
                const priceSpans = Array.from(priceWrapper.querySelectorAll('span'));
                const vals = priceSpans
                    .map(s => s.textContent.trim())
                    .filter(t => /^\d[\d\.]+$/.test(t)) // "2.619.000" — digits + dots only
                    .map(t => parseInt(t.replace(/\./g, '')))
                    .filter(n => n > 1000);
                price = vals[0] || 0; priceMin = vals[0] || 0; priceMax = vals[1] || vals[0] || 0;
            } else {
                // Fallback: old selector
                const priceEl = card.querySelector('[class*="price"]');
                ({ price, priceMin, priceMax } = parseVndPrice(priceEl?.textContent || ''));
            }

            // Sold: "Đã bán 5k+" or "Đã bán 1.200" text in any div
            let sold = 0;
            const allDivs = Array.from(card.querySelectorAll('div, span'));
            const soldEl = allDivs.find(el => /Đã bán|đã bán/.test(el.textContent) && el.children.length === 0);
            if (soldEl) {
                const soldText = soldEl.textContent.trim();
                // Handle "5k+" → 5000, "1.2k" → 1200, "1.200" → 1200
                const m2 = soldText.match(/([\d\.]+)\s*k\+?/i);
                if (m2) sold = Math.round(parseFloat(m2[1].replace(',', '.')) * 1000);
                else sold = parseSold(soldText);
            } else {
                // Fallback: old selector
                const soldEl2 = card.querySelector('[class*="sold"], [class*="historical"]');
                sold = parseSold(soldEl2?.textContent || '');
            }

            // Rating: img[alt="rating-star"] nextElementSibling has the rating text e.g. "4.9"
            let rating = 0;
            const ratingImg = card.querySelector('img[alt="rating-star"]');
            if (ratingImg?.nextElementSibling) {
                rating = parseFloat(ratingImg.nextElementSibling.textContent) || 0;
            } else {
                // Fallback: old selector
                const ratingEl = card.querySelector('[class*="rating"] [class*="stars"], [class*="stars-filled"]');
                rating = parseFloat(ratingEl?.style?.width || '0') / 20 || 0;
            }

            const image = getBestImg(card);

            if (!name && !image) return; // skip empty cards (nav links, etc.)

            results.push({ itemId, shopId, name, price, priceMin, priceMax, sold, rating, image,
                url: `https://shopee.vn/product/${shopId}/${itemId}` });
        });

        // Fallback: /product/SHOPID/ITEMID format
        if (results.length === 0) {
            document.querySelectorAll('a[href*="/product/"]').forEach(a => {
                const m = a.href.match(/\/product\/(\d+)\/(\d+)/);
                if (!m) return;
                const shopId = m[1], itemId = m[2];
                if (seen.has(itemId)) return;
                seen.add(itemId);
                const img = a.querySelector('img');
                results.push({ itemId, shopId, name: img?.alt || a.textContent?.trim() || '',
                    price: 0, priceMin: 0, priceMax: 0, sold: 0, rating: 0,
                    image: img?.src || '', url: `https://shopee.vn/product/${shopId}/${itemId}` });
            });
        }

        return results;
    });

    await browser.close();

    // Merge: prefer JSON products if found, else DOM products
    let products = [];
    if (jsonProducts.length > 0) {
        products = jsonProducts.map(item => {
            const p = item.item_basic || item;
            return {
                id: String(p.itemid || p.item_id),
                shopId: String(p.shopid || ''),
                name: p.name || '',
                description: p.description || '',
                price: p.price ? p.price / 100000 : 0,
                priceMin: p.price_min ? p.price_min / 100000 : 0,
                priceMax: p.price_max ? p.price_max / 100000 : 0,
                currency: 'VND',
                stock: p.stock || 0,
                sold: p.sold || p.historical_sold || 0,
                rating: p.item_rating?.rating_star || 0,
                image: p.image ? shopeeImg(p.image) : '',
                images: (p.images || []).map(i => shopeeImg(i)),
                url: `https://shopee.vn/product/${p.shopid}/${p.itemid}`,
            };
        });
    } else {
        products = domProducts.map(d => ({
            id: d.itemId,
            shopId: d.shopId,
            name: d.name,
            description: '',
            price: d.price,
            priceMin: d.priceMin,
            priceMax: d.priceMax,
            currency: 'VND',
            stock: 0,
            sold: d.sold,
            rating: d.rating,
            image: d.image,
            images: d.image ? [d.image] : [],
            url: d.url,
        }));
    }

    const shopId = products[0]?.shopId || '';
    console.log(`\n📊 Results: ${products.length} products found`);
    if (products.length === 0) {
        console.log('  ⚠️  No products found. Tips:');
        console.log('     - Make sure to scroll to bottom before copying HTML');
        console.log('     - Copy the FULL page HTML (right-click on <html> tag in DevTools → Copy → Copy outerHTML)');
    }

    // Shop info from meta
    const shopInfo = {
        name: shopMeta.title.replace(/\s*[-|].*$/, '').trim() || shopNameRaw,
        username: shopNameRaw,
        avatar: shopMeta.image || '',
        url: shopMeta.url || `https://shopee.vn/${shopNameRaw}`,
        shopId,
        itemCount: products.length,
        parsedFrom: 'html',
    };
    if (shopInfo.avatar) {
        const ok = await downloadImage(shopInfo.avatar, path.join(outputDir, 'images/shop/avatar.jpg'));
        if (ok) shopInfo.localAvatar = 'images/shop/avatar.jpg';
    }
    saveJson(path.join(outputDir, 'shop-info.json'), shopInfo);
    saveJson(path.join(outputDir, 'categories.json'), []);

    // Download product images
    console.log(`\n  📸 Downloading ${products.length} product images...`);
    let imgCount = 0;
    for (let i = 0; i < products.length; i++) {
        const p = products[i];
        if (p.image) {
            const ok = await downloadImage(p.image, path.join(outputDir, 'images/products', `${p.id}.jpg`));
            if (ok) { p.localImage = `images/products/${p.id}.jpg`; imgCount++; }
        }
        if (p.images?.length > 1) {
            p.localImages = [];
            for (let j = 1; j < Math.min(p.images.length, 5); j++) {
                const ok = await downloadImage(p.images[j], path.join(outputDir, 'images/products', `${p.id}_${j}.jpg`));
                if (ok) p.localImages.push(`images/products/${p.id}_${j}.jpg`);
            }
        }
        if ((i + 1) % 20 === 0) console.log(`  Images: ${imgCount} / ${i + 1}`);
    }
    console.log(`  ✅ ${imgCount} images downloaded`);

    saveJson(path.join(outputDir, 'products.json'), products);
    saveProductsMd(products, shopInfo, shopNameRaw, outputDir);
    saveJson(path.join(outputDir, 'scrape-summary.json'), {
        shop: shopNameRaw, shopId, parsedFrom: 'html', htmlFile,
        scrapedAt: new Date().toISOString(), outputDir,
        counts: { products: products.length },
        selectors: {
            productLink: 'a[href*="-i."] → regex -i\\.(\\d+)\\.(\\d+)',
            note: 'DOM parse mode — name from img.alt or card text, price from [class*=price]',
        },
    });

    console.log(`\n✅ Done!`);
    console.log(`   ${outputDir}`);
    console.log(`   Products: ${products.length} | Images: ${imgCount}`);
}

function saveShopInfo(sd, dir) {
    const info = {
        id: sd.shopid, name: sd.name, username: shopNameRaw,
        description: sd.description || '',
        avatar: shopeeImg(sd.account?.portrait), cover: shopeeImg(sd.cover),
        followerCount: sd.follower_count || 0, itemCount: sd.item_count || 0,
        ratingStar: sd.rating_star || 0, responseRate: sd.response_rate || 0,
        isOfficialShop: sd.is_official_shop || false,
        url: `https://shopee.vn/${shopNameRaw}`,
    };
    downloadImage(info.avatar, path.join(dir, 'images/shop/avatar.jpg')).then(ok => { if (ok) info.localAvatar = 'images/shop/avatar.jpg'; });
    downloadImage(info.cover, path.join(dir, 'images/shop/cover.jpg')).then(ok => { if (ok) info.localCover = 'images/shop/cover.jpg'; });
    saveJson(path.join(dir, 'shop-info.json'), info);
}

function saveProductsMd(products, shopDetail, shopName, dir) {
    let md = `# ${shopDetail?.name || shopName} — Products\n\n`;
    md += `**Shop:** [${shopName}](https://shopee.vn/${shopName})  \n`;
    md += `**Total:** ${products.length} products  \n`;
    md += `**Scraped:** ${new Date().toISOString()}  \n\n`;
    md += `| # | Name | Price (₫) | Sold | Rating | Vars |\n`;
    md += `|---|------|-----------|------|--------|------|\n`;
    for (let i = 0; i < products.length; i++) {
        const p = products[i];
        const pr = p.priceMin && p.priceMax && p.priceMin !== p.priceMax
            ? `${p.priceMin.toLocaleString()} - ${p.priceMax.toLocaleString()}`
            : `${(p.price || 0).toLocaleString()}`;
        md += `| ${i + 1} | ${(p.name || '').substring(0, 55)} | ${pr} | ${p.sold || '-'} | ${p.rating ? p.rating.toFixed(1) : '-'} | ${p.models?.length || 0} |\n`;
    }
    fs.writeFileSync(path.join(dir, 'products.md'), md);
    console.log(`  ✓ products.md`);
}

main().catch(err => { console.error('❌', err.message); process.exit(1); });
