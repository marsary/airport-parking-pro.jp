<?php

use App\Console\Commands\SendReminderEmails;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();

// テストモード
// Schedule::command(SendReminderEmails::class, ['--test'])->hourly();
// 本番モード
Schedule::command(SendReminderEmails::class)->hourly();
