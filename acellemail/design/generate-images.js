/**
 * AcelleMail — DALL-E Image Generator
 *
 * Generates replacement images for the AcelleMail landing site using OpenAI DALL-E 3 API.
 * Each image gets a tailored prompt based on what it should show.
 *
 * Usage:
 *   OPENAI_API_KEY=sk-xxx node design/generate-images.js
 *   OPENAI_API_KEY=sk-xxx node design/generate-images.js --only hero
 *   OPENAI_API_KEY=sk-xxx node design/generate-images.js --only features
 *   OPENAI_API_KEY=sk-xxx node design/generate-images.js --dry-run
 *
 * Requirements:
 *   npm install openai (in the design/ folder)
 */

const fs = require('fs');
const path = require('path');
const https = require('https');
const http = require('http');

const IMAGES_DIR = path.join(__dirname, '..', 'images');

// Parse CLI args
const args = process.argv.slice(2);
const DRY_RUN = args.includes('--dry-run');
const ONLY = args.includes('--only') ? args[args.indexOf('--only') + 1] : null;

const API_KEY = process.env.OPENAI_API_KEY;
if (!API_KEY && !DRY_RUN) {
    console.error('ERROR: Set OPENAI_API_KEY environment variable');
    console.error('Usage: OPENAI_API_KEY=sk-xxx node design/generate-images.js');
    process.exit(1);
}

// ============================================================================
// IMAGE DEFINITIONS — each image gets a category, filename, and DALL-E prompt
// ============================================================================

