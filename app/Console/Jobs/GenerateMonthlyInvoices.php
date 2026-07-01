<?php

namespace App\Console\Jobs;

use App\Models\Booking;
use App\Models\Invoice;
use App\Models\InAppNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GenerateMonthlyInvoices implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries   = 3;
    public int $timeout = 120;

    public function handle(): void
    {
        Log::info('[GenerateMonthlyInvoices] Job mulai: ' . now()->toDateTimeString());

        $generated = 0;
        $skipped   = 0;

        $bookings = Booking::with(['room', 'user'])
            ->where('payment_method', 'monthly')
            ->whereIn('status', ['active'])
            ->whereNotNull('check_in_date')
            ->whereNotNull('monthly_billing_day')
            ->get();

        foreach ($bookings as $booking) {
            try {
                $result = $this->processBooking($booking);
                if ($result) $generated++;
                else $skipped++;
            } catch (\Throwable $e) {
                Log::error('[GenerateMonthlyInvoices] Error booking #' . $booking->id . ': ' . $e->getMessage(), [
                    'booking_id' => $booking->id,
                    'trace'      => $e->getTraceAsString(),
                ]);
            }
        }

        $renewalBookings = Booking::with(['room', 'user'])
            ->whereNotNull('renewal_id')
            ->whereNotNull('renewal_duration')
            ->whereNotNull('renewal_period_start')
            ->where('renewal_payment_method', 'monthly')
            ->whereRaw('renewal_monthly_count < renewal_duration')
            ->whereIn('status', ['approved', 'active'])
            ->get();

        foreach ($renewalBookings as $booking) {
            try {
                $result = $this->processRenewalMonthly($booking);
                if ($result) $generated++;
                else $skipped++;
            } catch (\Throwable $e) {
                Log::error('[GenerateMonthlyInvoices] Error renewal booking #' . $booking->id . ': ' . $e->getMessage(), [
                    'booking_id' => $booking->id,
                    'trace'      => $e->getTraceAsString(),
                ]);
            }
        }

        Log::info('[GenerateMonthlyInvoices] Selesai. Generated: ' . $generated . ', Skipped: ' . $skipped);
    }

    private function processBooking(Booking $booking): bool
    {
        $invoiceCount    = $booking->monthly_invoice_count ?? 0;
        $checkIn         = Carbon::parse($booking->check_in_date);
        $nextMonthNumber = $invoiceCount + 1;
        $periodStart     = $checkIn->copy()->addMonths($nextMonthNumber - 1)->startOfDay();

        if ($booking->renewal_id) {
            $renewalPaymentMethod = $booking->renewal_payment_method ?? '';

            if ($booking->renewal_period_start) {
                $renewalStart = Carbon::parse($booking->renewal_period_start)->startOfDay();

                if ($periodStart->gte($renewalStart)) {
                    Log::info('[GenerateMonthlyInvoices] Booking #' . $booking->id .
                        ' bulan ke-' . $nextMonthNumber .
                        ' (periode ' . $periodStart->toDateString() . ')' .
                        ' >= renewal_period_start ' . $renewalStart->toDateString() .
                        ' [renewal_payment_method=' . ($renewalPaymentMethod ?: 'null') . ']' .
                        ' — stop invoice reguler.');
                    return false;
                }
            } else {
                if ($renewalPaymentMethod !== 'monthly') {
                    Log::info('[GenerateMonthlyInvoices] Booking #' . $booking->id .
                        ' memiliki renewal_id=' . $booking->renewal_id .
                        ' [renewal_payment_method=' . ($renewalPaymentMethod ?: 'null') . ']' .
                        ' renewal_period_start null — stop invoice reguler (renewal fullpay).');
                    return false;
                }
            }
        }

        $alreadyExists = Invoice::where('booking_id', $booking->id)
            ->where('invoice_type', 'monthly')
            ->where('month_number', $nextMonthNumber)
            ->exists();

        if ($alreadyExists) {
            return false;
        }

        $createThreshold = $periodStart->copy()->subDays(5);
        if (now()->lt($createThreshold)) {
            return false;
        }

        $periodEnd     = $periodStart->copy()->addMonth()->subDay();
        $dueDate       = $periodStart->copy();
        $baseAmount    = $booking->room?->price ?? 0;
        $serviceAmount = (float) ($booking->service_amount ?? 0);
        $totalAmount   = $baseAmount + $serviceAmount;
        $durationLabel = $booking->duration_months ?? '?';

        DB::transaction(function () use (
            $booking, $nextMonthNumber, $durationLabel,
            $baseAmount, $serviceAmount, $totalAmount,
            $periodStart, $periodEnd, $dueDate
        ) {
            $invoiceNumber = 'INV-' . strtoupper(now()->format('Ymd')) . '-' . strtoupper(Str::random(6));

            $invoice = Invoice::create([
                'user_id'        => $booking->user_id,
                'room_id'        => $booking->room_id,
                'booking_id'     => $booking->id,
                'invoice_number' => $invoiceNumber,
                'invoice_type'   => 'monthly',
                'month_number'   => $nextMonthNumber,
                'base_amount'    => $baseAmount,
                'service_amount' => $serviceAmount,
                'total_amount'   => $totalAmount,
                'period_start'   => $periodStart->toDateString(),
                'period_end'     => $periodEnd->toDateString(),
                'due_date'       => $dueDate->toDateString(),
                'status'         => 'unpaid',
                'notes'          => 'Tagihan sewa bulanan kamar ' . ($booking->room?->room_number ?? '-') .
                                    ' — bulan ke-' . $nextMonthNumber . ' dari ' . $durationLabel . ' bulan.' .
                                    ' Periode: ' . $periodStart->format('d M Y') . ' s/d ' . $periodEnd->format('d M Y') . '.',
            ]);

            $booking->increment('monthly_invoice_count');

            Log::info('[GenerateMonthlyInvoices] Invoice dibuat: ' . $invoiceNumber .
                ' (booking #' . $booking->id . ', bulan ke-' . $nextMonthNumber . ')');

            $this->sendInvoiceNotification($booking, $invoice, $nextMonthNumber, $dueDate);
        });

        return true;
    }

    private function processRenewalMonthly(Booking $booking): bool
    {
        if (($booking->renewal_payment_method ?? '') !== 'monthly') {
            Log::warning('[GenerateMonthlyInvoices] processRenewalMonthly dipanggil untuk booking #' .
                $booking->id . ' dengan renewal_payment_method=' .
                ($booking->renewal_payment_method ?? 'null') . ' — skip.');
            return false;
        }

        $renewal = \App\Models\Renewal::find($booking->renewal_id);
        if (!$renewal) return false;

        $nextMonthNumber = ($booking->renewal_monthly_count ?? 0) + 1;
        $totalMonths     = (int) $booking->renewal_duration;

        if ($nextMonthNumber > $totalMonths) return false;

        $alreadyExists = Invoice::where('renewal_id', $booking->renewal_id)
            ->where('invoice_type', 'renewal_monthly')
            ->where('month_number', $nextMonthNumber)
            ->exists();

        if ($alreadyExists) return false;

        $periodStart = Carbon::parse($booking->renewal_period_start)
            ->addMonths($nextMonthNumber - 1);

        $createThreshold = $periodStart->copy()->subDays(5);
        if (now()->lt($createThreshold)) return false;

        $periodEnd = $periodStart->copy()->addMonth()->subDay();
        $dueDate   = $periodStart->copy()->subDays(5);

        $roomPrice  = $booking->room?->price ?? 0;
        $svcMonthly = 0;

        if (!empty($renewal->additional_services)) {
            foreach ($renewal->additional_services as $svc) {
                $svcMonthly += (int) ($svc['price'] ?? 0);
            }
        }

        if ($svcMonthly === 0) {
            $svcMonthly = (int) ($booking->renewal_service_monthly ?? 0);
        }

        $monthlyTotal = $roomPrice + $svcMonthly;

        DB::transaction(function () use (
            $booking, $renewal, $nextMonthNumber, $totalMonths,
            $roomPrice, $svcMonthly, $monthlyTotal,
            $periodStart, $periodEnd, $dueDate
        ) {
            $invoiceNumber = 'INV-RNW-' . now()->format('Ymd') . '-' .
                str_pad($renewal->id, 4, '0', STR_PAD_LEFT) .
                '-M' . $nextMonthNumber;

            $invoice = Invoice::create([
                'user_id'        => $booking->user_id,
                'room_id'        => $booking->room_id,
                'renewal_id'     => $renewal->id,
                'invoice_number' => $invoiceNumber,
                'invoice_type'   => 'renewal_monthly',
                'month_number'   => $nextMonthNumber,
                'base_amount'    => $roomPrice,
                'service_amount' => $svcMonthly,
                'total_amount'   => $monthlyTotal,
                'tax_amount'     => 0,
                'status'         => 'unpaid',
                'due_date'       => $dueDate,
                'period_start'   => $periodStart->toDateString(),
                'period_end'     => $periodEnd->toDateString(),
                'notes'          => 'Perpanjangan sewa kamar ' . ($booking->room?->room_number ?? '-') .
                                    ' — bulan ke-' . $nextMonthNumber . ' dari ' . $totalMonths . ' bulan.' .
                                    ' Periode: ' . $periodStart->format('d M Y') . ' s/d ' . $periodEnd->format('d M Y') . '.',
            ]);

            $booking->increment('renewal_monthly_count');
            $renewal->increment('monthly_invoice_count');

            Log::info('[GenerateMonthlyInvoices] Renewal invoice dibuat: ' . $invoiceNumber .
                ' (renewal #' . $renewal->id .
                ', booking_payment_method=' . ($booking->payment_method ?? '-') .
                ', bulan ke-' . $nextMonthNumber .
                ', svc=Rp' . number_format($svcMonthly, 0, ',', '.') . ')');

            $this->sendRenewalInvoiceNotification($booking, $renewal, $invoice, $nextMonthNumber, $totalMonths, $dueDate, $monthlyTotal);
        });

        return true;
    }

    private function sendInvoiceNotification(Booking $booking, Invoice $invoice, int $monthNumber, Carbon $dueDate): void
    {
        $roomNumber = $booking->room?->room_number ?? '-';
        $totalFmt   = 'Rp ' . number_format($invoice->total_amount, 0, ',', '.');
        $dueFmt     = $dueDate->format('d M Y');
        $userName   = $booking->user?->name ?? 'Penghuni';

        $roleName  = $booking->user?->role?->name ?? 'tenant';
        $showRoute = $roleName === 'tenant'
            ? route('tenant.invoices.show', $invoice->id)
            : route('prospective.invoices.show', $invoice->id);

        InAppNotification::send(
            $booking->user_id,
            'monthly_invoice_created',
            " Tagihan Bulanan Bulan ke-{$monthNumber} — {$roomNumber}",
            "Tagihan sewa bulan ke-{$monthNumber} sebesar {$totalFmt} telah diterbitkan. Jatuh tempo: {$dueFmt}.",
            $showRoute,
            ['invoice_id' => $invoice->id, 'month_number' => $monthNumber]
        );

        if ($booking->user?->phone) {
            try {
                $wa    = new \App\Services\WhatsAppService();
                $waMsg = "Halo *{$userName}*,\n\n"
                    . " *Tagihan Sewa Bulanan*\n\n"
                    . "━━━━━━━━━━━━━━━━\n"
                    . " Kamar        : *{$roomNumber}*\n"
                    . " Bulan ke     : *{$monthNumber}*\n"
                    . " Nominal      : *{$totalFmt}*\n"
                    . " Jatuh Tempo  : *{$dueFmt}*\n"
                    . "━━━━━━━━━━━━━━━━\n\n"
                    . "Silakan lakukan pembayaran sebelum jatuh tempo melalui menu Tagihan di aplikasi.\n\n"
                    . "Terima kasih ";
                if (method_exists($wa, 'send')) {
                    $wa->send($booking->user->phone, $waMsg);
                }
            } catch (\Throwable $e) {
                Log::warning('[GenerateMonthlyInvoices] WA gagal: ' . $e->getMessage());
            }
        }

        try {
            $admins = \App\Models\User::whereHas('role', fn($q) => $q->where('name', 'admin'))
                ->where('is_active', true)->get();
            foreach ($admins as $admin) {
                InAppNotification::send(
                    $admin->id,
                    'monthly_invoice_auto_created',
                    " Invoice Bulanan Otomatis — {$roomNumber} Bulan ke-{$monthNumber}",
                    "Invoice {$invoice->invoice_number} untuk {$userName} (kamar {$roomNumber}) bulan ke-{$monthNumber} senilai {$totalFmt} dibuat otomatis. Jatuh tempo: {$dueFmt}.",
                    route('admin.invoices.show', $invoice->id),
                    ['invoice_id' => $invoice->id]
                );
            }
        } catch (\Throwable $e) {
            Log::warning('[GenerateMonthlyInvoices] Admin notif gagal: ' . $e->getMessage());
        }
    }

    private function sendRenewalInvoiceNotification(
        Booking $booking,
        \App\Models\Renewal $renewal,
        Invoice $invoice,
        int $monthNumber,
        int $totalMonths,
        Carbon $dueDate,
        float $monthlyTotal
    ): void {
        $roomNumber = $booking->room?->room_number ?? '-';
        $totalFmt   = 'Rp ' . number_format($monthlyTotal, 0, ',', '.');
        $dueFmt     = $dueDate->format('d M Y');
        $userName   = $booking->user?->name ?? 'Penghuni';

        InAppNotification::send(
            $booking->user_id,
            'renewal_monthly_invoice_created',
            " Tagihan Perpanjangan Bulan ke-{$monthNumber} — Kamar {$roomNumber}",
            "Tagihan perpanjangan bulan ke-{$monthNumber} dari {$totalMonths} sebesar {$totalFmt} telah diterbitkan. Jatuh tempo: {$dueFmt}.",
            route('tenant.renewal.invoice', $renewal->id),
            ['renewal_id' => $renewal->id, 'month_number' => $monthNumber]
        );

        if ($booking->user?->phone) {
            try {
                $wa    = new \App\Services\WhatsAppService();
                $waMsg = "Halo *{$userName}*,\n\n"
                    . " *Tagihan Perpanjangan Sewa*\n\n"
                    . "━━━━━━━━━━━━━━━━\n"
                    . " Kamar        : *{$roomNumber}*\n"
                    . " Bulan ke     : *{$monthNumber}*\n"
                    . " Nominal      : *{$totalFmt}*\n"
                    . " Jatuh Tempo  : *{$dueFmt}*\n"
                    . "━━━━━━━━━━━━━━━━\n\n"
                    . "Silakan lakukan pembayaran sebelum jatuh tempo melalui menu Tagihan di aplikasi.\n\n"
                    . "Terima kasih ";
                if (method_exists($wa, 'send')) {
                    $wa->send($booking->user->phone, $waMsg);
                }
            } catch (\Throwable $e) {
                Log::warning('[GenerateMonthlyInvoices] WA renewal gagal: ' . $e->getMessage());
            }
        }

        try {
            $admins = \App\Models\User::whereHas('role', fn($q) => $q->where('name', 'admin'))
                ->where('is_active', true)->get();
            foreach ($admins as $admin) {
                InAppNotification::send(
                    $admin->id,
                    'renewal_monthly_invoice_auto_created',
                    " Invoice Perpanjangan Otomatis — {$roomNumber} Bulan ke-{$monthNumber}",
                    "Invoice perpanjangan {$invoice->invoice_number} untuk {$userName} (kamar {$roomNumber}) bulan ke-{$monthNumber} dari {$totalMonths} senilai {$totalFmt} dibuat otomatis. Jatuh tempo: {$dueFmt}.",
                    route('admin.invoices.show', $invoice->id),
                    ['invoice_id' => $invoice->id, 'renewal_id' => $renewal->id]
                );
            }
        } catch (\Throwable $e) {
            Log::warning('[GenerateMonthlyInvoices] Admin notif renewal gagal: ' . $e->getMessage());
        }
    }
}
