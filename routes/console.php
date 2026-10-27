<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Welcome-sequence follow-ups (day 3 and day 7). Requires the Laravel scheduler to be running (php artisan schedule:run every minute).
Illuminate\Support\Facades\Schedule::command('newsletter:sequence')->dailyAt('09:00')->timezone('Africa/Nairobi');
