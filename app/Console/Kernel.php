<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Send progress reminders daily at 9:00 AM
        $schedule->command('notifications:send-reminders')
                 ->dailyAt('09:00')
                 ->description('Send daily progress reminders');
                 
        // Clean old notifications (older than 30 days)
        $schedule->call(function () {
            \App\Models\Notification::where('created_at', '<', now()->subDays(30))->delete();
        })->weekly()->description('Clean old notifications');
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