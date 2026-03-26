<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Email Marketing', 'slug' => 'email-marketing', 'icon' => '📧', 'color' => '#E8571A', 'description' => 'Campaign creation, templates, A/B testing, personalization', 'sort_order' => 1],
            ['name' => 'Automation', 'slug' => 'automation', 'icon' => '⚡', 'color' => '#6B3FA0', 'description' => 'Triggers, workflows, welcome series, drip campaigns', 'sort_order' => 2],
            ['name' => 'Sending & Deliverability', 'slug' => 'sending-deliverability', 'icon' => '🚀', 'color' => '#2563EB', 'description' => 'SMTP setup, bounce handling, sender reputation, warmup', 'sort_order' => 3],
            ['name' => 'List Management', 'slug' => 'list-management', 'icon' => '👥', 'color' => '#059669', 'description' => 'Segments, tags, import/export, verification', 'sort_order' => 4],
            ['name' => 'Analytics & Reporting', 'slug' => 'analytics-reporting', 'icon' => '📊', 'color' => '#D97706', 'description' => 'Open rates, click maps, campaign performance', 'sort_order' => 5],
            ['name' => 'Integrations', 'slug' => 'integrations', 'icon' => '🔗', 'color' => '#7C3AED', 'description' => 'Stripe, PayPal, WordPress, WooCommerce, API', 'sort_order' => 6],
            ['name' => 'Security & Compliance', 'slug' => 'security-compliance', 'icon' => '🔒', 'color' => '#DC2626', 'description' => 'GDPR, CAN-SPAM, SSL, encryption, data privacy', 'sort_order' => 7],
            ['name' => 'Installation & Setup', 'slug' => 'installation-setup', 'icon' => '🛠️', 'color' => '#0891B2', 'description' => 'Server requirements, deployment, configuration', 'sort_order' => 8],
            ['name' => 'SaaS & Multi-tenant', 'slug' => 'saas-multi-tenant', 'icon' => '🏢', 'color' => '#4338CA', 'description' => 'White-label, subscription plans, tenant management', 'sort_order' => 9],
            ['name' => 'Developer Guide', 'slug' => 'developer-guide', 'icon' => '💻', 'color' => '#241C15', 'description' => 'REST API, webhooks, customization, source code', 'sort_order' => 10],
            ['name' => 'DNS & Domain Setup', 'slug' => 'dns-domain-setup', 'icon' => '🌐', 'color' => '#0D9488', 'description' => 'SPF, DKIM, DMARC, domain verification', 'sort_order' => 11],
            ['name' => 'Server Management', 'slug' => 'server-management', 'icon' => '🖥️', 'color' => '#475569', 'description' => 'Cron jobs, queues, supervisor, Redis, performance', 'sort_order' => 12],
            ['name' => 'Troubleshooting', 'slug' => 'troubleshooting', 'icon' => '🔧', 'color' => '#B91C1C', 'description' => 'Common errors, debugging, logs', 'sort_order' => 13],
            ['name' => 'Best Practices', 'slug' => 'best-practices', 'icon' => '✨', 'color' => '#CA8A04', 'description' => 'Email design, subject lines, timing, warmup strategies', 'sort_order' => 14],
            ['name' => 'Migration & Comparison', 'slug' => 'migration-comparison', 'icon' => '🔄', 'color' => '#E8571A', 'description' => 'Switching from Mailchimp, cost comparison, data migration', 'sort_order' => 15],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
