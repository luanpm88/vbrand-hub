<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            // Authentication
            'spf', 'dkim', 'dmarc', 'bimi', 'arc',
            // Sending services
            'amazon-ses', 'sendgrid', 'mailgun', 'sparkpost', 'postmark', 'elastic-email',
            // Protocols
            'smtp', 'imap', 'tls', 'starttls',
            // Deliverability
            'bounce-handling', 'warmup', 'sender-reputation', 'spam-filter', 'blacklist', 'feedback-loop',
            'inbox-placement', 'throttling', 'ip-rotation',
            // Content
            'email-templates', 'drag-and-drop', 'html-email', 'responsive-design', 'dark-mode',
            'subject-lines', 'preheader', 'cta-buttons', 'personalization', 'dynamic-content',
            // Testing
            'a-b-testing', 'split-testing', 'email-preview', 'spam-score',
            // List
            'segmentation', 'double-opt-in', 'list-cleaning', 'email-verification', 'import-export',
            'subscriber-tags', 'custom-fields',
            // Automation
            'automation', 'welcome-series', 'drip-campaign', 'triggers', 'workflows',
            'abandoned-cart', 're-engagement',
            // Integrations
            'webhooks', 'api', 'rest-api', 'zapier',
            // Tech stack
            'laravel', 'php', 'mysql', 'redis', 'nginx', 'apache', 'supervisor', 'composer',
            'ubuntu', 'ssl-certificate', 'lets-encrypt',
            // Compliance
            'gdpr', 'can-spam', 'casl', 'data-privacy', 'consent',
            // Business
            'white-label', 'multi-tenant', 'stripe', 'paypal', 'paddle', 'braintree',
            'subscription-billing', 'pricing-plans',
            // Platforms
            'woocommerce', 'wordpress', 'shopify',
            // Operations
            'cron-jobs', 'queue-workers', 'performance', 'monitoring', 'backup',
            // Content strategy
            'open-rates', 'click-rates', 'conversion', 'roi', 'analytics',
            // AcelleMail specific
            'acellemail', 'self-hosted', 'open-source', 'codecanyon',
        ];

        foreach ($tags as $slug) {
            Tag::create([
                'name' => Str::title(str_replace('-', ' ', $slug)),
                'slug' => $slug,
            ]);
        }
    }
}
