<?php
namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleBatch2Seeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->getArticles() as $data) {
            $cat = Category::where('slug', $data['cat'])->first();
            if (!$cat) continue;
            $a = Article::create([
                'title' => $data['title'], 'slug' => Str::slug($data['title']),
                'excerpt' => $data['excerpt'], 'body_markdown' => $data['body'],
                'body_html' => app(\App\Services\MarkdownService::class)->toHtml($data['body']),
                'category_id' => $cat->id, 'status' => 'published',
                'content_type' => $data['type'] ?? 'tutorial', 'difficulty' => $data['diff'] ?? 'intermediate',
                'reading_time' => max(1, (int) ceil(str_word_count($data['body']) / 200)),
                'published_at' => now()->subDays(rand(1, 180)), 'views_count' => rand(200, 12000),
            ]);
            $ids = Tag::whereIn('slug', $data['tags'] ?? [])->pluck('id')->toArray();
            if ($ids) $a->tags()->attach($ids);
        }
    }

    private function getArticles(): array
    {
        return [

            // =========================================================
            // SENDING & DELIVERABILITY
            // =========================================================

            [
                'cat' => 'sending-deliverability',
                'title' => 'Understanding Email Bounce Types',
                'type' => 'guide',
                'diff' => 'intermediate',
                'excerpt' => 'Hard bounces and soft bounces behave very differently. Learn the SMTP codes behind each type and how AcelleMail handles them to protect your sender reputation.',
                'tags' => ['bounce-handling', 'smtp', 'sender-reputation'],
                'body' => <<<'MD'
## Hard vs Soft Bounces

A **hard bounce** is a permanent delivery failure. The recipient address does not exist, the domain is invalid, or the receiving server has explicitly rejected the message. AcelleMail automatically unsubscribes hard-bounced addresses to prevent further damage to your sender reputation.

A **soft bounce** is a temporary failure. The mailbox may be full, the server temporarily unavailable, or the message too large. AcelleMail retries soft bounces according to your queue retry settings before eventually marking them as failed.

## SMTP Bounce Codes

| Code | Type | Meaning |
|------|------|---------|
| 550 | Hard | Mailbox does not exist |
| 551 | Hard | User not local |
| 553 | Hard | Mailbox name invalid |
| 421 | Soft | Service temporarily unavailable |
| 452 | Soft | Mailbox full |
| 451 | Soft | Local processing error |

## How AcelleMail Processes Bounces

AcelleMail uses two methods to detect bounces:

1. **Feedback Loop (FBL)** — ISPs send complaint notifications back to your sending server.
2. **Bounce mailbox polling** — AcelleMail connects to a dedicated bounce email inbox (IMAP/POP3) and parses incoming DSN (Delivery Status Notification) messages.

Configure your bounce mailbox under **Settings → Sending Servers → [your server] → Bounce Handler**.

## Best Practices

- Keep your hard bounce rate below **2%** — most ISPs throttle senders who exceed this.
- Run a list verification pass before every large campaign.
- Never re-add a hard-bounced address manually without explicit re-confirmation from the subscriber.
- Review your bounce log monthly: **Reports → Bounced Subscribers**.
MD
            ],

            [
                'cat' => 'sending-deliverability',
                'title' => 'Email Throttling and Rate Limits Explained',
                'type' => 'guide',
                'diff' => 'intermediate',
                'excerpt' => 'Each ESP and ISP enforces its own sending rate limits. Learn the per-provider limits and how to configure throttling in AcelleMail to stay within them.',
                'tags' => ['throttling', 'amazon-ses', 'sendgrid', 'smtp', 'sender-reputation'],
                'body' => <<<'MD'
## Why Throttling Matters

Sending too fast triggers spam filters and can get your account suspended. Every receiving mail server — and every sending service — enforces rate limits. Throttling tells AcelleMail to spread your campaign sends over time.

## Common Provider Limits

| Provider | Default Limit | Notes |
|----------|--------------|-------|
| Amazon SES (sandbox) | 1 msg/sec, 200/day | Request production increase |
| Amazon SES (production) | 14 msgs/sec | Varies by account age |
| SendGrid Free | 100/day | Upgrade for more |
| SendGrid Essentials | 100 msgs/sec | |
| Mailgun Flex | 5,000/month free | |
| Gmail SMTP | 500/day | Not for bulk use |
| Your own Postfix | Unlimited* | Throttle by recipient ISP |

*Sending unlimited via your own server is technically possible but ISPs will throttle or reject bursts — configure wisely.

## Configuring Throttling in AcelleMail

Navigate to **Settings → Sending Servers → [your server]** and set:

- **Speed (emails/hour)** — total throughput cap
- **Sending limit** — optional daily cap

For campaigns, you can also set per-campaign throttling under the **Schedule** tab.

## Per-ISP Throttling Tips

Gmail, Yahoo, and Outlook each have informal limits for new IPs:

- Start with **200–500/day** per ISP on a new IP.
- Ramp up 20–50% per week during warmup.
- Watch your **Defer rate** — a high defer count means you are sending too fast.

Use AcelleMail's **Sending Server Pool** to distribute load across multiple servers when a single server's limit is not enough.
MD
            ],

            [
                'cat' => 'sending-deliverability',
                'title' => 'Configuring Multiple Sending Servers',
                'type' => 'tutorial',
                'diff' => 'intermediate',
                'excerpt' => 'Use a pool of sending servers to distribute campaign load, achieve IP rotation, and set up automatic failover when one server goes down.',
                'tags' => ['smtp', 'ip-rotation', 'sender-reputation', 'acellemail'],
                'body' => <<<'MD'
## Why Use Multiple Sending Servers

A single SMTP server is a single point of failure and a throughput bottleneck. Spreading sends across multiple servers gives you:

- **Higher throughput** — run parallel delivery queues.
- **IP rotation** — different recipient ISPs see different source IPs, reducing per-IP reputation risk.
- **Failover** — if one server goes offline, campaigns continue via the others.

## Setting Up a Sending Server Pool

1. Add each server under **Settings → Sending Servers**. Verify SMTP credentials for each.
2. Go to **Settings → Sending Server Pools → Create Pool**.
3. Add servers to the pool and assign a **weight** to each (higher weight = more sends).

| Server | Weight | Role |
|--------|--------|------|
| smtp-primary | 10 | Main delivery |
| smtp-backup | 5 | Secondary |
| amazon-ses | 3 | Overflow |

## Assigning a Pool to a Campaign

When creating a campaign, select the pool under **Sending Settings → Sending Server**. AcelleMail will rotate through the pool servers based on weight.

## Failover Behavior

AcelleMail automatically skips a server if it returns a connection error and retries on the next available server in the pool. Mark a server as **Inactive** manually to remove it from rotation immediately.

## Monitoring Per-Server Stats

Check individual server performance under **Reports → Sending Servers**. Look for:

- **Bounce rate per server** — indicates IP reputation issues.
- **Deferred rate** — indicates throttling by receiving ISPs.
- **Queue depth** — indicates a bottleneck on that server.

Rotate out any server with a bounce rate above 3%.
MD
            ],

            // =========================================================
            // DNS & DOMAIN SETUP
            // =========================================================

            [
                'cat' => 'dns-domain-setup',
                'title' => 'BIMI Setup: Show Your Logo in Gmail',
                'type' => 'tutorial',
                'diff' => 'advanced',
                'excerpt' => 'BIMI displays your brand logo next to emails in Gmail and Apple Mail. Learn the SVG requirements, VMC certificate, and the DNS TXT record format.',
                'tags' => ['bimi', 'dmarc', 'dkim', 'spf', 'sender-reputation'],
                'body' => <<<'MD'
## What Is BIMI

Brand Indicators for Message Identification (BIMI) allows mail clients to display your company logo next to authenticated emails. Gmail, Apple Mail, and Yahoo Mail support BIMI. It requires a valid DMARC policy of `p=quarantine` or `p=reject`.

## Prerequisites

- SPF record passing
- DKIM signing enabled
- DMARC at `p=quarantine` or `p=reject` with at least 30 days of clean history
- A Verified Mark Certificate (VMC) from DigiCert or Entrust *(required for Gmail)*

## SVG Logo Requirements

Gmail requires a **Scalable Vector Graphic (SVG Tiny PS)** format:

- Square aspect ratio
- No external references or scripts
- Under 32 KB file size
- Hosted at a public HTTPS URL

Validate your SVG at [validator.bimigroup.org](https://bimigroup.org/bimi-generator/).

## DNS Record Format

Add a TXT record to your DNS:

```
Host:  default._bimi.yourdomain.com
Type:  TXT
Value: v=BIMI1; l=https://yourdomain.com/logo.svg; a=https://yourdomain.com/vmc.pem
```

The `l=` field is your SVG URL. The `a=` field is your VMC certificate URL (required for Gmail — optional for others).

## Checking BIMI

Use `dig` to confirm propagation:

```bash
dig TXT default._bimi.yourdomain.com
```

Gmail may take 1–2 weeks to start showing the logo after all checks pass. Use [MXToolbox BIMI lookup](https://mxtoolbox.com/bimi.aspx) to verify your record before testing in Gmail.
MD
            ],

            [
                'cat' => 'dns-domain-setup',
                'title' => 'Custom Tracking Domain for Click URLs',
                'type' => 'tutorial',
                'diff' => 'intermediate',
                'excerpt' => 'Replace AcelleMail\'s default tracking domain with your own branded domain using a CNAME record and an SSL certificate.',
                'tags' => ['dkim', 'tls', 'lets-encrypt', 'nginx', 'acellemail'],
                'body' => <<<'MD'
## Why Use a Custom Tracking Domain

By default, AcelleMail wraps every link in your emails with its own tracking domain. If subscribers see `clicks.acelle.io` in URLs, it looks suspicious and can trigger spam filters. A custom tracking domain like `track.yourbrand.com` improves trust and deliverability.

## Step 1: Create the CNAME Record

Add this DNS record at your domain registrar or DNS provider:

```
Host:   track.yourbrand.com
Type:   CNAME
Value:  yourserver.com   (or the IP via an A record)
TTL:    3600
```

Wait for propagation (typically 15 minutes to a few hours).

## Step 2: Configure Nginx for the Subdomain

Create `/etc/nginx/sites-available/track.yourbrand.com`:

```nginx
server {
    listen 80;
    server_name track.yourbrand.com;
    location / {
        proxy_pass http://127.0.0.1:8000;
        proxy_set_header Host \$host;
        proxy_set_header X-Real-IP \$remote_addr;
    }
}
```

Enable and reload: `sudo ln -s ../sites-available/track.yourbrand.com /etc/nginx/sites-enabled/ && sudo nginx -s reload`

## Step 3: SSL Certificate

```bash
sudo certbot --nginx -d track.yourbrand.com
```

Certbot will auto-configure the HTTPS block.

## Step 4: Set in AcelleMail

Go to **Settings → Sending Domains → [your domain] → Tracking Domain**. Enter `https://track.yourbrand.com` and save.

Test by sending a test email and hovering over a link — it should now show your custom domain.
MD
            ],

            [
                'cat' => 'dns-domain-setup',
                'title' => 'DNS Propagation Explained',
                'type' => 'guide',
                'diff' => 'beginner',
                'excerpt' => 'DNS changes don\'t take effect immediately. Understand TTL, caching, and how to verify propagation has completed worldwide.',
                'tags' => ['spf', 'dkim', 'dmarc'],
                'body' => <<<'MD'
## What Is DNS Propagation

When you add or change a DNS record (SPF, DKIM, DMARC, CNAME), that change must spread to DNS resolvers worldwide. This process is called **DNS propagation**. It is not instant — it depends on the **Time-to-Live (TTL)** value of the record.

## Understanding TTL

TTL is a number in seconds. It tells DNS resolvers how long to cache a record before checking for updates.

| TTL Value | Cache Duration |
|-----------|---------------|
| 300 | 5 minutes |
| 3600 | 1 hour |
| 86400 | 24 hours |

Lower TTL = faster propagation but more DNS queries. For initial setup, set TTL to **300** so you can make corrections quickly. Raise it to **3600** once everything is confirmed working.

## How to Check Propagation

**Online tools:**

- [dnschecker.org](https://dnschecker.org) — shows results from 20+ global locations
- [MXToolbox](https://mxtoolbox.com) — specialized for email DNS records
- [whatsmydns.net](https://whatsmydns.net) — real-time worldwide view

**Command line:**

```bash
# Check SPF record
dig TXT yourdomain.com

# Check DKIM record
dig TXT selector._domainkey.yourdomain.com

# Check DMARC record
dig TXT _dmarc.yourdomain.com
```

## Why Propagation Seems Inconsistent

Your local machine may still show old records after you change DNS, because your ISP's resolver has cached the old TTL. Flush your local DNS cache:

```bash
# macOS
sudo dscacheutil -flushcache && sudo killall -HUP mDNSResponder

# Ubuntu/Debian
sudo systemd-resolve --flush-caches
```

Full worldwide propagation typically completes in **15 minutes to 48 hours** depending on TTL and resolver behavior.
MD
            ],

            [
                'cat' => 'dns-domain-setup',
                'title' => 'Return-Path and Envelope Sender Explained',
                'type' => 'guide',
                'diff' => 'intermediate',
                'excerpt' => 'The Return-Path header controls where bounce notifications go. Learn how it differs from the From address and why alignment matters for DMARC.',
                'tags' => ['spf', 'dmarc', 'bounce-handling', 'smtp'],
                'body' => <<<'MD'
## What Is the Return-Path

Every email has two "From" addresses:

- **Header From** (`From:`) — the address your subscribers see in their mail client.
- **Envelope Sender** (also called MAIL FROM or Return-Path) — the address used during the SMTP conversation, where bounces are delivered.

These can be different, and that difference matters for both bounce handling and DMARC alignment.

## The SMTP Conversation

During delivery, the sending server opens an SMTP connection and states:

```
MAIL FROM: <bounces@mail.yourdomain.com>
RCPT TO: <subscriber@gmail.com>
DATA
From: Your Brand <hello@yourdomain.com>
Subject: ...
```

The `MAIL FROM` is the envelope sender. Gmail will set the `Return-Path:` header in the delivered message to this address.

## Why It Matters for DMARC

SPF checks the **envelope sender domain** (Return-Path), not the From header. For **SPF alignment** in DMARC, the Return-Path domain must match the From domain.

| Scenario | SPF Check | DMARC Alignment |
|----------|-----------|-----------------|
| Return-Path = yourdomain.com | Pass | Aligned |
| Return-Path = sendgrid.net | Pass (for SendGrid) | Not aligned |
| No SPF record on Return-Path domain | Fail | Fail |

## Configuring Return-Path in AcelleMail

Under **Settings → Sending Domains**, enable **Custom Return-Path** and set a subdomain like `bounce.yourdomain.com`. Add the required SPF record for that subdomain. This ensures DMARC SPF alignment and routes all bounces to AcelleMail's handler automatically.
MD
            ],

            // =========================================================
            // SERVER MANAGEMENT
            // =========================================================

            [
                'cat' => 'server-management',
                'title' => 'Redis for Queue Processing',
                'type' => 'tutorial',
                'diff' => 'intermediate',
                'excerpt' => 'Replace the default database queue driver with Redis for faster, more reliable campaign processing. Covers install, Laravel config, and queue monitoring.',
                'tags' => ['redis', 'queue-workers', 'laravel', 'ubuntu', 'acellemail'],
                'body' => <<<'MD'
## Why Redis for Queues

AcelleMail uses Laravel queues to process campaign sends asynchronously. The default `database` driver works but creates heavy load on MySQL. Redis is an in-memory data structure store that handles queue operations significantly faster with minimal I/O overhead.

## Install Redis on Ubuntu

```bash
sudo apt update && sudo apt install -y redis-server
sudo systemctl enable redis-server
sudo systemctl start redis-server
redis-cli ping   # should return PONG
```

## Configure Laravel to Use Redis

Edit `/home/vbrand/app/.env`:

```env
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_PASSWORD=null
```

Then clear config cache:

```bash
php artisan config:cache
```

## Run Queue Workers

```bash
php artisan queue:work redis --sleep=3 --tries=3 --daemon
```

Use Supervisor to keep workers running:

```ini
[program:acellemail-worker]
command=php /home/vbrand/app/artisan queue:work redis --sleep=3 --tries=3
autostart=true
autorestart=true
numprocs=4
user=vbrand
```

## Monitor Redis Queue Depth

```bash
redis-cli llen queues:default
redis-cli info stats | grep total_commands_processed
```

Install Laravel Horizon for a web-based queue dashboard — it natively supports Redis and gives real-time throughput metrics, failed job tracking, and worker management.
MD
            ],

            [
                'cat' => 'server-management',
                'title' => 'Automated Database Backups',
                'type' => 'tutorial',
                'diff' => 'beginner',
                'excerpt' => 'Set up a daily automated MySQL backup using a simple shell script and cron. Includes rotation to keep disk usage in check.',
                'tags' => ['mysql', 'ubuntu', 'performance'],
                'body' => <<<'MD'
## Why Automated Backups Matter

A corrupted database or accidental deletion can wipe out your entire subscriber list and campaign history. Automated daily backups take minutes to set up and can save hours of recovery work.

## The Backup Script

Create `/home/vbrand/scripts/backup-db.sh`:

```bash
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/home/vbrand/backups/db"
DB_NAME="acellemail"
DB_USER="vbrand"
DB_PASS="yourpassword"
KEEP_DAYS=14

mkdir -p "\$BACKUP_DIR"

mysqldump -u"\$DB_USER" -p"\$DB_PASS" "\$DB_NAME" \
  --single-transaction --quick --lock-tables=false \
  | gzip > "\$BACKUP_DIR/\$DB_NAME_\$DATE.sql.gz"

# Remove backups older than KEEP_DAYS
find "\$BACKUP_DIR" -name "*.sql.gz" -mtime +\$KEEP_DAYS -delete

echo "Backup complete: \$BACKUP_DIR/\$DB_NAME_\$DATE.sql.gz"
```

```bash
chmod +x /home/vbrand/scripts/backup-db.sh
```

## Schedule with Cron

```bash
crontab -e
```

Add:

```
0 2 * * * /home/vbrand/scripts/backup-db.sh >> /home/vbrand/logs/backup.log 2>&1
```

This runs at 2:00 AM daily.

## Offsite Backup

For safety, copy backups to an S3 bucket:

```bash
aws s3 sync /home/vbrand/backups/db s3://your-bucket/acellemail-backups/
```

## Restoring from Backup

```bash
gunzip < /home/vbrand/backups/db/acellemail_20260301_020000.sql.gz \
  | mysql -uvbrand -p acellemail
```
MD
            ],

            [
                'cat' => 'server-management',
                'title' => 'Scaling for 100K+ Emails Per Day',
                'type' => 'guide',
                'diff' => 'advanced',
                'excerpt' => 'Tune queue workers, MySQL, and PHP-FPM to handle high-volume campaign sends without degrading server stability or delivery speed.',
                'tags' => ['queue-workers', 'mysql', 'php', 'nginx', 'performance', 'redis', 'ubuntu'],
                'body' => <<<'MD'
## Baseline Requirements

At 100K emails/day you need roughly 70 emails/minute sustained. A single queue worker can handle 10–20 sends/minute depending on SMTP latency. Scale accordingly.

## Queue Worker Tuning

Increase Supervisor worker processes in `/etc/supervisor/conf.d/acellemail.conf`:

```ini
numprocs=8
```

Then:

```bash
sudo supervisorctl reread && sudo supervisorctl update
sudo supervisorctl restart all
```

Monitor queue depth:

```bash
watch -n 2 "redis-cli llen queues:default"
```

## MySQL Tuning

Edit `/etc/mysql/mysql.conf.d/mysqld.cnf`:

```ini
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
max_connections = 300
query_cache_type = 0
innodb_flush_log_at_trx_commit = 2
```

Restart MySQL: `sudo systemctl restart mysql`

## PHP-FPM Tuning

Edit `/etc/php/8.2/fpm/pool.d/www.conf`:

```ini
pm = dynamic
pm.max_children = 50
pm.start_servers = 10
pm.min_spare_servers = 5
pm.max_spare_servers = 20
pm.max_requests = 500
```

## Nginx Worker Connections

In `/etc/nginx/nginx.conf`:

```nginx
worker_processes auto;
events {
    worker_connections 2048;
}
```

## Quick Checklist

| Area | Setting | Value |
|------|---------|-------|
| Queue workers | numprocs | 8–16 |
| Queue driver | QUEUE_CONNECTION | redis |
| MySQL buffer pool | innodb_buffer_pool_size | 50% RAM |
| PHP-FPM max children | pm.max_children | 50 |
MD
            ],

            // =========================================================
            // EMAIL INFRASTRUCTURE
            // =========================================================

            [
                'cat' => 'email-infrastructure',
                'title' => 'How Email Delivery Actually Works',
                'type' => 'guide',
                'diff' => 'beginner',
                'excerpt' => 'Follow a single email from your "Send" button to the recipient\'s inbox — covering MTA handoff, SMTP conversation, DNS MX lookup, and TLS negotiation.',
                'tags' => ['smtp', 'tls', 'dkim', 'spf'],
                'body' => <<<'MD'
## The Journey of One Email

When you click **Send Campaign** in AcelleMail, a chain of events fires before the email reaches the inbox.

## Step 1: The Queue

AcelleMail adds each recipient to a Laravel queue. Queue workers pick up jobs and hand them to your configured SMTP server (or local MTA like Postfix).

## Step 2: DNS MX Lookup

Your MTA needs to find where to deliver the message. It queries DNS for the recipient domain's **MX records**:

```bash
dig MX gmail.com
# Returns: gmail-smtp-in.l.google.com (priority 5)
```

The MX record points to Google's receiving server.

## Step 3: The SMTP Conversation

Your server opens a TCP connection on port 25 (or 587/465) to Google's server:

```
→ EHLO mail.yourdomain.com
← 250 mx.google.com at your service
→ MAIL FROM: <bounce@yourdomain.com>
← 250 OK
→ RCPT TO: <user@gmail.com>
← 250 OK
→ DATA
→ [email headers + body]
→ .
← 250 Message accepted
→ QUIT
```

## Step 4: TLS Negotiation

Modern servers require **STARTTLS** or connect over port 465 (SMTPS). This encrypts the SMTP conversation, preventing eavesdropping in transit.

## Step 5: Authentication Checks

The receiving server runs three checks:

1. **SPF** — is the sending IP authorized by the From domain's SPF record?
2. **DKIM** — is the DKIM signature in the headers valid?
3. **DMARC** — do SPF and/or DKIM align with the From header domain?

All three must pass for the best inbox placement.
MD
            ],

            [
                'cat' => 'email-infrastructure',
                'title' => 'Dedicated vs Shared IP Address',
                'type' => 'guide',
                'diff' => 'beginner',
                'excerpt' => 'Choosing between a dedicated and shared IP affects your sender reputation and warmup requirements. This guide compares both options with clear recommendations.',
                'tags' => ['ip-rotation', 'warmup', 'sender-reputation', 'smtp'],
                'body' => <<<'MD'
## The Core Difference

Your **sending IP address** is how receiving mail servers identify you. Its reputation — built from your sending history — directly affects whether your emails land in the inbox or spam folder.

| | Dedicated IP | Shared IP |
|--|-------------|-----------|
| Reputation | Yours alone | Shared with other senders |
| Warmup required | Yes (4–8 weeks) | No |
| Cost | Higher | Lower or free |
| Control | Full | None |
| Risk | Low (if done right) | Medium (others can harm you) |
| Best for | 50K+ emails/month | Low-volume senders |

## When to Use a Dedicated IP

- You send **more than 50,000 emails per month** consistently.
- You need full control over your sending reputation.
- You have the time and volume for a proper IP warmup.

## When a Shared IP Is Fine

- You send fewer than 50K emails/month.
- You use a reputable ESP (Amazon SES, SendGrid) whose shared IP pools are well-managed.
- You are just starting out and do not have warmup volume.

## IP Warmup Summary

A new dedicated IP has no reputation. ISPs are suspicious of unknown IPs. To build trust:

- Week 1: Send to your **most engaged** subscribers only — 200–500/day.
- Week 2–3: Double volume every few days.
- Week 4–6: Reach your target volume gradually.

Monitor bounce rate, spam complaints, and Gmail Postmaster Tools during warmup. Any spike above **0.1% complaint rate** means slow down immediately.
MD
            ],

            [
                'cat' => 'email-infrastructure',
                'title' => 'Email Queue Architecture in AcelleMail',
                'type' => 'guide',
                'diff' => 'intermediate',
                'excerpt' => 'Understand how AcelleMail breaks campaigns into queue jobs, processes them with workers, handles retries, and tracks delivery status.',
                'tags' => ['queue-workers', 'redis', 'laravel', 'acellemail', 'performance'],
                'body' => <<<'MD'
## Overview

When you launch a campaign, AcelleMail does not send every email immediately. It uses a **queue-based architecture** to process sends reliably and at scale.

## The Campaign Send Flow

```
Campaign Launched
       ↓
[CampaignJob dispatched to queue]
       ↓
Queue Worker picks up job
       ↓
For each subscriber → [SendEmailJob dispatched]
       ↓
Worker sends via SMTP server
       ↓
Status updated: sent / failed / bounced
```

## Job Types

| Job Class | Purpose |
|-----------|---------|
| `CampaignJob` | Iterates subscribers, dispatches per-email jobs |
| `SendEmailJob` | Sends one email via configured SMTP server |
| `ProcessBounceJob` | Parses bounce inbox, updates subscriber status |
| `TrackOpenJob` | Records open event from tracking pixel request |

## Retry Logic

Failed jobs are retried automatically. You can configure in `.env`:

```env
QUEUE_FAILED_DRIVER=database
```

And in the job class:

```php
public int \$tries = 3;
public int \$backoff = 60; // seconds between retries
```

Failed jobs after max retries land in the `failed_jobs` table. Re-dispatch them with:

```bash
php artisan queue:retry all
```

## Monitoring Queue Health

```bash
# Check queue depth (Redis)
redis-cli llen queues:default

# Watch workers
supervisorctl status

# View failed jobs
php artisan queue:failed
```

A healthy queue should have zero failed jobs and a queue depth that drops steadily during a campaign send.
MD
            ],

            // =========================================================
            // INSTALLATION & SETUP
            // =========================================================

            [
                'cat' => 'installation-setup',
                'title' => 'Installing AcelleMail on DigitalOcean',
                'type' => 'tutorial',
                'diff' => 'beginner',
                'excerpt' => 'Step-by-step guide to provisioning a DigitalOcean Droplet and installing AcelleMail on a fresh Ubuntu 22.04 LEMP stack.',
                'tags' => ['ubuntu', 'nginx', 'mysql', 'php', 'lets-encrypt', 'acellemail'],
                'body' => <<<'MD'
## Prerequisites

- DigitalOcean account
- Domain name with DNS access
- AcelleMail license

## Step 1: Create the Droplet

In the DigitalOcean dashboard, create a new Droplet:

- **Image:** Ubuntu 22.04 LTS
- **Size:** 4 GB RAM / 2 vCPUs (minimum for production)
- **Region:** closest to your subscribers
- **Authentication:** SSH key (recommended)

## Step 2: Install the LEMP Stack

```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y nginx mysql-server php8.2-fpm \
  php8.2-mbstring php8.2-xml php8.2-curl php8.2-gd \
  php8.2-mysql php8.2-zip php8.2-bcmath php8.2-intl \
  unzip git supervisor redis-server
```

## Step 3: Secure MySQL

```bash
sudo mysql_secure_installation
sudo mysql -e "CREATE DATABASE acellemail; \
  CREATE USER 'acelle'@'localhost' IDENTIFIED BY 'strongpassword'; \
  GRANT ALL ON acellemail.* TO 'acelle'@'localhost'; FLUSH PRIVILEGES;"
```

## Step 4: Deploy AcelleMail

```bash
cd /var/www
sudo git clone https://github.com/acellemail/acellemail.git
cd acellemail
composer install --no-dev --optimize-autoloader
cp .env.example .env && php artisan key:generate
# Edit .env: DB credentials, APP_URL, MAIL settings
php artisan migrate --seed
php artisan storage:link
sudo chown -R www-data:www-data storage bootstrap/cache
```

## Step 5: SSL with Let's Encrypt

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d mail.yourdomain.com
```

Point your domain A record to the Droplet IP, then run the certbot command. SSL will auto-renew via cron.
MD
            ],

            [
                'cat' => 'installation-setup',
                'title' => 'Docker Deployment Guide for AcelleMail',
                'type' => 'tutorial',
                'diff' => 'advanced',
                'excerpt' => 'Run AcelleMail in Docker using docker-compose with separate containers for the app, queue workers, MySQL, Redis, and Nginx.',
                'tags' => ['php', 'mysql', 'redis', 'nginx', 'queue-workers', 'laravel', 'lets-encrypt'],
                'body' => <<<'MD'
## Overview

Running AcelleMail in Docker isolates dependencies, simplifies updates, and makes scaling individual components easier.

## docker-compose.yml

```yaml
version: "3.9"
services:
  app:
    build: .
    volumes:
      - .:/var/www/html
    depends_on: [mysql, redis]
    env_file: .env

  worker:
    build: .
    command: php artisan queue:work redis --sleep=3 --tries=3
    depends_on: [mysql, redis]
    env_file: .env
    deploy:
      replicas: 4

  nginx:
    image: nginx:alpine
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - .:/var/www/html
      - ./docker/nginx.conf:/etc/nginx/conf.d/default.conf
      - certbot-etc:/etc/letsencrypt
    depends_on: [app]

  mysql:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: acellemail
      MYSQL_USER: acelle
      MYSQL_PASSWORD: secret
      MYSQL_ROOT_PASSWORD: rootsecret
    volumes:
      - mysql-data:/var/lib/mysql

  redis:
    image: redis:alpine
    volumes:
      - redis-data:/data

volumes:
  mysql-data:
  redis-data:
  certbot-etc:
```

## Dockerfile (simplified)

```dockerfile
FROM php:8.2-fpm
RUN apt-get update && apt-get install -y \
    libzip-dev libpng-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql zip gd bcmath intl
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY . .
RUN composer install --no-dev --optimize-autoloader
```

## First Run

```bash
docker-compose up -d
docker-compose exec app php artisan migrate --seed
docker-compose exec app php artisan storage:link
docker-compose exec app php artisan config:cache
```
MD
            ],

        ];
    }
}
