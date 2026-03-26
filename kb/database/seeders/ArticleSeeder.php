<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = $this->getArticles();

        foreach ($articles as $data) {
            $category = Category::where('slug', $data['category'])->first();
            if (!$category) continue;

            $article = Article::create([
                'title' => $data['title'],
                'slug' => \Illuminate\Support\Str::slug($data['title']),
                'excerpt' => $data['excerpt'],
                'body_markdown' => $data['body'],
                'body_html' => app(\App\Services\MarkdownService::class)->toHtml($data['body']),
                'category_id' => $category->id,
                'status' => 'published',
                'content_type' => $data['type'] ?? 'tutorial',
                'difficulty' => $data['difficulty'] ?? 'intermediate',
                'reading_time' => max(1, (int) ceil(str_word_count($data['body']) / 200)),
                'published_at' => now()->subDays(rand(1, 180)),
                'views_count' => rand(50, 5000),
            ]);

            // Attach random tags
            $tagSlugs = $data['tags'] ?? [];
            $tagIds = Tag::whereIn('slug', $tagSlugs)->pluck('id')->toArray();
            if (!empty($tagIds)) {
                $article->tags()->attach($tagIds);
            }
        }

        // Update category counts
        foreach (Category::all() as $cat) {
            $cat->update(['articles_count' => $cat->articles()->where('status', 'published')->count()]);
        }
    }

    private function getArticles(): array
    {
        return [
            // --- Email Marketing ---
            [
                'title' => 'Creating Your First Email Campaign in AcelleMail',
                'excerpt' => 'Learn how to create, design, and send your first email campaign using AcelleMail\'s drag-and-drop builder.',
                'category' => 'email-marketing',
                'type' => 'tutorial', 'difficulty' => 'beginner',
                'tags' => ['email-templates', 'drag-and-drop'],
                'body' => <<<'MD'
## Getting Started

AcelleMail makes it easy to create professional email campaigns without any coding knowledge. In this tutorial, we'll walk through the entire process from start to finish.

## Step 1: Create a New Campaign

Navigate to **Campaigns → Create New** in your AcelleMail dashboard. You'll be asked to choose:

- **Campaign name**: Internal name for your reference
- **Subject line**: What recipients see in their inbox
- **From name**: Your brand or personal name
- **From email**: Your verified sending address

## Step 2: Select Your Audience

Choose which subscriber list to send to. You can also apply segments to target specific groups:

- All subscribers on a list
- A saved segment (e.g., "Active last 30 days")
- Multiple lists combined

## Step 3: Design Your Email

AcelleMail offers two editor modes:

### Drag-and-Drop Builder
The visual builder includes pre-built blocks:
- **Text block**: Rich text with formatting options
- **Image block**: Upload or link to images
- **Button block**: CTA buttons with customizable styles
- **Divider**: Visual separators
- **Social links**: Auto-generated social icons

### HTML Editor
For advanced users, switch to the HTML editor for full control:

```html
<table width="600" cellpadding="0" cellspacing="0">
  <tr>
    <td style="padding: 20px;">
      <h1>Hello {FIRST_NAME}!</h1>
      <p>Welcome to our newsletter.</p>
    </td>
  </tr>
</table>
```

## Step 4: Preview and Test

Before sending, always:
1. **Preview** on desktop and mobile views
2. **Send a test email** to yourself
3. Check all links are working
4. Verify merge tags render correctly

## Step 5: Schedule or Send

Choose to send immediately or schedule for a specific date/time. AcelleMail supports timezone-aware scheduling.

> **Tip:** Tuesday through Thursday, 10am-2pm in your audience's timezone typically gets the best open rates.
MD
            ],
            [
                'title' => 'A/B Testing Email Subject Lines for Better Open Rates',
                'excerpt' => 'How to use AcelleMail\'s built-in A/B testing to find subject lines that maximize opens and engagement.',
                'category' => 'email-marketing',
                'type' => 'guide', 'difficulty' => 'intermediate',
                'tags' => ['a-b-testing'],
                'body' => <<<'MD'
## Why A/B Test Subject Lines?

Your subject line is the single most important factor in whether someone opens your email. A/B testing (also called split testing) lets you compare two or more variants to find what works best.

## How A/B Testing Works in AcelleMail

1. Create your campaign as usual
2. Enable "A/B Test" in the campaign settings
3. Write 2-5 subject line variants
4. Set the test audience size (typically 20-30% of your list)
5. Choose the winning metric (open rate or click rate)
6. Set the test duration (2-24 hours)
7. AcelleMail automatically sends the winner to the remaining subscribers

## What to Test

### Length
- Short: "Sale ends tonight" (3 words)
- Medium: "Don't miss our biggest sale of the year" (8 words)
- Long: "We're running our biggest sale ever — 50% off everything in store until midnight" (14 words)

### Personalization
- Without: "Check out our new collection"
- With: "{FIRST_NAME}, your new collection is here"

### Tone
- Formal: "Quarterly Business Review Report Available"
- Casual: "Your Q3 numbers are in 📊"

### Urgency
- No urgency: "New products available"
- With urgency: "Last chance: 24 hours left"

## Best Practices

- **Test one variable at a time** — don't change subject AND preheader simultaneously
- **Use a large enough sample** — at least 1,000 subscribers per variant
- **Wait long enough** — 4 hours minimum for opens to accumulate
- **Document results** — track what works for YOUR audience over time

> **Note:** Open rate tracking is affected by Apple Mail Privacy Protection. Consider using click rate as your winning metric for more accurate results.
MD
            ],
            [
                'title' => 'Email Template Design Best Practices',
                'excerpt' => 'Design responsive, accessible email templates that look great across all devices and email clients.',
                'category' => 'email-marketing',
                'type' => 'guide', 'difficulty' => 'intermediate',
                'tags' => ['email-templates', 'drag-and-drop'],
                'body' => <<<'MD'
## Email Design Fundamentals

Email rendering is notoriously inconsistent across clients. Here's how to design templates that work everywhere.

## Layout Rules

### Single Column
Always use a single-column layout for the main content. Multi-column layouts break on mobile.

```html
<!-- Good: Single column, max 600px -->
<table width="600" style="max-width: 600px; margin: 0 auto;">
  <tr><td>Your content here</td></tr>
</table>
```

### Mobile-First
- **Min tap target**: 44×44 pixels for buttons and links
- **Font size**: Minimum 14px body, 22px headings on mobile
- **Padding**: At least 20px on sides for mobile

## Typography

- Use web-safe fonts: Arial, Helvetica, Georgia, Verdana
- Google Fonts work in some clients but not all — always provide fallback
- Line height: 1.5 for body text, 1.2 for headings

## Images

- Always include `alt` text
- Don't use images for critical content (some clients block images by default)
- Optimize file size (under 200KB per image)
- Use `width` and `height` attributes for proper loading

## CTA Buttons

```html
<!-- Bulletproof button (works in Outlook) -->
<table cellpadding="0" cellspacing="0">
  <tr>
    <td style="background:#E8571A; border-radius:4px; padding:12px 24px;">
      <a href="https://example.com" style="color:#ffffff; text-decoration:none; font-weight:bold;">
        Shop Now
      </a>
    </td>
  </tr>
</table>
```

## Dark Mode

Modern email clients support dark mode. Test your designs:
- Use transparent PNGs for logos (not JPGs with white backgrounds)
- Avoid pure white (#ffffff) backgrounds — use light gray (#f5f5f5)
- Set both light and dark color scheme meta tags
MD
            ],

            // --- Sending & Deliverability ---
            [
                'title' => 'How to Set Up SPF, DKIM, and DMARC Records',
                'excerpt' => 'Complete guide to configuring email authentication DNS records for maximum deliverability.',
                'category' => 'sending-deliverability',
                'type' => 'tutorial', 'difficulty' => 'intermediate',
                'tags' => ['spf', 'dkim', 'dmarc'],
                'body' => <<<'MD'
## Why Email Authentication Matters

Email authentication prevents spoofing and improves deliverability. Without it, ISPs are more likely to send your emails to spam.

## SPF (Sender Policy Framework)

SPF tells receiving servers which IPs are allowed to send email for your domain.

### Setup

Add a TXT record to your domain's DNS:

```dns
yourdomain.com  TXT  "v=spf1 include:amazonses.com include:sendgrid.net ~all"
```

### Key Rules
- Only ONE SPF record per domain
- Use `include:` for each sending service
- End with `~all` (softfail) or `-all` (hardfail)
- Max 10 DNS lookups (keep includes minimal)

### Verify
```bash
dig TXT yourdomain.com | grep spf
```

## DKIM (DomainKeys Identified Mail)

DKIM adds a cryptographic signature to your emails proving they haven't been tampered with.

### Setup

Your sending service provides DKIM records. Add them as CNAME or TXT records:

```dns
selector1._domainkey.yourdomain.com  CNAME  selector1.dkim.amazonses.com
```

### Verify
```bash
dig CNAME selector1._domainkey.yourdomain.com
```

## DMARC (Domain-based Message Authentication)

DMARC ties SPF and DKIM together and tells ISPs what to do with unauthenticated messages.

### Setup (Start with monitoring)

```dns
_dmarc.yourdomain.com  TXT  "v=DMARC1; p=none; rua=mailto:dmarc@yourdomain.com; pct=100"
```

### Enforcement Levels
| Policy | Action | When to Use |
|--------|--------|-------------|
| `p=none` | Monitor only | Starting out |
| `p=quarantine` | Send to spam | After monitoring |
| `p=reject` | Block entirely | Full enforcement |

### Gradual Enforcement
1. Start with `p=none` for 2-4 weeks
2. Review DMARC reports (aggregate reports sent to `rua` address)
3. Fix any legitimate senders failing authentication
4. Move to `p=quarantine` with `pct=25` (25% enforcement)
5. Gradually increase `pct` to 100
6. Finally move to `p=reject`

> **Warning:** Never jump straight to `p=reject` — you might block legitimate email from services you forgot to authenticate.
MD
            ],
            [
                'title' => 'Configuring Amazon SES with AcelleMail',
                'excerpt' => 'Step-by-step guide to connecting Amazon Simple Email Service as your sending server in AcelleMail.',
                'category' => 'sending-deliverability',
                'type' => 'tutorial', 'difficulty' => 'intermediate',
                'tags' => ['amazon-ses', 'smtp'],
                'body' => <<<'MD'
## Prerequisites

- AWS account with SES access
- Verified domain or email in SES
- SES moved out of sandbox (for production)
- AcelleMail installed and running

## Step 1: Create IAM Credentials

In AWS Console → IAM → Users → Create User:

```json
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Effect": "Allow",
            "Action": [
                "ses:SendEmail",
                "ses:SendRawEmail"
            ],
            "Resource": "*"
        }
    ]
}
```

Save the Access Key ID and Secret Access Key.

## Step 2: Generate SMTP Credentials

In SES Console → SMTP Settings → Create SMTP Credentials. This generates a separate username/password specifically for SMTP.

> **Note:** SMTP credentials are different from IAM access keys. You need SMTP credentials for AcelleMail.

## Step 3: Configure in AcelleMail

Navigate to **Admin → Sending Servers → Add New**:

| Field | Value |
|-------|-------|
| Name | Amazon SES (us-east-1) |
| Type | SMTP |
| Host | email-smtp.us-east-1.amazonaws.com |
| Port | 587 |
| Encryption | TLS |
| Username | (SMTP username from Step 2) |
| Password | (SMTP password from Step 2) |
| Sending Limit | 14/second (SES default) |

## Step 4: Verify Domain in SES

Add these DNS records from the SES console:

```dns
; DKIM
abc123._domainkey.yourdomain.com CNAME abc123.dkim.amazonses.com
def456._domainkey.yourdomain.com CNAME def456.dkim.amazonses.com
ghi789._domainkey.yourdomain.com CNAME ghi789.dkim.amazonses.com

; MAIL FROM (optional but recommended)
mail.yourdomain.com  MX  10 feedback-smtp.us-east-1.amazonses.com
mail.yourdomain.com  TXT "v=spf1 include:amazonses.com ~all"
```

## Step 5: Set Up Bounce Handling

Configure SNS notifications in SES:
1. Create SNS topic for bounces
2. Create SNS topic for complaints
3. Subscribe AcelleMail's bounce webhook URL to both topics
4. AcelleMail auto-processes bounces and complaints

## Cost

Amazon SES costs approximately **$0.10 per 1,000 emails** sent. Sending 100,000 emails costs just $10.
MD
            ],
            [
                'title' => 'IP Warmup Schedule for New Sending Servers',
                'excerpt' => 'A proven warmup schedule to build sender reputation on new IPs without getting blocked by ISPs.',
                'category' => 'sending-deliverability',
                'type' => 'reference', 'difficulty' => 'advanced',
                'tags' => ['warmup', 'smtp'],
                'body' => <<<'MD'
## Why Warm Up?

New IPs have zero reputation. If you send thousands of emails immediately, ISPs will flag you as a potential spammer. Warming up gradually builds trust.

## Recommended Schedule

| Week | Day | Daily Volume | Cumulative | Notes |
|------|-----|-------------|-----------|-------|
| 1 | 1-2 | 50 | 100 | Most engaged subscribers only |
| 1 | 3-4 | 100 | 300 | Monitor for bounces |
| 1 | 5-7 | 250 | 1,050 | Check inbox placement |
| 2 | 8-10 | 500 | 2,550 | Review reputation dashboards |
| 2 | 11-14 | 1,000 | 6,550 | |
| 3 | 15-18 | 2,500 | 16,550 | |
| 3 | 19-21 | 5,000 | 31,550 | Google Postmaster check |
| 4 | 22-25 | 10,000 | 71,550 | |
| 4 | 26-28 | 25,000 | 146,550 | |
| 5 | 29-35 | 50,000 | 496,550 | |
| 6+ | 36+ | Full volume | - | Maintain consistency |

## AcelleMail Configuration

Set per-server sending limits to enforce the warmup schedule:

```
Admin → Sending Servers → Edit → Sending Limit
Day 1-2: 50 emails per day
Day 3-4: 100 emails per day
...adjust weekly
```

## Monitoring During Warmup

### Key Metrics to Watch
- **Bounce rate**: Must stay below 2%
- **Complaint rate**: Must stay below 0.1%
- **Inbox placement**: Use Google Postmaster Tools

### Red Flags (Stop and Investigate)
- Bounce rate exceeds 5%
- Complaint rate exceeds 0.3%
- Emails deferred by major ISPs (Gmail, Yahoo)
- IP listed on any blacklist (check mxtoolbox.com)

### Tools for Monitoring
```bash
# Check blacklists
curl -s "https://mxtoolbox.com/api/v1/Lookup/blacklist/?argument=YOUR_IP"

# Check Google Postmaster Tools
# → https://postmaster.google.com/
```

## Tips for Success

1. **Start with your best subscribers** — recent openers and clickers
2. **Send consistently** — don't skip days during warmup
3. **Separate by ISP** — warm up Gmail, Yahoo, Outlook independently if possible
4. **Use authentication** — SPF, DKIM, DMARC must be configured before warmup
5. **Avoid purchased lists** — only send to confirmed opt-in subscribers
MD
            ],

            // --- Installation & Setup ---
            [
                'title' => 'Installing AcelleMail on Ubuntu 22.04',
                'excerpt' => 'Complete installation guide for AcelleMail on a fresh Ubuntu 22.04 server with Nginx and MySQL.',
                'category' => 'installation-setup',
                'type' => 'tutorial', 'difficulty' => 'beginner',
                'tags' => ['laravel', 'php', 'mysql'],
                'body' => <<<'MD'
## Prerequisites

- Ubuntu 22.04 LTS server (2GB RAM minimum)
- Root or sudo access
- Domain name pointed to server IP

## Step 1: Install PHP 8.3

```bash
sudo apt update && sudo apt upgrade -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt install php8.3 php8.3-fpm php8.3-mysql php8.3-mbstring \
  php8.3-xml php8.3-curl php8.3-zip php8.3-gd php8.3-intl \
  php8.3-bcmath php8.3-redis -y
```

## Step 2: Install MySQL 8.0

```bash
sudo apt install mysql-server -y
sudo mysql_secure_installation

# Create database
sudo mysql -e "CREATE DATABASE acellemail CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
sudo mysql -e "CREATE USER 'acellemail'@'localhost' IDENTIFIED BY 'your_password';"
sudo mysql -e "GRANT ALL ON acellemail.* TO 'acellemail'@'localhost';"
```

## Step 3: Install Composer

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

## Step 4: Install Nginx

```bash
sudo apt install nginx -y
```

## Step 5: Upload AcelleMail

```bash
cd /var/www
# Upload your AcelleMail files here
sudo chown -R www-data:www-data /var/www/acellemail
```

## Step 6: Configure Nginx

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/acellemail/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

## Step 7: Install Dependencies

```bash
cd /var/www/acellemail
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
```

## Step 8: Configure Environment

Edit `.env` with your database credentials and domain.

## Step 9: Run Installer

Visit `https://yourdomain.com/install` in your browser and follow the web installer.

## Step 10: Set Up Cron & Queue

```bash
# Cron (run every minute)
echo "* * * * * www-data cd /var/www/acellemail && php artisan schedule:run >> /dev/null 2>&1" | sudo tee /etc/cron.d/acellemail

# SSL with Let's Encrypt
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d yourdomain.com
```
MD
            ],
            [
                'title' => 'Server Requirements and Hosting Options',
                'excerpt' => 'What you need to run AcelleMail — from shared hosting to cloud VPS, with detailed specifications.',
                'category' => 'installation-setup',
                'type' => 'reference', 'difficulty' => 'beginner',
                'tags' => ['laravel', 'php', 'mysql', 'redis'],
                'body' => <<<'MD'
## Minimum Requirements

| Component | Minimum | Recommended |
|-----------|---------|-------------|
| PHP | 8.1 | 8.3 |
| MySQL | 5.7 | 8.0 |
| RAM | 2 GB | 4 GB |
| Storage | 10 GB | 50 GB |
| CPU | 1 vCPU | 2 vCPU |
| OS | Ubuntu 20.04 | Ubuntu 22.04 |

## Required PHP Extensions

```
php-mbstring  php-xml  php-curl  php-zip
php-gd  php-intl  php-bcmath  php-mysql
php-redis (optional)  php-imagick (optional)
```

## Hosting Options

### Shared Hosting ($3-10/month)
- **Pros**: Cheapest, managed
- **Cons**: Limited resources, no root access, no cron sometimes
- **Works for**: Small lists (under 5,000 subscribers)

### VPS ($5-20/month)
- **Pros**: Full control, scalable, root access
- **Cons**: Self-managed
- **Best for**: Most users, recommended
- **Providers**: DigitalOcean, Vultr, Linode, Hetzner

### Dedicated ($50+/month)
- **Pros**: Maximum performance
- **Cons**: Expensive
- **Best for**: 100K+ subscribers, high-volume sending

### Cloud (Pay-as-you-go)
- **Providers**: AWS EC2, Google Cloud, Azure
- **Best for**: Variable workloads, auto-scaling needs

## Recommended Stack

```
Ubuntu 22.04 + Nginx + PHP 8.3-FPM + MySQL 8.0 + Redis
```

This stack gives you the best performance for AcelleMail.
MD
            ],

            // --- DNS & Domain Setup ---
            [
                'title' => 'Complete DNS Setup for Email Sending',
                'excerpt' => 'All the DNS records you need to configure for proper email delivery: SPF, DKIM, DMARC, MX, and more.',
                'category' => 'dns-domain-setup',
                'type' => 'reference', 'difficulty' => 'intermediate',
                'tags' => ['spf', 'dkim', 'dmarc'],
                'body' => <<<'MD'
## Overview of Required DNS Records

| Record Type | Name | Purpose |
|-------------|------|---------|
| SPF (TXT) | yourdomain.com | Authorize sending IPs |
| DKIM (CNAME/TXT) | selector._domainkey | Cryptographic signature |
| DMARC (TXT) | _dmarc.yourdomain.com | Authentication policy |
| MX | yourdomain.com | Receive bounce emails |
| PTR | IP address | Reverse DNS |

## Full Example Setup

Assuming you use Amazon SES in us-east-1:

```dns
; SPF — authorize SES to send for your domain
yourdomain.com.  TXT  "v=spf1 include:amazonses.com ~all"

; DKIM — three CNAME records from SES console
abc._domainkey.yourdomain.com.  CNAME  abc.dkim.amazonses.com.
def._domainkey.yourdomain.com.  CNAME  def.dkim.amazonses.com.
ghi._domainkey.yourdomain.com.  CNAME  ghi.dkim.amazonses.com.

; DMARC — start with monitoring
_dmarc.yourdomain.com.  TXT  "v=DMARC1; p=none; rua=mailto:dmarc-reports@yourdomain.com"

; MX — for bounce processing (optional, if receiving mail)
yourdomain.com.  MX  10  inbound-smtp.us-east-1.amazonaws.com.

; MAIL FROM subdomain (recommended by SES)
mail.yourdomain.com.  MX  10  feedback-smtp.us-east-1.amazonses.com.
mail.yourdomain.com.  TXT  "v=spf1 include:amazonses.com ~all"
```

## Verification Commands

```bash
# Check SPF
dig TXT yourdomain.com +short | grep spf

# Check DKIM
dig CNAME abc._domainkey.yourdomain.com +short

# Check DMARC
dig TXT _dmarc.yourdomain.com +short

# Check MX
dig MX yourdomain.com +short

# Full check with mxtoolbox
# https://mxtoolbox.com/SuperTool.aspx
```

## Common Mistakes

1. **Multiple SPF records** — only ONE TXT record starting with `v=spf1` per domain
2. **Missing DKIM** — each sending service needs its own DKIM
3. **DMARC too strict too fast** — always start with `p=none`
4. **Forgetting MAIL FROM** — improves alignment and deliverability
MD
            ],

            // --- Automation ---
            [
                'title' => 'Building a Welcome Email Series in AcelleMail',
                'excerpt' => 'Create an automated welcome sequence that nurtures new subscribers with a 5-email drip campaign.',
                'category' => 'automation',
                'type' => 'tutorial', 'difficulty' => 'beginner',
                'tags' => ['automation'],
                'body' => <<<'MD'
## The Welcome Series

A welcome series is a sequence of automated emails sent to new subscribers. It's your chance to make a great first impression and guide them toward becoming a customer.

## Recommended 5-Email Sequence

### Email 1: Immediate Welcome (Sent instantly)
- Thank them for subscribing
- Deliver any promised lead magnet
- Set expectations for future emails

### Email 2: Your Story (Day 2)
- Share your brand story
- Build personal connection
- Highlight your unique value proposition

### Email 3: Best Content (Day 4)
- Share your top-performing content
- Most popular blog posts, videos, or resources
- Establish yourself as an authority

### Email 4: Social Proof (Day 7)
- Customer testimonials
- Case studies or success stories
- Reviews and ratings

### Email 5: Soft Sell (Day 10)
- Present your product/service
- Special offer for new subscribers
- Clear call-to-action

## Setting Up in AcelleMail

1. Go to **Automations → Create New**
2. Set trigger: "Subscriber joins list"
3. Add actions:

```
Trigger: New subscriber joins "Main List"
  ↓
Send Email: "Welcome to [Brand]!"
  ↓
Wait: 2 days
  ↓
Send Email: "Our Story"
  ↓
Wait: 2 days
  ↓
Send Email: "Best Resources"
  ↓
Condition: Opened any previous email?
  ├─ Yes → Wait 3 days → Send "Social Proof" → Wait 3 days → Send "Special Offer"
  └─ No → Wait 5 days → Send "We miss you" (re-engagement)
```

## Tips

- **Personalize**: Use merge tags ({FIRST_NAME})
- **Brand consistently**: Same design template across all emails
- **Test deliverability**: Send test emails before activating
- **Monitor**: Check open/click rates weekly and optimize
MD
            ],
            [
                'title' => 'Advanced Automation Triggers and Conditions',
                'excerpt' => 'Master AcelleMail\'s automation engine with advanced triggers, conditional logic, and branching workflows.',
                'category' => 'automation',
                'type' => 'guide', 'difficulty' => 'advanced',
                'tags' => ['automation', 'webhooks'],
                'body' => <<<'MD'
## Beyond Basic Triggers

AcelleMail supports sophisticated automation triggers that go beyond simple list subscriptions.

## Available Triggers

### Behavioral Triggers
- **Email opened**: Trigger when subscriber opens a specific campaign
- **Link clicked**: Trigger on click of a specific URL in any email
- **Page visited**: Track website visits (requires tracking pixel)

### Data-Based Triggers
- **Date field match**: Birthday, anniversary, renewal date
- **Custom field change**: When any subscriber field value changes
- **Tag applied**: When a specific tag is added to a subscriber

### External Triggers
- **API webhook**: Trigger from your app via REST API
- **Zapier/Make**: Third-party automation platforms

## Conditional Branching

### If/Else Conditions
```
Condition: subscriber.custom_field("plan") == "premium"
  ├─ True → Send premium upsell email
  └─ False → Send upgrade offer email
```

### Multi-Branch
```
Condition: subscriber.tag
  ├─ Has "purchased" → Post-purchase flow
  ├─ Has "trial" → Trial nurture flow
  └─ Default → General nurture flow
```

## Advanced Patterns

### Lead Scoring
Assign points based on engagement and trigger actions at score thresholds:
- Email opened: +1 point
- Link clicked: +3 points
- Page visited: +5 points
- Score > 50: Add "hot-lead" tag → Trigger sales notification

### Re-Engagement Waterfall
```
Wait 30 days since last open
  ↓
Send "We miss you" email
  ↓
Wait 7 days → Check: Opened?
  ├─ Yes → Remove "inactive" tag, continue normal flow
  └─ No → Send "Last chance" email with incentive
       ↓
       Wait 7 days → Check: Opened?
         ├─ Yes → Welcome back flow
         └─ No → Add "unengaged" tag → Consider removal
```

## API Trigger Example

```bash
curl -X POST https://your-acellemail.com/api/v1/automations/trigger \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "automation_id": "abc123",
    "subscriber_email": "user@example.com",
    "data": {
      "event": "purchase_completed",
      "order_total": 99.00
    }
  }'
```
MD
            ],

            // --- Security & Compliance ---
            [
                'title' => 'GDPR Compliance Guide for Email Marketing',
                'excerpt' => 'Everything you need to know about GDPR compliance when using AcelleMail for email marketing in the EU.',
                'category' => 'security-compliance',
                'type' => 'guide', 'difficulty' => 'intermediate',
                'tags' => ['gdpr'],
                'body' => <<<'MD'
## GDPR and Email Marketing

The General Data Protection Regulation (GDPR) applies to any business that processes personal data of EU residents, regardless of where the business is located.

## Key Requirements

### 1. Lawful Basis for Processing
For marketing emails, you need **explicit consent**:
- Consent must be freely given, specific, informed, and unambiguous
- Pre-ticked checkboxes do NOT count as consent
- You must record when and how consent was given

### 2. Right to Access (Article 15)
Subscribers can request all data you hold about them. In AcelleMail:
- Export subscriber profile from admin panel
- Include: email, name, custom fields, subscription date, engagement history

### 3. Right to Erasure (Article 17)
"Right to be forgotten" — delete all subscriber data on request:
- Remove from all lists
- Delete engagement history
- Remove from any exports or backups

### 4. Right to Portability (Article 20)
Export subscriber data in a machine-readable format (CSV/JSON).

### 5. Data Processing Agreement
If using third-party sending services (SES, SendGrid), you need a DPA with each provider.

## AcelleMail GDPR Features

- **Double opt-in** with customizable confirmation emails
- **Consent checkboxes** on signup forms (customizable text)
- **One-click data export** for any subscriber
- **One-click data deletion** with confirmation
- **Self-hosted**: Data stays on YOUR server — no third-party sharing by default
- **Audit trail**: Track consent timestamps and sources

## Signup Form Requirements

```html
<form>
  <input type="email" name="email" required>
  <label>
    <input type="checkbox" name="consent" required>
    I agree to receive marketing emails from [Company].
    I understand I can unsubscribe at any time.
    <a href="/privacy">Privacy Policy</a>
  </label>
  <button type="submit">Subscribe</button>
</form>
```

> **Important:** The consent checkbox must NOT be pre-checked. The subscriber must actively opt in.
MD
            ],

            // --- Best Practices ---
            [
                'title' => 'Email Subject Line Formulas That Work',
                'excerpt' => 'Proven subject line templates and psychological triggers to boost your email open rates.',
                'category' => 'best-practices',
                'type' => 'guide', 'difficulty' => 'beginner',
                'tags' => ['a-b-testing'],
                'body' => <<<'MD'
## Why Subject Lines Matter

47% of email recipients decide to open based on the subject line alone. Here are proven formulas.

## Formulas

### The Question
- "Are you making these email mistakes?"
- "What's your email marketing ROI?"
- Works because: triggers curiosity

### The Number/List
- "7 ways to improve deliverability"
- "3 mistakes killing your open rates"
- Works because: specific, scannable promise

### The How-To
- "How to set up DKIM in 5 minutes"
- "How we increased opens by 40%"
- Works because: promises practical value

### The Urgency
- "Last day: 50% off annual plans"
- "Your trial expires tomorrow"
- Works because: fear of missing out (FOMO)

### The Personal
- "{FIRST_NAME}, your weekly digest is ready"
- "A message from our founder"
- Works because: feels one-to-one

### The Curiosity Gap
- "We almost didn't send this..."
- "The one thing most marketers get wrong"
- Works because: creates information gap

## Length Guidelines

| Device | Visible Characters |
|--------|-------------------|
| Desktop | 60-80 characters |
| Mobile | 30-40 characters |
| Apple Watch | 12-18 characters |

**Rule of thumb**: Keep it under 50 characters for best results.

## What to Avoid

- ALL CAPS (looks like shouting)
- Excessive punctuation!!! or emojis 🎉🎊🔥
- Deceptive subject lines (CAN-SPAM violation)
- "Re:" or "Fwd:" tricks (destroys trust)
- Spam trigger words: "free", "guaranteed", "act now"
MD
            ],

            // --- Migration & Comparison ---
            [
                'title' => 'Migrating from Mailchimp to AcelleMail',
                'excerpt' => 'Step-by-step guide to migrating your subscriber lists, templates, and campaigns from Mailchimp.',
                'category' => 'migration-comparison',
                'type' => 'tutorial', 'difficulty' => 'intermediate',
                'tags' => [],
                'body' => <<<'MD'
## Why Migrate?

| Aspect | Mailchimp | AcelleMail |
|--------|-----------|------------|
| Cost (10K subs) | $100/month | $64 one-time |
| Cost (50K subs) | $350/month | $64 one-time |
| Annual cost | $1,200-4,200 | $64-199 total |
| Data ownership | Mailchimp's servers | Your server |
| Source code | No access | Full source |
| Subscriber limits | Per plan | Unlimited |

## Migration Steps

### 1. Export from Mailchimp
- Go to Audience → All Contacts → Export Audience
- Download CSV file
- Also export: segments, tags, automation workflows (manually document these)

### 2. Prepare Your Data
Clean the CSV:
- Remove unsubscribed/bounced contacts (don't re-import them)
- Map Mailchimp fields to AcelleMail fields
- Standard mapping: EMAIL → email, FNAME → first_name, LNAME → last_name

### 3. Install AcelleMail
Follow the installation guide for your server.

### 4. Import Subscribers
- Go to Lists → Your List → Import
- Upload CSV
- Map fields
- Choose "Subscribe" as default status
- Enable "skip existing" to avoid duplicates

### 5. Recreate Templates
- Export HTML from Mailchimp templates
- Import into AcelleMail's template editor
- Or use AcelleMail's drag-and-drop builder to recreate

### 6. Recreate Automations
Document your Mailchimp automations and rebuild in AcelleMail:
- Welcome series
- Abandoned cart
- Re-engagement
- Birthday emails

### 7. Update DNS
Point your sending domain authentication to your new sending server:
- Update SPF record
- Add new DKIM records
- Keep DMARC in monitor mode during transition

### 8. Warm Up (if using new IP)
Follow the warmup schedule in our warmup guide.

## Timeline

| Week | Action |
|------|--------|
| 1 | Install AcelleMail, import lists, recreate templates |
| 2 | Recreate automations, configure sending server |
| 3 | DNS migration, start warmup |
| 4 | Begin sending from AcelleMail (small batches) |
| 5-6 | Gradual migration of all sending |
| 7+ | Cancel Mailchimp subscription |

> **Tip:** Run both platforms in parallel for 2-4 weeks during transition. Send different campaigns from each to avoid duplicates.
MD
            ],

            // --- Developer Guide ---
            [
                'title' => 'Getting Started with the AcelleMail REST API',
                'excerpt' => 'Authenticate and make your first API calls to manage subscribers, campaigns, and automations programmatically.',
                'category' => 'developer-guide',
                'type' => 'tutorial', 'difficulty' => 'intermediate',
                'tags' => ['api', 'webhooks', 'laravel', 'php'],
                'body' => <<<'MD'
## Authentication

All API requests require a Bearer token. Get yours from AcelleMail admin → API → Generate Token.

```bash
# Test authentication
curl -H "Authorization: Bearer YOUR_API_TOKEN" \
     https://your-acellemail.com/api/v1/me
```

## Common Operations

### List All Subscribers

```bash
curl -H "Authorization: Bearer TOKEN" \
     "https://your-acellemail.com/api/v1/lists/LIST_UID/subscribers?per_page=20"
```

### Add a Subscriber

```bash
curl -X POST \
     -H "Authorization: Bearer TOKEN" \
     -H "Content-Type: application/json" \
     -d '{
       "EMAIL": "john@example.com",
       "FIRST_NAME": "John",
       "LAST_NAME": "Doe",
       "tag": "api-import"
     }' \
     "https://your-acellemail.com/api/v1/lists/LIST_UID/subscribers"
```

### PHP/Laravel Example

```php
use Illuminate\Support\Facades\Http;

$response = Http::withToken(config('services.acellemail.token'))
    ->post('https://your-acellemail.com/api/v1/lists/LIST_UID/subscribers', [
        'EMAIL' => $user->email,
        'FIRST_NAME' => $user->name,
    ]);

if ($response->successful()) {
    // Subscriber added
    $subscriberUid = $response->json('subscriber_uid');
}
```

### Webhook Integration

Configure webhooks in AcelleMail to notify your app of events:

```php
// routes/api.php
Route::post('/webhooks/acellemail', function (Request $request) {
    $event = $request->input('event');
    $data = $request->input('data');

    match($event) {
        'subscriber.added' => handleNewSubscriber($data),
        'email.bounced' => handleBounce($data),
        'email.complained' => handleComplaint($data),
        default => null,
    };

    return response('OK', 200);
});
```

## Rate Limits

| Endpoint | Limit |
|----------|-------|
| GET requests | 60/minute |
| POST/PUT/DELETE | 30/minute |
| Bulk operations | 10/minute |

## Error Handling

```json
{
    "status": "error",
    "message": "Subscriber already exists",
    "code": 409
}
```

Always check the `status` field in responses and handle errors gracefully.
MD
            ],

            // --- Analytics ---
            [
                'title' => 'Understanding Email Analytics and Key Metrics',
                'excerpt' => 'Learn how to read your campaign analytics dashboard and which metrics matter most for email success.',
                'category' => 'analytics-reporting',
                'type' => 'guide', 'difficulty' => 'beginner',
                'tags' => [],
                'body' => <<<'MD'
## Key Email Metrics

### Open Rate
**Formula**: (Unique Opens / Emails Delivered) × 100

- Industry average: 20-25%
- Tracked via invisible 1×1 pixel image
- Affected by Apple MPP (inflated for Apple Mail users)

### Click-Through Rate (CTR)
**Formula**: (Unique Clicks / Emails Delivered) × 100

- Industry average: 2-5%
- More reliable than open rate
- Measures actual engagement

### Click-to-Open Rate (CTOR)
**Formula**: (Unique Clicks / Unique Opens) × 100

- Industry average: 10-15%
- Best measure of content effectiveness
- High CTOR = good content, regardless of subject line

### Bounce Rate
**Formula**: (Bounces / Emails Sent) × 100

- Should be under 2%
- Hard bounces: permanent (invalid email)
- Soft bounces: temporary (mailbox full)

### Complaint Rate
**Formula**: (Spam Complaints / Emails Delivered) × 100

- Must stay below 0.1%
- Higher = ISPs may throttle or block you
- Gmail's threshold: 0.3% (danger zone)

## AcelleMail Dashboard

Your campaign report includes:
- Real-time delivery progress
- Open and click tracking (hourly breakdown)
- Geographic map (opens by country)
- Device breakdown (desktop vs mobile)
- Click map (visual heatmap of link clicks)
- Subscriber-level engagement data
- Export to CSV for further analysis

## What Good Looks Like

| Metric | Poor | Average | Good | Excellent |
|--------|------|---------|------|-----------|
| Open Rate | <15% | 15-20% | 20-30% | >30% |
| CTR | <1% | 1-2% | 2-5% | >5% |
| Bounce Rate | >5% | 2-5% | 1-2% | <1% |
| Unsub Rate | >1% | 0.5-1% | 0.1-0.5% | <0.1% |

## Improving Your Metrics

- **Low opens?** → Test subject lines, send times, sender name
- **Low clicks?** → Improve CTA placement, content relevance, design
- **High bounces?** → Clean your list, verify emails before import
- **High complaints?** → Send more relevant content, honor frequency expectations
MD
            ],

            // --- List Management ---
            [
                'title' => 'Advanced Segmentation Strategies',
                'excerpt' => 'Create powerful subscriber segments based on behavior, demographics, and engagement to send targeted emails.',
                'category' => 'list-management',
                'type' => 'guide', 'difficulty' => 'advanced',
                'tags' => ['segmentation'],
                'body' => <<<'MD'
## Why Segment?

Segmented campaigns get 14.31% higher open rates and 100.95% higher click rates than non-segmented campaigns (Mailchimp benchmark data).

## Segment Types

### Engagement-Based
- **Active**: Opened or clicked in last 30 days
- **Engaged**: Opened 3+ emails in last 90 days
- **At-risk**: No opens in 60-90 days
- **Inactive**: No opens in 90+ days

### Demographic
- Location (country, state, city)
- Language preference
- Job title / industry
- Company size

### Behavioral
- Purchase history (buyers vs non-buyers)
- Content preferences (which links they click)
- Signup source (form, import, API)
- Tags (VIP, trial, enterprise)

## Building Segments in AcelleMail

### Combining Conditions
```
Segment: "High-Value Active Subscribers"
Rules:
  - opened_count > 5  (last 90 days)
  - AND has_tag = "customer"
  - AND country = "US"
```

### Dynamic vs Static Segments
- **Dynamic**: Auto-updates as subscriber data changes (recommended)
- **Static**: Frozen at creation time (useful for one-time campaigns)

## Practical Segment Examples

### "Win-Back" Segment
```
last_opened > 60 days ago
AND subscription_date < 6 months ago
AND NOT has_tag "unsubscribed"
```

### "VIP Customers" Segment
```
has_tag "purchased"
AND open_count > 10 (all time)
AND click_count > 5 (all time)
```

### "New Subscribers" Segment
```
subscription_date within last 14 days
AND NOT has_tag "welcomed"
```

## Tips

1. Start with 3-5 core segments, not 50
2. Name segments clearly (avoid "Segment 1")
3. Review segment sizes monthly
4. Automate segment-based workflows
5. Use engagement segments for warmup targeting
MD
            ],

            // --- Integrations ---
            [
                'title' => 'Integrating AcelleMail with WordPress and WooCommerce',
                'excerpt' => 'Sync your WordPress users and WooCommerce customers with AcelleMail for automated email marketing.',
                'category' => 'integrations',
                'type' => 'tutorial', 'difficulty' => 'intermediate',
                'tags' => ['wordpress', 'woocommerce', 'api'],
                'body' => <<<'MD'
## Overview

Connect AcelleMail with WordPress to automatically sync subscribers and trigger automations based on user actions.

## WordPress Integration

### Method 1: API Integration (Recommended)

Add subscribers when they register on your WordPress site:

```php
// functions.php or custom plugin
add_action('user_register', function($user_id) {
    $user = get_user_by('id', $user_id);

    wp_remote_post('https://your-acellemail.com/api/v1/lists/LIST_UID/subscribers', [
        'headers' => [
            'Authorization' => 'Bearer YOUR_API_TOKEN',
            'Content-Type' => 'application/json',
        ],
        'body' => json_encode([
            'EMAIL' => $user->user_email,
            'FIRST_NAME' => $user->first_name,
            'LAST_NAME' => $user->last_name,
        ]),
    ]);
});
```

### Method 2: Signup Form Embed

Embed AcelleMail's signup form on your WordPress site:
1. Create a form in AcelleMail → Forms → Create
2. Copy the embed code
3. Paste into a WordPress HTML block or widget

## WooCommerce Integration

### Sync Customers on Purchase

```php
add_action('woocommerce_thankyou', function($order_id) {
    $order = wc_get_order($order_id);

    wp_remote_post('https://your-acellemail.com/api/v1/lists/LIST_UID/subscribers', [
        'headers' => [
            'Authorization' => 'Bearer YOUR_API_TOKEN',
            'Content-Type' => 'application/json',
        ],
        'body' => json_encode([
            'EMAIL' => $order->get_billing_email(),
            'FIRST_NAME' => $order->get_billing_first_name(),
            'LAST_NAME' => $order->get_billing_last_name(),
            'tag' => 'woocommerce-customer,purchased',
            'ORDER_TOTAL' => $order->get_total(),
        ]),
    ]);
});
```

### Automated Flows
After integration, set up automations in AcelleMail:
- **Post-purchase follow-up**: Thank you + review request
- **Abandoned cart**: Reminder emails (requires cart tracking plugin)
- **Win-back**: Re-engage customers who haven't purchased in 90 days
- **Product recommendations**: Based on purchase history tags

## Webhook for Real-Time Sync

Configure AcelleMail webhooks to update WordPress when subscribers change:

```php
// REST API endpoint in WordPress
add_action('rest_api_init', function() {
    register_rest_route('acellemail/v1', '/webhook', [
        'methods' => 'POST',
        'callback' => function($request) {
            $event = $request->get_param('event');
            $email = $request->get_param('data')['email'];

            if ($event === 'subscriber.removed') {
                // Handle unsubscribe in WordPress
            }

            return new WP_REST_Response('OK', 200);
        },
    ]);
});
```
MD
            ],

            // --- SaaS & Multi-tenant ---
            [
                'title' => 'Setting Up AcelleMail as a SaaS Platform',
                'excerpt' => 'Configure the Extended License to run your own email marketing SaaS with subscription billing and white-label.',
                'category' => 'saas-multi-tenant',
                'type' => 'tutorial', 'difficulty' => 'advanced',
                'tags' => ['white-label', 'multi-tenant', 'stripe', 'paypal'],
                'body' => <<<'MD'
## Prerequisites

- AcelleMail Extended License ($199)
- Stripe or PayPal account for billing
- Server with adequate resources (4GB+ RAM recommended)

## Step 1: Configure Payment Gateway

### Stripe Setup

In AcelleMail Admin → Settings → Payment:

```
Gateway: Stripe
Publishable Key: pk_live_xxxxx
Secret Key: sk_live_xxxxx
Webhook Secret: whsec_xxxxx
```

Set up Stripe webhook:
- URL: `https://yourdomain.com/cashier/webhook`
- Events: `invoice.paid`, `customer.subscription.deleted`

### PayPal Setup
Similar configuration with PayPal Client ID and Secret.

## Step 2: Create Subscription Plans

Admin → Plans → Create New Plan:

| Field | Starter | Pro | Enterprise |
|-------|---------|-----|-----------|
| Price | $29/month | $79/month | $199/month |
| Subscribers | 2,500 | 15,000 | 50,000 |
| Emails/month | 25,000 | 150,000 | 500,000 |
| Automations | 3 | Unlimited | Unlimited |
| Sending Servers | Shared | Shared | Dedicated |
| A/B Testing | No | Yes | Yes |
| Custom Domain | No | Yes | Yes |

## Step 3: White-Label Branding

### Remove "Powered by AcelleMail"
Extended License allows full white-labeling:
- Custom logo (replace in admin settings)
- Custom color scheme (CSS customization)
- Custom domain per customer (CNAME setup)
- Custom from addresses

### Custom Login Page

```php
// Customize resources/views/auth/login.blade.php
// Add your own branding, colors, and messaging
```

## Step 4: Tenant Management

Each customer (tenant) gets:
- Separate subscriber lists
- Separate campaigns and templates
- Isolated sending quotas
- Own automation workflows
- Usage dashboard

## Revenue Model

| Customers | Plan | Monthly Revenue |
|-----------|------|----------------|
| 10 | Starter ($29) | $290 |
| 25 | Pro ($79) | $1,975 |
| 5 | Enterprise ($199) | $995 |
| **Total** | **40 customers** | **$3,260/month** |

Your cost: $199 one-time + ~$50/month server + sending costs.

> **Pro tip:** Start with 2-3 plans. You can always add more tiers later based on customer demand.
MD
            ],

            // --- Server Management ---
            [
                'title' => 'Setting Up Queue Workers and Cron Jobs',
                'excerpt' => 'Configure Supervisor for queue workers and cron jobs essential for AcelleMail email processing.',
                'category' => 'server-management',
                'type' => 'tutorial', 'difficulty' => 'intermediate',
                'tags' => ['cron-jobs', 'queue-workers', 'redis'],
                'body' => <<<'MD'
## Why Queue Workers Matter

AcelleMail uses Laravel queues to process emails asynchronously. Without queue workers running, emails won't be sent, automations won't trigger, and bounces won't be processed.

## Cron Job Setup

AcelleMail's scheduler handles periodic tasks. Add this to your crontab:

```bash
sudo crontab -u www-data -e
# Add this line:
* * * * * cd /var/www/acellemail && php artisan schedule:run >> /dev/null 2>&1
```

The scheduler runs these tasks:
- Process automation workflows
- Send scheduled campaigns
- Process bounce/complaint notifications
- Clean up expired sessions
- Generate reports

## Queue Worker with Supervisor

### Install Supervisor

```bash
sudo apt install supervisor -y
```

### Create Worker Configuration

```bash
sudo nano /etc/supervisor/conf.d/acellemail-worker.conf
```

```ini
[program:acellemail-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/acellemail/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/acellemail/storage/logs/worker.log
stopwaitsecs=3600
```

### Start Workers

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start acellemail-worker:*
```

### Monitor Workers

```bash
# Check status
sudo supervisorctl status

# View logs
tail -f /var/www/acellemail/storage/logs/worker.log

# Restart after code changes
sudo supervisorctl restart acellemail-worker:*
```

## Redis Configuration (Recommended)

Using Redis as queue driver is faster than database driver:

```bash
sudo apt install redis-server -y
sudo systemctl enable redis-server
```

In `.env`:
```env
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

## Troubleshooting

### Emails not sending?
```bash
# Check if workers are running
sudo supervisorctl status

# Check queue size
php artisan queue:monitor redis:default --max=100

# Process failed jobs
php artisan queue:retry all
```

### Worker crashes?
```bash
# Check supervisor logs
sudo tail -f /var/log/supervisor/supervisord.log

# Check worker logs
tail -f /var/www/acellemail/storage/logs/worker.log
```
MD
            ],

            // --- Troubleshooting ---
            [
                'title' => 'Debugging Common Email Delivery Issues',
                'excerpt' => 'Diagnose and fix the most common problems with email delivery, bounces, and inbox placement.',
                'category' => 'troubleshooting',
                'type' => 'guide', 'difficulty' => 'intermediate',
                'tags' => ['bounce-handling', 'smtp'],
                'body' => <<<'MD'
## Common Issues and Solutions

### 1. Emails Going to Spam

**Symptoms**: Low open rates, subscribers report emails in spam folder.

**Checklist**:
- [ ] SPF record configured correctly
- [ ] DKIM signing active
- [ ] DMARC policy set
- [ ] Sending from authenticated domain (not gmail.com)
- [ ] Content not triggering spam filters
- [ ] IP/domain not on any blacklists
- [ ] Consistent sending volume (no sudden spikes)

**Diagnosis**:
```bash
# Check blacklists
# Visit: https://mxtoolbox.com/blacklists.aspx

# Check authentication
# Visit: https://www.mail-tester.com/
# Send a test email to the provided address

# Check Google reputation
# Visit: https://postmaster.google.com/
```

### 2. High Bounce Rate

**Symptoms**: Bounce rate above 2%, delivery failures.

**Common Causes**:
- Old/stale email list
- Purchased or scraped lists (never do this)
- Typos in email addresses
- Temporary server issues (soft bounces)

**Solutions**:
- Enable email verification before import
- Remove hard bounces immediately
- Re-confirm old lists before sending
- Use double opt-in for all new subscribers

### 3. Emails Not Sending

**Symptoms**: Campaign stuck in "sending" status, queue not processing.

**Debug Steps**:
```bash
# Check queue workers
sudo supervisorctl status

# Check failed jobs
php artisan queue:failed

# Check sending server connection
php artisan tinker
>>> Mail::raw('Test', function($m) { $m->to('test@test.com'); });

# Check logs
tail -f storage/logs/laravel.log
```

### 4. Slow Sending Speed

**Symptoms**: Large campaigns take hours to complete.

**Solutions**:
- Increase queue workers (`numprocs=4` in supervisor)
- Use Redis instead of database queue driver
- Check sending server rate limits
- Configure multiple sending servers with rotation

### 5. Images Not Loading in Emails

**Symptoms**: Broken image icons in received emails.

**Causes**:
- Images hosted on HTTP (not HTTPS)
- Server blocking hotlinking
- Images too large (timeout)

**Solutions**:
- Always use HTTPS URLs for images
- Host images on your server or CDN
- Optimize image file sizes (under 200KB each)
- Use `alt` text for all images
MD
            ],
        ];
    }
}
