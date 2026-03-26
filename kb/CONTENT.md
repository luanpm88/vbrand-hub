# AcelleMail Knowledge Base — Content Reference

> Comprehensive reference of all email marketing concepts, technical terms, and AcelleMail features.
> Used as source material for KB articles.

---

## 1. Email Campaign Management

### Core Concepts
- **Campaign**: A single email send to a list of subscribers
- **Broadcast**: One-time campaign sent immediately or scheduled
- **A/B Testing (Split Testing)**: Send 2+ variants to a subset, winner goes to the rest
- **Personalization**: Merge tags ({FIRST_NAME}, {COMPANY}), conditional content blocks
- **Dynamic Content**: Show different content based on subscriber attributes
- **Preheader Text**: Preview text shown after subject line in inbox
- **Email Preview**: Test rendering across clients (desktop, mobile, webmail)

### Email Builder
- Drag-and-drop visual editor
- Pre-built content blocks: text, image, button, divider, social, columns, video
- HTML code editor (advanced)
- Mobile-responsive by default
- Template library (100+ templates by category)
- Custom template upload (HTML/ZIP)
- Brand kit: saved colors, fonts, logo
- Real-time preview (desktop/tablet/mobile toggle)

### Campaign Types
- Regular (one-off broadcast)
- Automated (trigger-based, see Automation section)
- RSS-to-Email (auto-send new blog posts)
- Recurring (weekly digest, monthly newsletter)
- Transactional (order confirmations, password resets — API only)

---

## 2. Marketing Automation

### Trigger Types
| Trigger | Description |
|---------|-------------|
| Subscriber added | When contact joins a list |
| Email opened | When recipient opens a specific email |
| Link clicked | When recipient clicks a specific link |
| Date field | Birthday, anniversary, renewal date |
| Custom field change | When a subscriber field value changes |
| API trigger | External event via REST API webhook |
| Tag added/removed | When a tag is applied or removed |

### Workflow Actions
- **Send Email**: Deliver a specific email template
- **Wait/Delay**: Pause for minutes/hours/days/weeks
- **Condition/Split**: Branch based on subscriber data, engagement, tags
- **Add/Remove Tag**: Organize subscribers dynamically
- **Move to List**: Transfer subscriber between lists
- **Update Field**: Change subscriber custom field value
- **Webhook**: Call external URL with subscriber data
- **End Flow**: Terminate the automation for this subscriber

### Common Workflows
- Welcome Series (3-5 emails over 2 weeks)
- Abandoned Cart Recovery (1h → 24h → 72h reminders)
- Re-engagement (30/60/90 day inactive triggers)
- Birthday/Anniversary (date field trigger + offset)
- Lead Nurturing (score-based progressive content)
- Onboarding Drip (SaaS: signup → feature discovery → upgrade nudge)
- Win-back (inactive + special offer)
- Post-purchase Follow-up (review request, cross-sell)

