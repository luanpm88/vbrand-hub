<?php
namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleBatch3Seeder extends Seeder
{
    public function run(): void
    {
        $articles = $this->getArticles();
        foreach ($articles as $data) {
            $category = Category::where('slug', $data['category'])->first();
            if (!$category) continue;
            $article = Article::create([
                'title' => $data['title'],
                'slug' => Str::slug($data['title']),
                'excerpt' => $data['excerpt'],
                'body_markdown' => $data['body'],
                'body_html' => app(\App\Services\MarkdownService::class)->toHtml($data['body']),
                'category_id' => $category->id,
                'status' => 'published',
                'content_type' => $data['type'] ?? 'tutorial',
                'difficulty' => $data['difficulty'] ?? 'intermediate',
                'reading_time' => max(1, (int) ceil(str_word_count($data['body']) / 200)),
                'published_at' => now()->subDays(rand(1, 180)),
                'views_count' => rand(100, 8000),
            ]);
            $tagIds = Tag::whereIn('slug', $data['tags'] ?? [])->pluck('id')->toArray();
            if ($tagIds) $article->tags()->attach($tagIds);
        }
    }

    private function getArticles(): array
    {
        return [

            // =========================================================
            // INTEGRATIONS
            // =========================================================

            [
                'category' => 'integrations',
                'title' => 'Connecting Stripe for Subscription Billing',
                'type' => 'tutorial',
                'difficulty' => 'intermediate',
                'excerpt' => 'Set up Stripe payments in AcelleMail SaaS mode — API keys, webhook endpoints, and plan synchronization.',
                'tags' => ['stripe', 'subscription-billing'],
                'body' => <<<'MD'
## Adding Your Stripe API Keys

In AcelleMail admin, go to **Settings → Payment Gateways → Stripe**. Enter your Publishable Key and Secret Key from the [Stripe Dashboard](https://dashboard.stripe.com/apikeys). Enable test mode first to verify the integration.

| Key | Where to find it |
|---|---|
| Publishable Key | Stripe Dashboard → API Keys |
| Secret Key | Stripe Dashboard → API Keys (reveal) |
| Webhook Secret | Stripe Dashboard → Webhooks → Signing secret |

## Creating a Webhook Endpoint

In Stripe Dashboard, go to **Developers → Webhooks → Add endpoint**. Set the URL to:

```
https://yourdomain.com/stripe/webhook
```

Select these events:

- `invoice.payment_succeeded`
- `invoice.payment_failed`
- `customer.subscription.deleted`
- `checkout.session.completed`

## Syncing Plans

After saving Stripe keys in AcelleMail, go to **Plans** and create a plan. Set the **Stripe Price ID** field to match the Price ID from your Stripe product catalog (starts with `price_`).

When a customer subscribes, AcelleMail creates a Stripe customer automatically, attaches the plan, and handles renewal billing. Failed payments trigger a grace period before account suspension.

## Testing the Flow

Use Stripe's test card `4242 4242 4242 4242` with any future expiry. Check **Stripe Dashboard → Events** to confirm webhook delivery and **AcelleMail → Subscriptions** to verify the new record.
MD
            ],

            [
                'category' => 'integrations',
                'title' => 'WordPress Subscriber Sync',
                'type' => 'tutorial',
                'difficulty' => 'intermediate',
                'excerpt' => 'Automatically add WordPress users to AcelleMail lists using action hooks and the REST API.',
                'tags' => ['wordpress', 'api'],
                'body' => <<<'MD'
## The Integration Approach

AcelleMail exposes a REST API that you can call from WordPress hooks. The simplest pattern: when a user registers on WordPress, push them to an AcelleMail list via API.

## PHP Hook Example

Add this to your theme's `functions.php` or a custom plugin:

```php
add_action('user_register', function($user_id) {
    $user = get_userdata($user_id);

    $response = wp_remote_post('https://mail.yourdomain.com/api/v1/subscribers', [
        'headers' => [
            'Authorization' => 'Bearer YOUR_API_TOKEN',
            'Content-Type'  => 'application/json',
        ],
        'body' => json_encode([
            'email'       => $user->user_email,
            'first_name'  => $user->first_name,
            'list_uid'    => 'YOUR_LIST_UID',
        ]),
    ]);
});
```

Replace `YOUR_API_TOKEN` with a token from **AcelleMail → API Tokens** and `YOUR_LIST_UID` with the list UID from **Lists → Overview**.

## Handling Unsubscribes

To keep both platforms in sync, listen for AcelleMail's `unsubscribe` webhook and update WordPress user meta:

```php
// In a webhook handler endpoint
$payload = json_decode(file_get_contents('php://input'), true);
if ($payload['event'] === 'unsubscribe') {
    $user = get_user_by('email', $payload['subscriber']['email']);
    if ($user) {
        update_user_meta($user->ID, 'newsletter_subscribed', '0');
    }
}
```

## WooCommerce Checkout Opt-in

For WooCommerce, add a checkbox at checkout and subscribe on order completion:

```php
add_action('woocommerce_checkout_after_terms_and_conditions', function() {
    echo '<p><label><input type="checkbox" name="subscribe_newsletter" value="1"> Subscribe to newsletter</label></p>';
});
```
MD
            ],

            [
                'category' => 'integrations',
                'title' => 'WooCommerce Post-Purchase Emails',
                'type' => 'tutorial',
                'difficulty' => 'intermediate',
                'excerpt' => 'Trigger AcelleMail automations after a WooCommerce order — confirmation, review requests, and upsell sequences.',
                'tags' => ['woocommerce', 'subscription-billing'],
                'body' => <<<'MD'
## Why Post-Purchase Automation Matters

The moments after a purchase are the highest-engagement window you have. A well-timed review request or cross-sell email can significantly increase LTV without additional ad spend.

## Triggering on Order Completion

Use the `woocommerce_order_status_completed` hook to fire an API call to AcelleMail:

```php
add_action('woocommerce_order_status_completed', function($order_id) {
    $order = wc_get_order($order_id);
    $email = $order->get_billing_email();
    $first_name = $order->get_billing_first_name();

    // Add to post-purchase automation list
    wp_remote_post('https://mail.yourdomain.com/api/v1/subscribers', [
        'headers' => ['Authorization' => 'Bearer TOKEN', 'Content-Type' => 'application/json'],
        'body' => json_encode([
            'email'      => $email,
            'first_name' => $first_name,
            'list_uid'   => 'POST_PURCHASE_LIST_UID',
        ]),
    ]);
});
```

## Post-Purchase Sequence

Set up an AcelleMail automation with this timing:

| Email | Delay | Goal |
|---|---|---|
| Order thank you | Immediately | Confirm + set expectations |
| Usage tips | Day 3 | Reduce returns, build loyalty |
| Review request | Day 7 | Social proof |
| Related products | Day 14 | Upsell / cross-sell |

## Product-Specific Tags

Pass order product categories as subscriber tags to personalise follow-ups:

```php
$categories = [];
foreach ($order->get_items() as $item) {
    $terms = get_the_terms($item->get_product_id(), 'product_cat');
    foreach ($terms as $term) $categories[] = $term->slug;
}
// Include 'tags' => $categories in the API payload
```
MD
            ],

            [
                'category' => 'integrations',
                'title' => 'Zapier Integration Guide',
                'type' => 'guide',
                'difficulty' => 'beginner',
                'excerpt' => 'Connect AcelleMail to 5,000+ apps via Zapier — set up triggers, actions, and common automation use cases.',
                'tags' => ['zapier', 'api'],
                'body' => <<<'MD'
## Connecting AcelleMail to Zapier

AcelleMail integrates with Zapier through its REST API using the **Webhooks by Zapier** action and custom app connections. You need an API token from **AcelleMail → API Tokens**.

## Available Triggers (AcelleMail → Zapier)

Use AcelleMail's **Webhooks** feature to push events to Zapier:

| Event | Zapier Trigger URL Use Case |
|---|---|
| New subscriber | Notify Slack channel |
| Unsubscribe | Update CRM contact |
| Campaign sent | Log to Google Sheet |
| Link clicked | Tag contact in HubSpot |

In AcelleMail, go to **Webhooks → Create** and paste the Zapier Webhook URL (from "Catch Hook" trigger).

## Available Actions (Zapier → AcelleMail)

Use **Webhooks by Zapier → POST** to call the AcelleMail API:

```
POST https://mail.yourdomain.com/api/v1/subscribers
Authorization: Bearer YOUR_TOKEN
Content-Type: application/json

{ "email": "{{email}}", "first_name": "{{first_name}}", "list_uid": "abc123" }
```

## Common Use Cases

**Typeform → AcelleMail:** When someone completes a Typeform survey, add them to a specific list with their answers as custom fields.

**Shopify → AcelleMail:** Add new Shopify customers to a welcome sequence automatically.

**Calendly → AcelleMail:** Subscribe event attendees and trigger a pre-event reminder sequence.

**AcelleMail → Google Sheets:** Log every new subscriber to a spreadsheet for reporting.

Zapier's multi-step Zaps let you chain these — for example, subscribe a Typeform respondent and simultaneously create a CRM deal.
MD
            ],

            // =========================================================
            // DEVELOPER GUIDE
            // =========================================================

            [
                'category' => 'developer-guide',
                'title' => 'REST API Authentication and Endpoints',
                'type' => 'reference',
                'difficulty' => 'intermediate',
                'excerpt' => 'Authenticate with Bearer tokens and explore the core AcelleMail REST API endpoints with curl examples.',
                'tags' => ['api', 'rest-api'],
                'body' => <<<'MD'
## Authentication

AcelleMail uses Bearer token authentication. Generate a token at **Settings → API Tokens → Create**.

Include the token in every request header:

```bash
Authorization: Bearer your_token_here
```

## Base URL

```
https://yourdomain.com/api/v1/
```

## Core Endpoints

| Method | Endpoint | Description |
|---|---|---|
| GET | `/lists` | List all mailing lists |
| POST | `/subscribers` | Add a subscriber |
| GET | `/subscribers/{uid}` | Get subscriber details |
| DELETE | `/subscribers/{uid}` | Delete a subscriber |
| GET | `/campaigns` | List campaigns |
| POST | `/campaigns/{uid}/send` | Send a campaign |

## Curl Examples

**Add a subscriber:**

```bash
curl -X POST https://mail.yourdomain.com/api/v1/subscribers \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","first_name":"Jane","list_uid":"LIST_UID"}'
```

**Get all lists:**

```bash
curl https://mail.yourdomain.com/api/v1/lists \
  -H "Authorization: Bearer TOKEN"
```

**Unsubscribe by email:**

```bash
curl -X PATCH https://mail.yourdomain.com/api/v1/subscribers/unsubscribe \
  -H "Authorization: Bearer TOKEN" \
  -d '{"email":"user@example.com","list_uid":"LIST_UID"}'
```

## Response Format

All responses return JSON with a `status` field (`success` or `error`) and a `data` object. Errors include a `message` field describing the issue. HTTP status codes follow REST conventions (200, 201, 400, 401, 404, 422).
MD
            ],

            [
                'category' => 'developer-guide',
                'title' => 'Webhook Events Reference',
                'type' => 'reference',
                'difficulty' => 'intermediate',
                'excerpt' => 'Complete reference for all AcelleMail webhook events, payload structures, and verification.',
                'tags' => ['webhooks', 'rest-api'],
                'body' => <<<'MD'
## Overview

AcelleMail fires webhooks to your URL when key events occur. Configure endpoints at **Settings → Webhooks → Add**.

## Event Types

| Event | When it fires |
|---|---|
| `subscriber.created` | New subscriber added to a list |
| `subscriber.unsubscribed` | Subscriber opts out |
| `subscriber.bounced` | Hard or soft bounce recorded |
| `subscriber.complained` | Spam complaint received |
| `campaign.sent` | Campaign delivery completed |
| `campaign.opened` | First open tracked |
| `campaign.clicked` | Link click recorded |

## Payload Example

All events share a common envelope:

```json
{
  "event": "subscriber.created",
  "fired_at": "2026-03-15T10:23:00Z",
  "data": {
    "subscriber": {
      "uid": "sub_abc123",
      "email": "user@example.com",
      "first_name": "Jane",
      "status": "subscribed",
      "list_uid": "list_xyz"
    }
  }
}
```

## Verifying Webhook Signatures

AcelleMail signs each webhook with HMAC-SHA256. Verify in PHP:

```php
$secret = 'your_webhook_secret';
$signature = $_SERVER['HTTP_X_ACELLE_SIGNATURE'] ?? '';
$payload = file_get_contents('php://input');

$expected = hash_hmac('sha256', $payload, $secret);
if (!hash_equals($expected, $signature)) {
    http_response_code(401);
    exit('Invalid signature');
}
```

## Retry Logic

If your endpoint returns a non-2xx status, AcelleMail retries up to 5 times with exponential backoff (1 min, 5 min, 30 min, 2 hrs, 8 hrs). After 5 failures the webhook is marked inactive.
MD
            ],

            [
                'category' => 'developer-guide',
                'title' => 'Extending AcelleMail Source Code',
                'type' => 'guide',
                'difficulty' => 'advanced',
                'excerpt' => 'Understand AcelleMail\'s Laravel architecture and add custom features without breaking core functionality.',
                'tags' => ['laravel', 'open-source', 'self-hosted'],
                'body' => <<<'MD'
## Project Structure

AcelleMail is built on Laravel. Key directories:

```
app/
  Models/       — Eloquent models (MailList, Campaign, Subscriber...)
  Http/
    Controllers/Api/  — REST API controllers
    Controllers/Web/  — Web UI controllers
  Jobs/         — Queue jobs (SendEmail, TrackOpen...)
  Services/     — Business logic (MailService, SegmentService...)
resources/views/ — Blade templates
routes/
  api.php       — API routes
  web.php       — Web routes
```

## Adding a Custom Field to Subscribers

1. Create a migration: `php artisan make:migration add_phone_to_subscribers`
2. Add the column and update `$fillable` in `app/Models/Subscriber.php`
3. Expose via API by editing `SubscriberController@store`

## Custom Mail Provider

Implement the `MailClientInterface` in a new class under `app/Services/MailProviders/`, then register it in `config/mail_providers.php`. The interface requires `send()`, `verify()`, and `getQuota()` methods.

## Hooks and Events

AcelleMail dispatches Laravel events you can listen to without modifying core files:

```php
// In a Service Provider boot()
Event::listen(\App\Events\CampaignSent::class, function($event) {
    // Custom post-send logic
    Log::info('Campaign sent: ' . $event->campaign->name);
});
```

## Upgrade Safety

Keep customisations in separate Service Providers and avoid editing vendor or core model files directly. Use `extends` when possible. Document every change so upgrades are predictable.
MD
            ],

            // =========================================================
            // SAAS & MULTI-TENANT
            // =========================================================

            [
                'category' => 'saas-multi-tenant',
                'title' => 'Building Your Email Marketing SaaS',
                'type' => 'guide',
                'difficulty' => 'advanced',
                'excerpt' => 'Use AcelleMail\'s Extended License to launch a multi-tenant email marketing SaaS — setup, plans, and onboarding.',
                'tags' => ['multi-tenant', 'subscription-billing', 'codecanyon', 'white-label'],
                'body' => <<<'MD'
## The Extended License

AcelleMail's Extended License (available on CodeCanyon) allows you to sell access to the platform as a SaaS. You charge customers; you keep the revenue. One license covers unlimited end-users on one installation.

## Initial Setup Checklist

- [ ] Install AcelleMail on a VPS (min. 2 CPU, 4 GB RAM recommended)
- [ ] Configure a sending domain with SPF, DKIM, DMARC
- [ ] Set up a mail provider (Amazon SES, Mailgun, SendGrid)
- [ ] Enable **SaaS mode** in `config/app.php` → `saas_mode = true`
- [ ] Configure Stripe or PayPal in Settings → Payment Gateways

## Designing Plans

Go to **Admin → Plans → Create**. Each plan controls:

| Setting | Example |
|---|---|
| Subscriber limit | 5,000 |
| Emails per month | 50,000 |
| Campaigns | Unlimited |
| Automation | Yes/No |
| Custom sending domain | Yes/No |

## Customer Onboarding Flow

1. Customer visits your pricing page
2. Selects a plan → Stripe Checkout
3. AcelleMail creates a tenant account automatically
4. Welcome email sent with login credentials
5. Guided setup wizard (sending domain, first list)

## Scaling Tips

Separate your web and queue workers early. Use Redis for queues and cache. Add read replicas for MySQL once you exceed ~1,000 active tenants. Monitor queue depth — email sending is CPU and I/O intensive.
MD
            ],

            [
                'category' => 'saas-multi-tenant',
                'title' => 'White-Label Customization Guide',
                'type' => 'tutorial',
                'difficulty' => 'intermediate',
                'excerpt' => 'Rebrand AcelleMail with your logo, colors, custom domain, and remove all AcelleMail references.',
                'tags' => ['white-label', 'multi-tenant'],
                'body' => <<<'MD'
## Branding Settings

Go to **Admin → General Settings → Branding**:

- **App Name** — shown in UI, emails, and browser title
- **Logo** — PNG/SVG, displayed in navbar and emails
- **Favicon** — 32×32 ICO or PNG
- **Primary Color** — hex value, applied to buttons and links

These settings propagate automatically to all outgoing system emails (welcome, password reset, billing receipts).

## Custom Domain

Point your white-label domain (e.g., `send.yourbrand.com`) to your server IP. In **Settings → Custom Domain**, enter the domain. AcelleMail will auto-provision an SSL certificate via Let's Encrypt.

Update your DNS:

```
Type  Name               Value
A     send.yourbrand.com  YOUR_SERVER_IP
```

## Removing AcelleMail References

In `resources/views/layouts/`, search for "AcelleMail" and replace with your brand name. Key files:

- `app.blade.php` — main layout
- `emails/master.blade.php` — email footer
- `auth/login.blade.php` — login page

> Keep a diff of your changes in version control so upgrades remain manageable.

## Custom CSS

Add a `custom.css` file in `public/css/` and include it in `app.blade.php`. Target AcelleMail's BEM-style classes or override Bootstrap variables.

## Transactional Email Footer

Per CAN-SPAM/GDPR, your transactional emails must include a valid physical address. Update this in **Settings → Company** — it appears automatically in all outgoing emails.
MD
            ],

            [
                'category' => 'saas-multi-tenant',
                'title' => 'Subscription Plan Design Strategy',
                'type' => 'guide',
                'difficulty' => 'intermediate',
                'excerpt' => 'Design effective pricing tiers for your email SaaS — feature gates, upgrade triggers, and common pitfalls.',
                'tags' => ['subscription-billing', 'multi-tenant'],
                'body' => <<<'MD'
## The Three-Tier Model

Most successful email SaaS products use three tiers: Starter, Growth, and Pro (or Business). This covers SMBs, growing teams, and agencies without overwhelming new users with options.

| Plan | Subscribers | Price Signal | Key Gate |
|---|---|---|---|
| Starter | up to 1,000 | $15/mo | No automation |
| Growth | up to 10,000 | $49/mo | Automation + segments |
| Pro | up to 50,000 | $149/mo | Priority support + API |

## Choosing Feature Gates

Gate features that are valuable but not essential at entry level. Good gates:

- **Automations** — visible on Starter, locked until Growth
- **Advanced segments** — Growth+
- **Custom sending domain** — Pro or add-on
- **API access** — Pro or add-on
- **Dedicated IP** — Pro add-on

Avoid gating features that affect deliverability or basic usability — frustrated free/starter users churn and leave bad reviews.

## Upgrade Triggers

Design the UI to surface upgrade prompts at the right moment:

- Subscriber count reaches 90% of limit → banner + email
- User tries to create an automation on Starter → modal with upgrade CTA
- Team member invite on non-team plan → prompt

## Annual Discount

Offering 2 months free on annual billing (≈16% discount) typically converts 25–40% of monthly subscribers to annual, improving cash flow and reducing churn. Show annual pricing first.

## Free Trial vs. Freemium

A 14-day free trial (no card required) converts better than freemium for SaaS email tools. Freemium creates support burden from users who will never pay.
MD
            ],

            // =========================================================
            // SECURITY & COMPLIANCE
            // =========================================================

            [
                'category' => 'security-compliance',
                'title' => 'CAN-SPAM Compliance Checklist',
                'type' => 'guide',
                'difficulty' => 'beginner',
                'excerpt' => 'Seven CAN-SPAM requirements every US email sender must meet — with concrete examples for each.',
                'tags' => ['can-spam', 'data-privacy', 'gdpr'],
                'body' => <<<'MD'
## What is CAN-SPAM?

The CAN-SPAM Act (2003) applies to all commercial emails sent to US recipients. Violations carry penalties up to $51,744 per email. Here are the seven requirements.

## The 7 Requirements

**1. Don't use false or misleading header information**
The "From," "To," and routing information must accurately identify who sent the email.

**2. Don't use deceptive subject lines**
The subject must reflect the content. "You've won a prize" when it's a newsletter is a violation.

**3. Identify the message as an advertisement**
Unless you have express prior consent, clearly disclose the email is an ad. A small "Advertisement" label in the footer is sufficient.

**4. Tell recipients where you're located**
Include a valid physical postal address — street address, PO Box, or private mailbox.

```
Example Corp | 123 Main St, Suite 400 | Austin, TX 78701
```

**5. Tell recipients how to opt out**
Every email must include a clear, conspicuous unsubscribe mechanism.

**6. Honor opt-out requests promptly**
Process unsubscribe requests within 10 business days. AcelleMail handles this automatically.

**7. Monitor what others do on your behalf**
If you hire a third party to send email, you're still legally responsible.

## AcelleMail Handles Most of This

AcelleMail automatically adds an unsubscribe link, includes your company address (set in **Settings → Company**), and suppresses unsubscribed contacts. Your main responsibility is honest subject lines and header information.
MD
            ],

            [
                'category' => 'security-compliance',
                'title' => 'CASL: Canadian Anti-Spam Requirements',
                'type' => 'guide',
                'difficulty' => 'intermediate',
                'excerpt' => 'CASL is stricter than CAN-SPAM. Understand express vs. implied consent, record-keeping, and the penalty regime.',
                'tags' => ['casl', 'data-privacy', 'gdpr'],
                'body' => <<<'MD'
## Why CASL Matters

Canada's Anti-Spam Legislation (CASL) came into force in 2014 and is considered one of the world's strictest anti-spam laws. It covers any commercial electronic message (CEM) sent to or from Canada. Penalties reach CAD $10 million per violation for businesses.

## Express vs. Implied Consent

| Type | Definition | Expires |
|---|---|---|
| Express | Subscriber actively opted in (checkbox, sign-up form) | Never (until withdrawn) |
| Implied – existing business | Purchase, inquiry, or contract in past 2 years | 2 years |
| Implied – membership | Member of club/org | While membership active |

**CASL requires you to prove consent.** Keep records of when, where, and how consent was obtained.

## What Must Every CEM Include?

1. Your full legal name (or operating name)
2. Your mailing address and one of: phone, email, or web URL
3. A clear, functioning unsubscribe mechanism
4. Unsubscribes must be honoured within 10 business days

## Recording Consent in AcelleMail

Use subscription form source tracking to record consent origin. Add a custom field `consent_source` and populate it via the form embed or API:

```json
{ "email": "user@example.ca", "consent_source": "checkout-form-2026-01-15", "list_uid": "..." }
```

Store the form version and date in case of audit.

## The Transition Period is Over

The three-year implied consent transition period ended July 2017. Any implied consent from before that date has expired. Review your list if you have not already done so.
MD
            ],

            [
                'category' => 'security-compliance',
                'title' => 'Data Encryption for Self-Hosted Platforms',
                'type' => 'guide',
                'difficulty' => 'advanced',
                'excerpt' => 'Secure subscriber data at rest and in transit on your self-hosted AcelleMail instance.',
                'tags' => ['data-privacy', 'self-hosted', 'gdpr'],
                'body' => <<<'MD'
## Encryption in Transit (TLS)

All traffic must be served over HTTPS. On a typical VPS setup with Nginx:

```nginx
server {
    listen 443 ssl http2;
    ssl_certificate     /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;
    ssl_protocols       TLSv1.2 TLSv1.3;
    ssl_ciphers         HIGH:!aNULL:!MD5;
}
```

Use `certbot` to provision and auto-renew Let's Encrypt certificates. Redirect all HTTP to HTTPS with a 301.

## Encryption at Rest

**Database:** Enable MySQL encryption for tables containing PII:

```sql
ALTER TABLE subscribers ENCRYPTION='Y';
ALTER TABLE contacts ENCRYPTION='Y';
```

This requires MySQL 5.7.11+ with InnoDB encryption enabled in `my.cnf`.

**File storage:** For uploaded attachments or exports, use encrypted volumes. On AWS, use EBS volumes with KMS encryption. On bare metal, use LUKS.

## Application-Level Encryption

For highly sensitive fields (e.g., GDPR consent records), use Laravel's `encrypt()` helper:

```php
$subscriber->consent_text = encrypt($consentText);
// Retrieve:
$plain = decrypt($subscriber->consent_text);
```

Store the `APP_KEY` securely (not in your repo) — it's the master key.

## Backups

Encrypt database backups before storage:

```bash
mysqldump acelle_db | gzip | gpg --symmetric --cipher-algo AES256 -o backup.sql.gz.gpg
```

Rotate encryption keys annually and document the rotation procedure.
MD
            ],

            // =========================================================
            // EMAIL DESIGN
            // =========================================================

            [
                'category' => 'email-design',
                'title' => 'Dark Mode Email Design',
                'type' => 'tutorial',
                'difficulty' => 'intermediate',
                'excerpt' => 'Design emails that look great in both light and dark mode using CSS media queries and tested fallbacks.',
                'tags' => ['dark-mode', 'html-email', 'responsive-design'],
                'body' => <<<'MD'
## The Dark Mode Problem

About 35% of email clients render in dark mode by default. Without dark mode support, light backgrounds turn dark and light text becomes invisible — creating an unreadable email.

## CSS Media Query

Use `prefers-color-scheme` to switch colors:

```css
@media (prefers-color-scheme: dark) {
  body, .email-wrapper {
    background-color: #1a1a1a !important;
    color: #f0f0f0 !important;
  }
  .email-header {
    background-color: #2d2d2d !important;
  }
  a { color: #60b4ff !important; }
}
```

## Forcing Dark-Friendly Colors

Some clients (Gmail Android) invert colors automatically. Use `color-scheme` meta:

```html
<meta name="color-scheme" content="light dark">
<meta name="supported-color-schemes" content="light dark">
```

## Logo Handling

White logos disappear on dark backgrounds; black logos disappear in dark mode. Solutions:

| Approach | How |
|---|---|
| Transparent PNG | Works if logo uses mid-tones |
| Dark mode swap | Use `<picture>` element or CSS `content` swap |
| Outlined logo | Add a white outline/border on dark mode |

## Testing Dark Mode

Test in:
- Apple Mail (macOS/iOS) — most reliable dark mode support
- Outlook 2019+ on Windows — partial support
- Gmail app (Android) — force-inverts, needs testing
- Samsung Mail — aggressive inversion

Use Litmus or Email on Acid to preview across clients before sending.
MD
            ],

            [
                'category' => 'email-design',
                'title' => 'Mobile-First Email Principles',
                'type' => 'guide',
                'difficulty' => 'beginner',
                'excerpt' => 'Build emails that render beautifully on smartphones first, then scale up to desktop.',
                'tags' => ['responsive-design', 'html-email', 'mobile-first'],
                'body' => <<<'MD'
## Why Mobile-First

Over 60% of emails are opened on mobile devices. A 600px-wide desktop email that shrinks on a phone creates tiny unreadable text and impossible-to-tap links.

## Width and Layout

Set your email container to a max of **600px** with 100% width as the base:

```html
<table width="100%" style="max-width:600px; margin:0 auto;">
```

Use single-column layouts by default. Multi-column layouts require media queries and add complexity — only use them when the content genuinely benefits.

## Font Sizes

| Element | Minimum Size |
|---|---|
| Body text | 16px |
| Secondary text | 14px |
| Heading (H1) | 24px |
| Heading (H2) | 20px |

Below 14px becomes difficult to read without zooming. Use system fonts (`-apple-system, Arial, sans-serif`) for reliable rendering.

## Tap Targets

Every link and button must be at least **44×44px** — Apple's HIG minimum. A common mistake is using text links inline; they're hard to tap accurately.

## Images

Always include `width="100%"` and `max-width` on images. Add descriptive `alt` text — many mobile users have images off by default.

```html
<img src="banner.jpg" width="100%" style="max-width:600px; display:block;" alt="Spring sale — 30% off all products">
```

## Spacing

Use generous padding (16–24px) on mobile. Tight spacing makes content feel cramped and increases mis-taps. Add padding with inline styles — CSS classes are unreliable in older clients.
MD
            ],

            [
                'category' => 'email-design',
                'title' => 'Bulletproof CTA Buttons for Outlook',
                'type' => 'tutorial',
                'difficulty' => 'intermediate',
                'excerpt' => 'Create CTA buttons that render in every email client including Outlook, using VML and hybrid CSS.',
                'tags' => ['cta-buttons', 'html-email', 'responsive-design'],
                'body' => <<<'MD'
## The Outlook Button Problem

Outlook on Windows uses Microsoft Word as its rendering engine, which ignores CSS `border-radius`, `background-color` on `<a>` tags, and most modern CSS. The only reliable solution is VML (Vector Markup Language).

## The Bulletproof Button Code

```html
<!--[if mso]>
<v:roundrect xmlns:v="urn:schemas-microsoft-com:vml"
  xmlns:w="urn:schemas-microsoft-com:office:word"
  href="https://example.com/cta"
  style="height:44px; width:200px; v-text-anchor:middle;"
  arcsize="10%"
  fillcolor="#0066cc">
  <w:anchorlock/>
  <center style="color:#ffffff; font-family:Arial,sans-serif; font-size:16px; font-weight:bold;">
    Shop Now
  </center>
</v:roundrect>
<![endif]-->
<!--[if !mso]><!-->
<a href="https://example.com/cta"
   style="background-color:#0066cc; border-radius:4px; color:#ffffff;
          display:inline-block; font-family:Arial,sans-serif; font-size:16px;
          font-weight:bold; line-height:44px; padding:0 24px; text-decoration:none;">
  Shop Now
</a>
<!--<![endif]-->
```

## Key Parameters

| VML Attribute | Effect |
|---|---|
| `arcsize` | Corner rounding (10% ≈ 4px radius) |
| `fillcolor` | Button background |
| `height` | Button height |
| `v-text-anchor:middle` | Vertical center text |

## AcelleMail Template Tip

Save this as a block in AcelleMail's template library. When editing a campaign, insert the block and update only the `href` and label text — no need to re-code VML each time.
MD
            ],

            // =========================================================
            // ACELLEMAIL UPDATES
            // =========================================================

            [
                'category' => 'acellemail-updates',
                'title' => "What's New in AcelleMail 5.2",
                'type' => 'announcement',
                'difficulty' => 'beginner',
                'excerpt' => 'AcelleMail 5.2 ships AI-assisted writing, improved segmentation, and a refreshed template library.',
                'tags' => ['acellemail', 'open-source'],
                'body' => <<<'MD'
## Release Highlights

AcelleMail 5.2 is one of the most substantial updates in recent history, with improvements across the editor, automation engine, and sending infrastructure.

## AI Writing Assistant

The campaign editor now includes a built-in AI writing assistant powered by OpenAI. Highlight any text block and click **"Improve with AI"** to:

- Rewrite in a different tone (formal, casual, urgent)
- Expand a short bullet into a paragraph
- Generate subject line variations
- Translate to another language

Configure your OpenAI API key in **Settings → AI Features**.

## New Segment Conditions

Segmentation now supports:

| Condition | Example |
|---|---|
| Email domain | `@gmail.com` subscribers only |
| Custom field contains | `city contains "London"` |
| Campaign interaction | Opened any campaign in last 30 days |
| Subscriber age | Joined more than 90 days ago |

## Refreshed Template Library

50+ new responsive templates across categories: e-commerce, SaaS, events, newsletters, and re-engagement. All templates are dark mode compatible.

## Performance

- Campaign sending throughput improved by ~30% through batched queue processing
- List import is now async with progress tracking — no more timeouts on large CSVs
- Dashboard analytics queries optimized with new database indexes

## Upgrading

Run the standard upgrade process: pull the new code, run `composer install`, then `php artisan migrate`. No breaking changes to the API or database schema.
MD
            ],

            [
                'category' => 'acellemail-updates',
                'title' => 'AcelleMail 2026 Roadmap',
                'type' => 'announcement',
                'difficulty' => 'beginner',
                'excerpt' => 'A look at what the AcelleMail team is building in 2026 — new channels, deeper analytics, and a plugin system.',
                'tags' => ['acellemail', 'open-source'],
                'body' => <<<'MD'
## What's Coming in 2026

The AcelleMail team shared the public roadmap for 2026 at the start of the year. Here are the major themes.

## Q1: SMS & Multichannel

AcelleMail will expand beyond email to support SMS campaigns through Twilio and AWS SNS. Automations will support mixed email + SMS sequences — for example, send an email, wait 3 days, then follow up with an SMS if no open is recorded.

## Q2: Advanced Analytics

A rebuilt analytics dashboard with:

- Revenue tracking (connect to WooCommerce/Stripe)
- Cohort analysis — how do subscribers from different sources perform over time?
- Deliverability score per sending domain
- Heatmap overlay on campaign link clicks

## Q3: Plugin/Extension System

A formal extension API will allow third-party developers to build and distribute AcelleMail add-ons. Planned first-party extensions:

| Extension | Purpose |
|---|---|
| Referral Tracking | Track subscriber acquisition sources |
| Landing Pages | Build opt-in pages inside AcelleMail |
| Forms (advanced) | Multi-step forms with conditional logic |

## Q4: Self-Service Migration Tool

An official import wizard supporting migration from Mailchimp, Klaviyo, and ActiveCampaign — subscribers, segments, templates, and automation sequences.

## Staying Updated

Watch the [AcelleMail GitHub repository](https://github.com/acellemail/acelle) and subscribe to the official newsletter for release announcements.
MD
            ],

            [
                'category' => 'acellemail-updates',
                'title' => 'Community Spotlight: Business Case Studies',
                'type' => 'case-study',
                'difficulty' => 'beginner',
                'excerpt' => 'Three real-world stories of businesses using self-hosted AcelleMail to build sustainable email programs.',
                'tags' => ['acellemail', 'self-hosted', 'multi-tenant'],
                'body' => <<<'MD'
## Story 1: Digital Agency Replaces Mailchimp for 40 Clients

A Vietnamese digital agency was spending over $2,000/month on Mailchimp accounts for their clients. They migrated to a self-hosted AcelleMail instance on a $60/month VPS.

**Result:** Monthly cost dropped to $60 (server) + $40 (Amazon SES sending). Clients got white-labeled dashboards under the agency's brand. The agency now resells email marketing as a service.

**Key setup:** SaaS mode, white-label domain per client, shared Amazon SES account with per-client subaccounts for tracking.

## Story 2: E-Commerce Store Recovers Abandoned Carts

A mid-size WooCommerce store integrated AcelleMail with their store via the WordPress plugin. They built a three-email abandoned cart sequence triggered by webhook.

| Email | Timing | Offer |
|---|---|---|
| Reminder | 1 hour after abandon | None |
| Social proof | 24 hours | Customer reviews |
| Discount | 48 hours | 10% code |

**Result:** 18% cart recovery rate on a list of 45,000 subscribers — roughly $12,000/month in recovered revenue.

## Story 3: SaaS Startup Builds Onboarding Sequences

A B2B SaaS company used AcelleMail automations to deliver a 14-email onboarding sequence based on feature usage data passed via API.

New users who completed the full sequence had a 3.4× higher 90-day retention rate than those who didn't. The sequence was built entirely in AcelleMail's visual automation builder with no custom code.
MD
            ],

            [
                'category' => 'acellemail-updates',
                'title' => 'Contributing to AcelleMail Development',
                'type' => 'guide',
                'difficulty' => 'intermediate',
                'excerpt' => 'How to contribute bug fixes, features, and translations to the AcelleMail open-source project.',
                'tags' => ['acellemail', 'open-source', 'laravel'],
                'body' => <<<'MD'
## The Open-Source Edition

AcelleMail's Community Edition is open source (MIT license) and hosted on GitHub. The CodeCanyon version is the commercial release with additional SaaS features and priority support. Contributions to the community edition benefit all users.

## Getting Started

```bash
git clone https://github.com/acellemail/acelle.git
cd acelle
cp .env.example .env
composer install
npm install && npm run dev
php artisan key:generate
php artisan migrate --seed
```

## Contribution Workflow

1. **Fork** the repository on GitHub
2. Create a feature branch: `git checkout -b fix/unsubscribe-race-condition`
3. Write a test that covers your change (PHPUnit/Pest)
4. Implement the fix
5. Run the test suite: `./vendor/bin/pest`
6. Submit a Pull Request with a clear description

## What the Team Accepts

| Type | Guidance |
|---|---|
| Bug fixes | Always welcome with a reproducing test |
| Performance | Include benchmarks before/after |
| New features | Open a Discussion first — alignment needed |
| Translations | Edit files in `resources/lang/` |
| Documentation | Edit markdown files in `docs/` |

## Community Channels

- **GitHub Discussions** — feature proposals, Q&A
- **GitHub Issues** — confirmed bugs with reproduction steps
- **Discord** — real-time chat with contributors and users

Before opening an issue, search existing ones — duplicate reports slow down the team. Include your PHP version, AcelleMail version, and exact error message.
MD
            ],

        ];
    }
}
