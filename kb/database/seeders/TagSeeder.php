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
            'spf',
            'dkim',
            'dmarc',
            'amazon-ses',
            'sendgrid',
            'mailgun',
            'smtp',
            'bounce-handling',
            'warmup',
            'email-templates',
            'drag-and-drop',
            'a-b-testing',
            'segmentation',
            'automation',
            'webhooks',
            'api',
            'laravel',
            'php',
            'mysql',
            'redis',
            'gdpr',
            'can-spam',
            'white-label',
            'multi-tenant',
            'stripe',
            'paypal',
            'woocommerce',
            'wordpress',
            'cron-jobs',
            'queue-workers',
        ];

        foreach ($tags as $slug) {
            Tag::create([
                'name' => Str::title(str_replace('-', ' ', $slug)),
                'slug' => $slug,
            ]);
        }
    }
}