### Best Practices
- Always include exit conditions (unsubscribe, goal reached)
- Limit automation frequency (don't overwhelm subscribers)
- Use tags to prevent duplicate automations
- Test automation with a test subscriber before activating
- Monitor automation metrics weekly

---

## 3. Sending Infrastructure & Deliverability

### Sending Services
| Service | Cost | Best For |
|---------|------|----------|
| Amazon SES | $0.10/1K emails | High volume, lowest cost |
| SendGrid | Free tier: 100/day | Analytics, webhooks |
| Mailgun | $0.80/1K emails | Developer-friendly API |
| SparkPost | $0.10/1K emails | Enterprise deliverability |
| Postmark | $1.25/1K emails | Transactional email |
| Elastic Email | $0.10/1K emails | Budget-friendly |
| Custom SMTP | $0 (your server) | Full control |

### Server Configuration in AcelleMail
```php
// config/mail.php (example for SES)
'mailers' => [
    'ses' => [
        'transport' => 'ses',
        'region' => 'us-east-1',
    ],
],
```

### Multi-Server Rotation
- Configure multiple sending servers in AcelleMail admin
- Automatic load balancing across servers
- Per-server sending limits and throttling
- Automatic failover if a server goes down
- Domain-level server assignment (different servers per sending domain)

### Bounce Handling
- **Hard Bounce**: Permanent failure (invalid address) → auto-unsubscribe after 1 occurrence
- **Soft Bounce**: Temporary failure (mailbox full, server down) → retry, unsubscribe after 5 occurrences
- **Complaint (FBL)**: Subscriber marks as spam → immediate unsubscribe + blacklist
- Bounce processing via webhook callbacks from sending services
- Automatic list cleaning based on bounce patterns

### Feedback Loops (FBL)
- ISP programs: AOL, Yahoo, Hotmail/Outlook, Comcast
- When subscriber clicks "Report Spam", ISP notifies sender
- AcelleMail auto-processes FBL reports → unsubscribes complainer
- Critical for maintaining sender reputation

### Sender Reputation
- **IP Reputation**: Score assigned to sending IP by ISPs
- **Domain Reputation**: Score for your sending domain (newer metric, more important)
- Factors: bounce rate, complaint rate, engagement rate, spam trap hits
- Monitor via: Google Postmaster Tools, Microsoft SNDS, Sender Score (validity.com)
- Reputation affects inbox placement rate directly

---

## 4. DNS & Domain Authentication

### SPF (Sender Policy Framework)
```dns
; TXT record on your sending domain
v=spf1 include:amazonses.com include:sendgrid.net ~all
```
- Tells receiving servers which IPs can send email for your domain
- Only one SPF record per domain (combine with `include:`)
- Use `~all` (softfail) or `-all` (hardfail)
- Check: `dig TXT yourdomain.com | grep spf`

### DKIM (DomainKeys Identified Mail)
```dns
; CNAME or TXT record
selector._domainkey.yourdomain.com  →  provided by your sending service
```
- Cryptographic signature proving email wasn't tampered with
- Each sending service provides its own DKIM selector/key
- Multiple DKIM records allowed (one per sending service)
- Check: `dig TXT selector._domainkey.yourdomain.com`

### DMARC (Domain-based Message Authentication)
```dns
; TXT record at _dmarc.yourdomain.com
v=DMARC1; p=quarantine; rua=mailto:dmarc@yourdomain.com; pct=100
```
- Policy telling ISPs what to do with unauthenticated email
- `p=none` (monitor only) → `p=quarantine` (spam folder) → `p=reject` (block)
- Start with `p=none` + reporting, gradually enforce
- Requires both SPF and DKIM to be set up first
- Check: `dig TXT _dmarc.yourdomain.com`

### BIMI (Brand Indicators for Message Identification)
```dns
; TXT record at default._bimi.yourdomain.com
v=BIMI1; l=https://yourdomain.com/logo.svg; a=https://yourdomain.com/vmc.pem
```
- Shows your brand logo in supported email clients (Gmail, Yahoo)
- Requires DMARC enforcement (p=quarantine or p=reject)
- SVG logo must meet specific requirements (square, specific format)
- VMC (Verified Mark Certificate) required for Gmail

### Return-Path / Envelope Sender
- The "bounce address" — where bounces are sent
- Different from the "From" address visible to recipients
- AcelleMail configures this per sending server
- Important for SPF alignment

### MX Records
```dns
; For receiving email (bounce processing)
yourdomain.com  MX  10  mail.yourdomain.com
```

---

## 5. IP & Domain Warmup

### What is Warmup?
New IPs and domains have no sending reputation. ISPs are suspicious of unknown senders. Warmup = gradually increasing send volume to build positive reputation.

### Warmup Schedule (New IP)
| Day | Daily Volume | Notes |
|-----|-------------|-------|
| 1-2 | 50 | Most engaged subscribers only |
| 3-4 | 100 | |
| 5-6 | 250 | |
| 7-8 | 500 | Monitor bounces |
| 9-10 | 1,000 | |
| 11-14 | 2,500 | |
| 15-21 | 5,000 | |
| 22-28 | 10,000 | Check reputation |
| 29-35 | 25,000 | |
| 36-42 | 50,000 | |
| 43+ | Full volume | Maintain consistency |

### Warmup Best Practices
- Send to most engaged subscribers first (recent openers/clickers)
- Maintain consistent daily volume (don't spike)
- Monitor bounce rate (keep under 2%)
- Monitor complaint rate (keep under 0.1%)
- Use dedicated IP if sending 100K+/month
- Shared IP for lower volumes (sending service handles warmup)
- Warm up each ISP separately if possible (Gmail, Yahoo, Outlook)

### Domain Warmup
- Similar to IP warmup but for new sending domains
- Even more important now that ISPs weight domain reputation heavily
- Takes 2-4 weeks minimum
- AcelleMail supports per-domain sending limits for warmup

---

## 6. List Management & Segmentation

### Import Methods
- CSV/Excel upload with field mapping
- Copy-paste from spreadsheet
- API import (programmatic)
- Signup form submissions
- WordPress/WooCommerce sync

### Subscriber Fields
- Standard: email, first_name, last_name, company, phone, address
- Custom fields: text, number, date, dropdown, checkbox, textarea
- System fields: subscription_date, last_opened, last_clicked, open_count, click_count

### Segmentation Operators
| Operator | Example |
|----------|---------|
| equals | country = "US" |
| not equals | status != "unsubscribed" |
| contains | email contains "@gmail" |
| starts with | first_name starts with "J" |
| greater than | open_count > 5 |
| before/after date | subscribed_at after "2024-01-01" |
| is empty / not empty | phone is not empty |
| has tag / missing tag | has tag "VIP" |

### List Hygiene
- Email verification before import (syntax, MX check, SMTP check)
- Remove role addresses (info@, admin@, support@)
- Remove disposable email domains (mailinator, guerrillamail)
- Identify and remove spam traps (pristine + recycled)
- Regular re-engagement campaigns for inactive subscribers
- Auto-unsubscribe after N soft bounces

### Double Opt-In
1. User submits signup form
2. AcelleMail sends confirmation email with verify link
3. User clicks link → confirmed subscriber
4. Benefits: cleaner list, GDPR compliance, fewer complaints

---

## 7. Analytics & Reporting

### Key Metrics
| Metric | Formula | Good Benchmark |
|--------|---------|----------------|
| Open Rate | Opens / Delivered × 100 | 20-30% |
| Click Rate (CTR) | Clicks / Delivered × 100 | 2-5% |
| Click-to-Open (CTOR) | Clicks / Opens × 100 | 10-15% |
| Bounce Rate | Bounces / Sent × 100 | < 2% |
| Complaint Rate | Complaints / Delivered × 100 | < 0.1% |
| Unsubscribe Rate | Unsubs / Delivered × 100 | < 0.5% |
| Conversion Rate | Conversions / Clicks × 100 | Varies |

### Tracking Methods
- Open tracking: invisible 1×1 pixel image (limited by Apple MPP)
- Click tracking: redirect links through tracking server
- UTM parameters for Google Analytics integration
- Conversion tracking via webhook or landing page pixel

### Apple Mail Privacy Protection (MPP)
- Since iOS 15 (Sep 2021), Apple pre-fetches all images
- Open rates inflated for Apple Mail users (~50% of all email)
- Mitigation: focus on click metrics, not just opens
- Use click-based segmentation for engagement scoring

### Reports Available in AcelleMail
- Campaign performance (per-campaign dashboard)
- Subscriber engagement (per-subscriber history)
- Geographic breakdown (country/city from IP)
- Device breakdown (desktop/mobile/tablet, email client)
- Click map (visual heatmap of link clicks)
- Export to CSV for external analysis
- Comparison reports (campaign A vs B)

---

## 8. Security & Compliance

### GDPR (EU General Data Protection Regulation)
- Explicit consent required before sending marketing emails
- Right to access: subscribers can request their data
- Right to erasure: "right to be forgotten" — delete all data on request
- Right to portability: export subscriber data in machine-readable format
- Data processing agreement (DPA) required with any third-party processors
- Privacy policy must describe data collection and usage
- AcelleMail: self-hosted = you are the data controller, no third-party sharing

### CAN-SPAM (US)
- Physical mailing address required in every email
- Clear "unsubscribe" link in every email
- Honor unsubscribe requests within 10 business days
- No deceptive subject lines
- Identify the message as an ad
- Penalties: up to $50,120 per violation

### CASL (Canada)
- Express consent or implied consent required
- Clear identification of sender
- Unsubscribe mechanism that works for 60 days
- Record of consent must be maintained
- Penalties: up to $10M per violation

### Self-Hosted Security Advantages
- Data stays on YOUR server — no third-party access
- No data sharing with platform vendors
- Full audit trail in your own logs
- Custom retention policies
- Encrypt database at rest (MySQL TDE)
- SSL/TLS for all connections
- Role-based access control
- IP whitelisting for admin panel
- Two-factor authentication (optional)

---

## 9. Technical Architecture (AcelleMail)

### Stack
- **Backend**: PHP 8.x + Laravel framework
- **Database**: MySQL 5.7+ / MariaDB
- **Cache**: Redis (optional, recommended)
- **Queue**: Laravel queue (database/Redis driver)
- **Scheduler**: Linux cron (runs Laravel scheduler every minute)
- **Worker**: Supervisor manages queue workers
- **Web Server**: Apache or Nginx

### Key Components
```
AcelleMail/
├── Campaigns        → Create, schedule, send email campaigns
├── Automations      → Trigger-based email workflows
├── Lists            → Subscriber management, segments
├── Templates        → Drag-and-drop email builder
├── Sending Servers  → SMTP/API sending configuration
├── Bounce Handlers  → Process bounces from sending services
├── Verification     → Email address verification service
├── Payments         → Stripe/PayPal billing (Extended only)
├── Plans            → SaaS subscription plan management
└── API              → REST API for external integration
```

### Server Requirements
| Requirement | Minimum | Recommended |
|-------------|---------|-------------|
| PHP | 8.1 | 8.3 |
| MySQL | 5.7 | 8.0 |
| RAM | 2 GB | 4 GB+ |
| Storage | 10 GB | 50 GB+ (for attachments) |
| OS | Ubuntu 20.04 | Ubuntu 22.04 |
| Web Server | Apache 2.4 | Nginx 1.24 |

### Cron Setup
```bash
# Add to crontab: crontab -e
* * * * * cd /path/to/acellemail && php artisan schedule:run >> /dev/null 2>&1
```

### Queue Worker (Supervisor)
```ini
[program:acellemail-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/acellemail/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/acellemail/storage/logs/worker.log
stopwaitsecs=3600
```

---

## 10. SaaS & Multi-Tenant (Extended License)

### Multi-Tenant Architecture
- Each "customer" gets their own account (sub-user)
- Isolated lists, campaigns, templates, automations
- Shared infrastructure (single database, shared queues)
- Admin can create subscription plans with feature/quota limits

### Subscription Plans
- Define sending quota (emails per month/day)
- Define subscriber limits
- Feature gates (automation on/off, A/B testing on/off)
- Template access levels
- Sending server assignments per plan
- Custom pricing (monthly/yearly/lifetime)

### Payment Processing
| Gateway | Supports | Notes |
|---------|----------|-------|
| Stripe | Cards, recurring | Most popular, global |
| PayPal | PayPal balance, cards | Wide adoption |
| Braintree | Cards, PayPal, Venmo | PayPal-owned |
| Paddle | Cards, PayPal | Merchant of record (handles VAT/tax) |

### White-Label Features
- Custom logo and branding throughout
- Custom domain (CNAME to your server)
- Custom email from addresses
- Remove "Powered by AcelleMail" (Extended license)
- Custom color scheme
- Custom login page

---

## 11. REST API

### Authentication
```bash
curl -H "Authorization: Bearer YOUR_API_TOKEN" \
     https://your-acellemail.com/api/v1/subscribers
```

### Key Endpoints
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /api/v1/lists | List all mailing lists |
| POST | /api/v1/subscribers | Add subscriber |
| GET | /api/v1/subscribers/{id} | Get subscriber details |
| PATCH | /api/v1/subscribers/{id} | Update subscriber |
| DELETE | /api/v1/subscribers/{id} | Delete subscriber |
| POST | /api/v1/campaigns | Create campaign |
| POST | /api/v1/campaigns/{id}/send | Send campaign |
| GET | /api/v1/campaigns/{id}/stats | Get campaign stats |

### Webhook Events
```json
{
    "event": "subscriber.added",
    "timestamp": "2026-03-26T10:00:00Z",
    "data": {
        "subscriber_id": 12345,
        "email": "user@example.com",
        "list_id": 1,
        "source": "api"
    }
}
```

Available events: subscriber.added, subscriber.removed, email.sent, email.opened, email.clicked, email.bounced, email.complained, campaign.started, campaign.completed

---

## 12. Open Source & Community

### Why Self-Hosted?
- **Cost**: One-time $64-$199 vs $500+/month SaaS
- **Privacy**: Your data, your server, no third-party access
- **Control**: Modify source code, add custom features
- **No Limits**: Unlimited subscribers, unlimited sends
- **No Vendor Lock-in**: Export data anytime, switch servers anytime
- **Transparency**: Full source code, no hidden behaviors

### Community Resources
- CodeCanyon comments/support
- Community forum (forum.acellemail.com)
- Documentation (built-in + online)
- Video tutorials
- API documentation
- GitHub issues for bug reports

### Contributing & Customization
- Full PHP/Laravel source code (unencrypted)
- Standard Laravel project structure
- Custom plugins/extensions possible
- Database schema extensible
- Blade templates customizable
- CSS/JS fully editable
- API extensible with custom endpoints

---

## 13. Email Design Best Practices

### Subject Lines
- Keep under 50 characters (mobile truncation)
- Use personalization: "{FIRST_NAME}, check this out"
- Create urgency without being spammy
- A/B test subject lines consistently
- Avoid ALL CAPS and excessive punctuation!!!
- Emojis: use sparingly, test rendering across clients

### Email Body
- Single column layout (mobile-friendly)
- Max width: 600px
- Above the fold: clear value proposition + CTA
- Images: use alt text, don't rely on images-only
- CTA buttons: 44x44px minimum tap target, contrasting color
- Text-to-image ratio: at least 60% text
- Preview text: customize (don't let it auto-pull)
- Footer: unsubscribe link, physical address, preference center

### Deliverability Tips
- Clean your list regularly (remove bounces, inactives)
- Authenticate your domain (SPF, DKIM, DMARC)
- Warm up new IPs/domains gradually
- Monitor sender reputation
- Avoid spam trigger words in subject lines
- Maintain consistent sending schedule
- Segment and personalize (better engagement = better reputation)
- Process complaints immediately
- Use double opt-in for new subscribers
- Include a plain-text version

### Send Timing
- B2B: Tuesday-Thursday, 10am-2pm recipient timezone
- B2C: Evenings and weekends often perform well
- E-commerce: Sunday evening, Friday morning
- Test YOUR audience — benchmarks vary widely
- AcelleMail: use timezone-aware scheduling

---

## Glossary

| Term | Definition |
|------|-----------|
| MTA | Mail Transfer Agent — server software that sends/receives email |
| MX Record | DNS record pointing to mail server |
| PTR Record | Reverse DNS — IP maps back to hostname |
| Envelope Sender | Technical "from" address (Return-Path) |
| Header From | Display "from" address visible to recipient |
| Relay | Forwarding email through an intermediate server |
| Throttling | Limiting send speed to avoid ISP blocks |
| Blacklist | List of IPs/domains blocked by ISPs (e.g., Spamhaus) |
| Whitelist | Pre-approved sender list (contact-level) |
| Spam Trap | Email address used to catch spammers |
| Pristine Trap | Never-used address — hitting it = buying lists |
| Recycled Trap | Old abandoned address repurposed as trap |
| FBL | Feedback Loop — ISP notifies you of spam complaints |
| ESP | Email Service Provider (e.g., Mailchimp, SendGrid) |
| MBP | Mailbox Provider (e.g., Gmail, Yahoo, Outlook) |
| SMTP | Simple Mail Transfer Protocol — the protocol for sending email |
| IMAP/POP3 | Protocols for receiving/reading email |
| TLS | Transport Layer Security — encrypts email in transit |
| STARTTLS | Upgrading a plain connection to encrypted |
| ARC | Authenticated Received Chain — preserves auth through forwarding |
| List-Unsubscribe | Email header allowing one-click unsubscribe in clients |
