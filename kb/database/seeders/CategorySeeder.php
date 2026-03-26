<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Getting Started (4)
            ['name' => 'Email Marketing', 'slug' => 'email-marketing', 'icon' => 'mail', 'color' => '#E8571A', 'group' => 'getting-started', 'description' => 'Campaign creation, templates, A/B testing, personalization, scheduling', 'sort_order' => 1],
            ['name' => 'Automation', 'slug' => 'automation', 'icon' => 'zap', 'color' => '#6B3FA0', 'group' => 'getting-started', 'description' => 'Triggers, workflows, welcome series, drip campaigns, behavioral flows', 'sort_order' => 2],
            ['name' => 'List Management', 'slug' => 'list-management', 'icon' => 'users', 'color' => '#059669', 'group' => 'getting-started', 'description' => 'Segments, tags, import/export, double opt-in, verification, hygiene', 'sort_order' => 3],
            ['name' => 'Analytics & Reporting', 'slug' => 'analytics-reporting', 'icon' => 'bar-chart', 'color' => '#D97706', 'group' => 'getting-started', 'description' => 'Open rates, click maps, campaign performance, device reports, exports', 'sort_order' => 4],

            // Infrastructure (5)
            ['name' => 'Sending & Deliverability', 'slug' => 'sending-deliverability', 'icon' => 'send', 'color' => '#2563EB', 'group' => 'infrastructure', 'description' => 'SMTP setup, bounce handling, sender reputation, IP warmup, throttling', 'sort_order' => 5],
            ['name' => 'DNS & Domain Setup', 'slug' => 'dns-domain-setup', 'icon' => 'globe', 'color' => '#0D9488', 'group' => 'infrastructure', 'description' => 'SPF, DKIM, DMARC, BIMI, domain verification, MX records, Return-Path', 'sort_order' => 6],
            ['name' => 'Server Management', 'slug' => 'server-management', 'icon' => 'server', 'color' => '#475569', 'group' => 'infrastructure', 'description' => 'Cron jobs, queues, Supervisor, Redis, Nginx, performance tuning', 'sort_order' => 7],
            ['name' => 'Installation & Setup', 'slug' => 'installation-setup', 'icon' => 'tool', 'color' => '#0891B2', 'group' => 'infrastructure', 'description' => 'Server requirements, deployment, Nginx/Apache, PHP config, SSL', 'sort_order' => 8],
            ['name' => 'Email Infrastructure', 'slug' => 'email-infrastructure', 'icon' => 'layers', 'color' => '#334155', 'group' => 'infrastructure', 'description' => 'Mail servers, MTA configuration, relay setup, IP management, monitoring', 'sort_order' => 9],

            // Advanced (5)
            ['name' => 'Integrations', 'slug' => 'integrations', 'icon' => 'link', 'color' => '#7C3AED', 'group' => 'advanced', 'description' => 'Stripe, PayPal, WordPress, WooCommerce, Zapier, webhooks, API', 'sort_order' => 10],
            ['name' => 'Developer Guide', 'slug' => 'developer-guide', 'icon' => 'code', 'color' => '#241C15', 'group' => 'advanced', 'description' => 'REST API, webhooks, customization, extending source code, plugins', 'sort_order' => 11],
            ['name' => 'SaaS & Multi-tenant', 'slug' => 'saas-multi-tenant', 'icon' => 'layers', 'color' => '#4338CA', 'group' => 'advanced', 'description' => 'White-label, subscription plans, billing, tenant management, branding', 'sort_order' => 12],
            ['name' => 'Security & Compliance', 'slug' => 'security-compliance', 'icon' => 'shield', 'color' => '#DC2626', 'group' => 'advanced', 'description' => 'GDPR, CAN-SPAM, CASL, SSL/TLS, encryption, data privacy, audit', 'sort_order' => 13],
            ['name' => 'Email Design & Content', 'slug' => 'email-design', 'icon' => 'layout', 'color' => '#BE185D', 'group' => 'advanced', 'description' => 'HTML email, responsive design, dark mode, accessibility, content strategy', 'sort_order' => 14],

            // Resources (4)
            ['name' => 'Best Practices', 'slug' => 'best-practices', 'icon' => 'star', 'color' => '#CA8A04', 'group' => 'resources', 'description' => 'Subject lines, send timing, warmup strategies, engagement optimization', 'sort_order' => 15],
            ['name' => 'Troubleshooting', 'slug' => 'troubleshooting', 'icon' => 'alert-circle', 'color' => '#B91C1C', 'group' => 'resources', 'description' => 'Common errors, debugging, delivery issues, logs, performance', 'sort_order' => 16],
            ['name' => 'Migration & Comparison', 'slug' => 'migration-comparison', 'icon' => 'refresh-cw', 'color' => '#E8571A', 'group' => 'resources', 'description' => 'Switching from Mailchimp/Sendinblue, cost comparison, data migration', 'sort_order' => 17],
            ['name' => 'AcelleMail Updates', 'slug' => 'acellemail-updates', 'icon' => 'bell', 'color' => '#F59E0B', 'group' => 'resources', 'description' => 'Release notes, changelogs, new features, roadmap, community news', 'sort_order' => 18],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
