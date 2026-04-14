<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Generate sitemap daily
        $schedule->command('sitemap:generate')->dailyAt('02:00');

        // You can add more scheduled tasks here
        // $schedule->command('queue:work')->everyMinute();
        // $schedule->command('backup:clean')->daily();
        $schedule->call(function () {
            Artisan::call('cache:forget', ['key' => 'sitemap_content']);
            // You can also call a command to regenerate, but the cache will rebuild on next request
        })->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
