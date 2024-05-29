<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected $commands = [
        Commands\TestCron::class,
        \App\Console\Commands\DispatchHoulrlyNewsNotification::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('send:custom-reminders')->everyMinute();
        $schedule->command('send:dispatch-houlrly-news-notification')->hourly();
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