const IMAGES = [
    // --- HERO IMAGES ---
    {
        category: 'hero',
        file: 'hero/home-hero.png',
        size: '1792x1024',
        prompt: 'Modern email marketing platform dashboard UI screenshot, clean blue and white design, showing email campaign analytics with charts and graphs, drag and drop email builder preview, subscriber growth chart, professional SaaS interface, isometric 3D illustration style with soft shadows, blue color scheme (#2563EB accent), white background, no text, no logos'
    },
    {
        category: 'hero',
        file: 'hero/home-hero-alt.png',
        size: '1792x1024',
        prompt: 'Abstract illustration of email marketing automation, floating envelope icons connected by flowing blue lines and nodes, representing automated email sequences, modern flat design with subtle gradients, blue and white color palette, professional tech illustration, no text'
    },
    {
        category: 'hero',
        file: 'hero/automation-hero.png',
        size: '1792x1024',
        prompt: 'Marketing automation workflow visualization, connected nodes and paths showing email triggers and conditions, modern UI design with blue accent color, flowchart-style customer journey with decision diamonds and email send actions, clean professional illustration, white background with blue (#2563EB) highlights, no text'
    },
    {
        category: 'hero',
        file: 'hero/help-hero.png',
        size: '1792x1024',
        prompt: 'Friendly illustration of customer support and documentation, showing a laptop with help articles, a chat bubble, and a person reading documentation, modern flat illustration style, blue and white color scheme, warm and welcoming, professional, no text no logos'
    },
    {
        category: 'hero',
        file: 'hero/about-hero.jpg',
        size: '1792x1024',
        prompt: 'Modern tech startup team working in a bright open office space, diverse group of developers and designers collaborating, laptops and large monitors showing code and dashboards, natural lighting, professional photography style, warm and inviting atmosphere, no logos or brand names visible'
    },

    // --- FEATURE SCREENSHOTS ---
    {
        category: 'features',
        file: 'features/email-templates.png',
        size: '1024x1024',
        prompt: 'Email template gallery UI screenshot, grid of beautiful email template previews showing different layouts (newsletter, promotional, transactional), modern clean interface with blue accent buttons, professional email marketing tool, no text labels just visual templates with placeholder content'
    },
    {
        category: 'features',
        file: 'features/automation-flows.png',
        size: '1024x1024',
        prompt: 'Email automation workflow builder UI, visual flowchart with connected blocks showing trigger, delay, condition, and send email steps, drag and drop interface, modern blue and white design, professional marketing automation tool screenshot, clean and minimal'
    },
    {
        category: 'features',
        file: 'features/campaign-manager.png',
        size: '1024x1024',
        prompt: 'Email campaign management dashboard, table view showing campaigns with status badges (sent, draft, scheduled), open rate and click rate columns with progress bars, modern clean UI with blue header, professional email marketing analytics, white background'
    },
    {
        category: 'features',
        file: 'features/email-sms.png',
        size: '1024x1024',
        prompt: 'Drag and drop email builder interface, showing a split view with visual email editor on left and preview on right, colorful email template being designed with image blocks and text sections, modern UI with blue toolbar, professional and clean'
    },
    {
        category: 'features',
        file: 'features/segmentation.png',
        size: '1024x1024',
        prompt: 'Audience segmentation interface, showing filter conditions being built with dropdowns (opened email, clicked link, subscribed after date), visual Venn diagram showing overlapping segments, modern UI with blue accent, professional marketing tool'
    },
    {
        category: 'features',
        file: 'features/predictive.png',
        size: '1024x1024',
        prompt: 'Email analytics dashboard showing AI insights, prediction charts for best send times, engagement heatmap by hour and day, subscriber activity graph trending upward, modern clean data visualization with blue color scheme, professional marketing analytics'
    },
    {
        category: 'features',
        file: 'features/customer-journey.png',
        size: '1024x1024',
        prompt: 'Customer journey map visualization, horizontal timeline showing touchpoints from signup to purchase, connected nodes with email, wait, and condition steps, modern flat design with blue accent color, professional marketing automation illustration'
    },
    {
        category: 'features',
        file: 'features/content-studio.png',
        size: '1024x1024',
        prompt: 'Content management interface for email marketing, showing media library with uploaded images, file manager with drag and drop zone, template asset organization, modern clean UI with blue accents, professional'
    },
    {
        category: 'features',
        file: 'features/landing-pages.png',
        size: '1024x1024',
        prompt: 'Landing page builder UI, drag and drop editor showing a subscription form being designed with hero section, email input field, and submit button, live preview on the right, modern blue and white interface, professional web builder tool'
    },
    {
        category: 'features',
        file: 'features/websites.png',
        size: '1024x1024',
        prompt: 'Server management dashboard showing email sending statistics, delivery rate gauge at 99.2%, bounce rate chart, SMTP connection status panel showing green checkmarks for Amazon SES SendGrid SparkPost, modern clean monitoring UI with blue theme'
    },
    {
        category: 'features',
        file: 'features/analytics.png',
        size: '1024x1024',
        prompt: 'Email campaign analytics report, showing open rate pie chart, click map on email template, geographic distribution world map with colored dots, time-series line chart of engagement, modern data dashboard with blue color scheme, professional'
    },
    {
        category: 'features',
        file: 'features/ab-testing.png',
        size: '1024x1024',
        prompt: 'A/B testing comparison interface for email campaigns, showing two email variants side by side (Version A and Version B) with performance metrics below each (open rate, click rate, conversion), winner badge on the better variant, modern clean UI with blue accents'
    },
    {
        category: 'features',
        file: 'features/social-posting.png',
        size: '1024x1024',
        prompt: 'Multi-channel marketing dashboard, showing email, SMS, and web push notification channels in a unified interface, channel performance comparison chart, modern clean UI with blue design, professional marketing platform'
    },
    {
        category: 'features',
        file: 'features/surveys.png',
        size: '1024x1024',
        prompt: 'Subscription form builder UI, showing a customizable signup form being designed with fields for email name and preferences, color picker panel, embed code snippet below, modern clean interface with blue accents, professional'
    },
    {
        category: 'features',
        file: 'features/onboarding.png',
        size: '1024x1024',
        prompt: 'Server installation wizard UI, step-by-step setup showing database configuration, SMTP settings with Amazon SES selected, admin account creation, progress bar at top showing step 3 of 5, modern clean interface with blue theme, professional'
    },
    {
        category: 'features',
        file: 'features/experts.png',
        size: '1024x1024',
        prompt: 'Professional support chat interface, showing a conversation between customer and support agent about email configuration, code snippet being shared, helpful and friendly tone, modern clean messaging UI with blue accent, professional'
    },
    {
        category: 'features',
        file: 'features/customer-success.png',
        size: '1024x1024',
        prompt: 'Success metrics dashboard showing business growth, revenue chart trending upward, email ROI calculator showing cost savings vs SaaS alternatives, subscriber count reaching 100K milestone, celebratory UI with blue theme, professional'
    },
    {
        category: 'features',
        file: 'features/switch-brands.png',
        size: '1024x1024',
        prompt: 'Migration wizard interface, showing data import from CSV and other email platforms, mapping fields between source and destination, progress indicator, modern clean UI with blue accents, professional email marketing migration tool'
    },
    {
        category: 'features',
        file: 'features/integrations-auto.png',
        size: '1024x1024',
        prompt: 'API integration dashboard showing connected services with status indicators, webhook configuration panel, REST API documentation preview, code snippets for different programming languages, modern clean developer-focused UI with blue theme'
    },
    {
        category: 'features',
        file: 'features/pricing-hero.png',
        size: '1024x1024',
        prompt: 'Pricing comparison illustration, showing a simple pricing card with a blue checkmark and one-time payment badge, versus multiple recurring billing invoices stacked up, modern flat illustration style emphasizing simplicity and value, blue and white color scheme, no text'
    },
    {
        category: 'features',
        file: 'features/case-study.png',
        size: '1024x1024',
        prompt: 'Business dashboard showing email marketing results, large numbers showing 50K subscribers, 98.5% delivery rate, $0.10 per 1000 emails cost, ROI chart, modern clean data visualization with blue color scheme, professional success story presentation'
    },
    {
        category: 'features',
        file: 'features/whats-new.png',
        size: '1024x1024',
        prompt: 'Software changelog and updates page, showing version badges (v4.1.5 LTS), new feature highlights with icons, release timeline, modern clean documentation style UI with blue accents, professional software update presentation'
    },

    // --- ABOUT PAGE ---
    {
        category: 'about',
        file: 'about/newsroom.jpg',
        size: '1024x1024',
        prompt: 'Modern tech company blog/newsroom page on a laptop screen, showing article cards with tech and email marketing topics, clean minimal design, warm lighting, professional office desk setup, lifestyle photography style'
    },
    {
        category: 'about',
        file: 'about/why-mailchimp.jpg',
        size: '1024x1024',
        prompt: 'Developer working on a Laravel PHP project on a large monitor, code editor showing clean PHP code, second monitor showing email template preview, cozy home office with plants, professional lifestyle photography, warm natural lighting'
    },
    {
        category: 'about',
        file: 'about/whats-new.png',
        size: '1024x1024',
        prompt: 'Software version update celebration illustration, showing a rocket launching from a laptop screen with confetti, version number badge, feature icons floating around, modern flat illustration style with blue and white colors, playful yet professional'
    },
    {
        category: 'about',
        file: 'about/office.png',
        size: '1024x1024',
        prompt: 'Modern remote-first tech team on a video call, multiple faces on screen showing diversity, collaborative atmosphere, code and dashboards visible in background, professional and friendly, warm lighting, illustration or realistic style'
    },

    // --- HELP PAGE ---
    {
        category: 'help',
        file: 'help/contact-support.png',
        size: '1024x1024',
        prompt: 'Customer support illustration, friendly support agent with headset at a desk with multiple monitors, help tickets and chat bubbles floating around, modern flat illustration style with blue accents, warm and approachable, professional'
    },
    {
        category: 'help',
        file: 'help/expert-help.png',
        size: '1024x1024',
        prompt: 'Technical consultation illustration, showing a developer and a business person reviewing a server setup together, terminal window and email dashboard on screens, modern flat illustration with blue color scheme, professional and collaborative'
    },
];

