#!/usr/bin/env node
/**
 * Mailchimp Clone — Audit Screenshot Script
 * Takes full-page screenshots of all pages at desktop + mobile viewports
 * Usage: node design/screenshot-all.js [audit_number]
 */

const puppeteer = require('puppeteer');
const path = require('path');
const fs = require('fs');

const BASE_URL = 'http://localhost:8888';
const PAGES = [
    { name: 'home', path: '/' },
    { name: 'features', path: '/features.php' },
    { name: 'pricing', path: '/pricing.php' },
    { name: 'automation', path: '/automation.php' },
    { name: 'email-marketing', path: '/email-marketing.php' },
    { name: 'integrations', path: '/integrations.php' },
    { name: 'security', path: '/security.php' },
    { name: 'about', path: '/about.php' },
    { name: 'help', path: '/help.php' },
    { name: 'contact', path: '/contact.php' },
];

const VIEWPORTS = [
    { name: 'desktop', width: 1440, height: 900 },
    { name: 'mobile', width: 375, height: 812 },
];

(async () => {
    // Determine audit number
    const versionsDir = path.join(__dirname, 'versions');
    let auditNum = process.argv[2];
    if (!auditNum) {
        const existing = fs.readdirSync(versionsDir).filter(d => d.startsWith('audit_'));
        auditNum = existing.length + 1;
    }

    const outDir = path.join(versionsDir, `audit_${auditNum}`);
    fs.mkdirSync(outDir, { recursive: true });

    console.log(`\n📸 Audit ${auditNum} — Screenshots → ${outDir}\n`);

    const browser = await puppeteer.launch({ headless: 'new' });

    let count = 0;
    for (const vp of VIEWPORTS) {
        for (const pg of PAGES) {
            const page = await browser.newPage();
            await page.setViewport({ width: vp.width, height: vp.height });

            const url = `${BASE_URL}${pg.path}`;
            console.log(`  ${vp.name} ${pg.name} → ${url}`);

            try {
                await page.goto(url, { waitUntil: 'networkidle0', timeout: 15000 });
                await new Promise(r => setTimeout(r, 500));

                const filename = `${pg.name}_${vp.name}.png`;
                await page.screenshot({
                    path: path.join(outDir, filename),
                    fullPage: true,
                });
                count++;
            } catch (err) {
                console.error(`  ❌ ${pg.name}_${vp.name}: ${err.message}`);
            }

            await page.close();
        }
    }

    await browser.close();
    console.log(`\n✅ Done! ${count} screenshots saved to audit_${auditNum}/\n`);
})();
