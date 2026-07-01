<?php

namespace App\Console;

use App\Console\Jobs\GenerateMonthlyInvoices;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('tenancy:check-expiring')
            ->dailyAt('08:00')
            ->timezone('Asia/Jakarta');
        
        $schedule->command('kos:payment-reminders')
            ->dailyAt('08:00')
            ->timezone('Asia/Jakarta')
            ->withoutOverlapping();

        $schedule->job(new GenerateMonthlyInvoices)
            ->dailyAt('07:00')
            ->timezone('Asia/Jakarta')
            ->withoutOverlapping()
            ->onFailure(function () {
                \Illuminate\Support\Facades\Log::error('[Scheduler] GenerateMonthlyInvoices GAGAL.');
            });
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