// ============================================================================
// DALL-E API CALLER
// ============================================================================

async function generateImage(imageConfig) {
    const { file, prompt, size } = imageConfig;
    const outPath = path.join(IMAGES_DIR, file);

    // Ensure directory exists
    fs.mkdirSync(path.dirname(outPath), { recursive: true });

    if (DRY_RUN) {
        console.log(`[DRY RUN] Would generate: ${file}`);
        console.log(`  Size: ${size}`);
        console.log(`  Prompt: ${prompt.substring(0, 100)}...`);
        console.log('');
        return;
    }

    console.log(`Generating: ${file}...`);

    try {
        const response = await fetch('https://api.openai.com/v1/images/generations', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${API_KEY}`,
            },
            body: JSON.stringify({
                model: 'dall-e-3',
                prompt: prompt,
                n: 1,
                size: size,
                quality: 'standard',
                response_format: 'url',
            }),
        });

        if (!response.ok) {
            const error = await response.json();
            throw new Error(`API error ${response.status}: ${JSON.stringify(error)}`);
        }

        const data = await response.json();
        const imageUrl = data.data[0].url;

        // Download image
        await downloadFile(imageUrl, outPath);
        console.log(`  ✓ Saved: ${file}`);
    } catch (err) {
        console.error(`  ✗ Failed: ${file} — ${err.message}`);
    }
}

function downloadFile(url, destPath) {
    return new Promise((resolve, reject) => {
        const protocol = url.startsWith('https') ? https : http;
        protocol.get(url, (response) => {
            if (response.statusCode === 301 || response.statusCode === 302) {
                downloadFile(response.headers.location, destPath).then(resolve).catch(reject);
                return;
            }
            const fileStream = fs.createWriteStream(destPath);
            response.pipe(fileStream);
            fileStream.on('finish', () => { fileStream.close(); resolve(); });
            fileStream.on('error', reject);
        }).on('error', reject);
    });
}

// ============================================================================
// MAIN
// ============================================================================

async function main() {
    let images = IMAGES;

    if (ONLY) {
        images = IMAGES.filter(img => img.category === ONLY);
        if (images.length === 0) {
            console.error(`No images found for category: ${ONLY}`);
            console.error('Available categories: hero, features, about, help');
            process.exit(1);
        }
    }

    console.log(`\n=== AcelleMail Image Generator ===`);
    console.log(`Images to generate: ${images.length}`);
    console.log(`Mode: ${DRY_RUN ? 'DRY RUN' : 'LIVE'}`);
    if (ONLY) console.log(`Category filter: ${ONLY}`);
    console.log('');

    // Generate sequentially to avoid rate limits
    for (const img of images) {
        await generateImage(img);
        // Small delay between requests to avoid rate limits
        if (!DRY_RUN) await new Promise(r => setTimeout(r, 2000));
    }

    console.log('\nDone!');
    console.log(`Total: ${images.length} images`);
}

main().catch(console.error);
