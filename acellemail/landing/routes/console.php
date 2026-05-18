<?php

declare(strict_types=1);

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduler
|--------------------------------------------------------------------------
| Cron `* * * * * php artisan schedule:run` runs this on prod. The
| ugc:sweep command persists a daily JSON report under
| storage/app/ugc-sweep/ and emails the digest to config('ugc.admin_emails')
| only when findings > 0.
*/
Schedule::command('ugc:sweep --email-if-findings')
    ->dailyAt('09:00')
    ->timezone('Asia/Ho_Chi_Minh')
    ->withoutOverlapping()
    ->onOneServer()
    ->runInBackground()
    ->description('Daily UGC abuse sweep + admin digest email');
