<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendPaymentReminders extends Command
{
    protected $signature   = 'kos:payment-reminders';
    protected $description = 'Kirim pengingat pembayaran otomatis H-3, H-2, H-1, H-0, dan overdue via in-app & WhatsApp';

    public function handle(): int
    {
        $today = now()->toDateString();
        $notif = new NotificationService();
        $total = 0;

        $this->info('======================================================');
        $this->info('  Payment Reminder — ' . now()->format('d M Y'));
        $this->info('======================================================');
        $this->newLine();

        $overdueCount = Invoice::where('status', 'unpaid')
            ->whereDate('due_date', '<', $today)
            ->update(['status' => 'overdue']);

        if ($overdueCount > 0) {
            $this->warn("  {$overdueCount} invoice baru ditandai OVERDUE.");
            Log::info("[PaymentReminder] {$overdueCount} invoice ditandai overdue.", ['date' => $today]);
        }

        $this->newLine();

        $upcoming = [
            'H-3' => now()->addDays(3)->toDateString(),
            'H-2' => now()->addDays(2)->toDateString(),
            'H-1' => now()->addDay()->toDateString(),
        ];

        foreach ($upcoming as $stage => $date) {
            $invoices = Invoice::where('status', 'unpaid')
                ->whereDate('due_date', $date)
                ->with(['user', 'room'])
                ->get();

            $count = 0;
            foreach ($invoices as $inv) {
                if (!$inv->user) continue;

                $notif->notifyPaymentReminder($inv, $stage);

                $this->info("[{$stage}] {$inv->user->name} — {$inv->invoice_number} — " .
                    'Rp ' . number_format($inv->total_amount, 0, ',', '.'));

                Log::info("[PaymentReminder] [{$stage}] Reminder terkirim.", [
                    'invoice_id'     => $inv->id,
                    'invoice_number' => $inv->invoice_number,
                    'user_id'        => $inv->user_id,
                    'due_date'       => $inv->due_date,
                ]);

                $count++;
                $total++;
            }

            if ($count === 0) {
                $this->line("[{$stage}] Tidak ada invoice jatuh tempo pada {$date}.");
            } else {
                $this->line("[{$stage}] {$count} reminder terkirim.");
            }

            $this->newLine();
        }

        $this->line('── H-0 & Overdue ────────────────────────────────────');

        $h0Invoices = Invoice::whereIn('status', ['unpaid', 'overdue'])
            ->whereDate('due_date', '<=', $today)
            ->with(['user', 'room'])
            ->get();

        $h0Count   = 0;
        $skipCount = 0;

        foreach ($h0Invoices as $inv) {
            if (!$inv->user) continue;

            if (!empty($inv->last_reminded_at)
                && \Carbon\Carbon::parse($inv->last_reminded_at)->isToday()
            ) {
                $skipCount++;
                continue;
            }

            $notif->notifyPaymentReminder($inv, 'H-0');

            $inv->updateQuietly(['last_reminded_at' => now()]);

            $isOverdue = $inv->status === 'overdue';
            $tag       = $isOverdue ? 'H-0/OVERDUE' : 'H-0';

            $this->info("[{$tag}] {$inv->user->name} — {$inv->invoice_number} — " .
                'Rp ' . number_format($inv->total_amount, 0, ',', '.') .
                ($isOverdue ? ' (due: ' . \Carbon\Carbon::parse($inv->due_date)->format('d M Y') . ')' : ''));

            Log::info("[PaymentReminder] [{$tag}] Reminder terkirim.", [
                'invoice_id'     => $inv->id,
                'invoice_number' => $inv->invoice_number,
                'user_id'        => $inv->user_id,
                'due_date'       => $inv->due_date,
                'status'         => $inv->status,
            ]);

            $h0Count++;
            $total++;
        }

        if ($skipCount > 0) {
            $this->line("[H-0] {$skipCount} invoice di-skip (sudah diingatkan hari ini).");
        }

        if ($h0Count === 0 && $skipCount === 0) {
            $this->line('[H-0] Tidak ada invoice jatuh tempo atau overdue hari ini.');
        } else {
            $this->line("[H-0] {$h0Count} reminder terkirim.");
        }

        $this->newLine();
        $this->info('======================================================');
        $this->info(" Selesai. Total reminder terkirim: {$total}");
        $this->info('======================================================');

        Log::info('[PaymentReminder] Selesai.', [
            'total_sent' => $total,
            'skipped'    => $skipCount,
            'date'       => $today,
        ]);

        return 0;
    }
}
