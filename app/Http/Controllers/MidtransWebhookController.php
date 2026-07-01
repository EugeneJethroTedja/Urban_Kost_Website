<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Renewal;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload     = $request->all();
        $orderId     = $payload['order_id']          ?? '';
        $statusCode  = $payload['status_code']       ?? '';
        $grossAmount = $payload['gross_amount']       ?? '';
        $txStatus    = $payload['transaction_status'] ?? '';
        $fraudStatus = $payload['fraud_status']       ?? '';
        $sigKey      = $payload['signature_key']      ?? '';

        Log::info('[Webhook] HIT', [
            'order_id'   => $orderId,
            'tx_status'  => $txStatus,
            'fraud'      => $fraudStatus,
        ]);

        $expectedSig = hash('sha512', $orderId . $statusCode . $grossAmount . config('midtrans.server_key'));
        if ($sigKey !== $expectedSig) {
            Log::warning('[Webhook] Invalid signature: ' . $orderId);
            return response()->json(['message' => 'invalid signature'], 403);
        }

        $isPaid   = in_array($txStatus, ['capture', 'settlement'])
                    && ($fraudStatus === 'accept' || $fraudStatus === '');
        $isFailed = in_array($txStatus, ['cancel', 'deny', 'expire', 'failure']);

        if (str_starts_with($orderId, 'RNW-')) {
            return $this->handleRenewalWebhook($orderId, $payload, $isPaid, $isFailed);
        }

        if (str_starts_with($orderId, 'INV-')) {
            return $this->handleInvoiceWebhook($orderId, $payload, $isPaid, $isFailed);
        }

        Log::warning('[Webhook] Order ID tidak dikenali: ' . $orderId);
        return response()->json(['message' => 'order id tidak dikenali'], 200);
    }

    private function handleInvoiceWebhook(
        string $orderId,
        array  $payload,
        bool   $isPaid,
        bool   $isFailed
    ): \Illuminate\Http\JsonResponse {

        $parts     = explode('-', $orderId);
        $invoiceId = null;

        if (isset($parts[1]) && is_numeric($parts[1])) {
            $invoiceId = (int) $parts[1];
        }

        $invoice = null;

        if ($invoiceId) {
            $invoice = Invoice::with(['user.role', 'room', 'booking', 'waitingList.booking'])->find($invoiceId);
        }

        if (!$invoice) {
            $payment = Payment::where('midtrans_order_id', $orderId)->first();
            if ($payment) {
                $invoice = Invoice::with(['user.role', 'room', 'booking', 'waitingList.booking'])
                    ->find($payment->invoice_id);
            }
        }

        if (!$invoice) {
            Log::warning('[Webhook/INV] Invoice tidak ditemukan: ' . $orderId);
            return response()->json(['message' => 'invoice not found'], 404);
        }

        if ($invoice->status === 'paid') {
            Log::info('[Webhook/INV] Invoice sudah paid, skip: ' . $orderId);
            return response()->json(['message' => 'already paid'], 200);
        }

        $payment = $invoice->payments()
            ->where('midtrans_order_id', $orderId)
            ->whereIn('status', ['initiated', 'pending'])
            ->first();

        if ($isPaid) {
            DB::transaction(function () use ($invoice, $payment, $payload) {
                if ($payment) {
                    $payment->update([
                        'status'                  => 'verified',
                        'verified_at'             => now(),
                        'midtrans_transaction_id' => $payload['transaction_id'] ?? null,
                    ]);
                } else {
                    $invoice->payments()->create([
                        'user_id'                 => $invoice->user_id,
                        'payment_code'            => $payload['order_id'],
                        'method'                  => 'midtrans',
                        'amount'                  => $invoice->total_amount,
                        'status'                  => 'verified',
                        'verified_at'             => now(),
                        'midtrans_transaction_id' => $payload['transaction_id'] ?? null,
                        'midtrans_order_id'       => $payload['order_id'],
                    ]);
                }

                $invoice->update(['status' => 'paid', 'paid_at' => now()]);
                if (in_array($invoice->invoice_type, ['renewal_monthly', 'renewal']) && $invoice->renewal_id) {
                    $this->checkRenewalMonthlyCompletion($invoice);
                }
            });

            Log::info('[Webhook/INV] PAID: ' . $orderId . ' → Invoice #' . $invoice->id);

            try {
                $invoice->refresh()->loadMissing(['user.role', 'room', 'booking', 'waitingList.booking', 'payments']);
                (new NotificationService())->handleInvoicePaidNotifications($invoice);
                Log::info('[Webhook/INV] Notifikasi selesai: ' . $invoice->invoice_number);
            } catch (\Throwable $e) {
                Log::error('[Webhook/INV] Notifikasi gagal: ' . $e->getMessage(), [
                    'order_id'   => $orderId,
                    'invoice_id' => $invoice->id,
                    'trace'      => $e->getTraceAsString(),
                ]);
            }

        } elseif ($isFailed) {
            $payment?->update(['status' => 'rejected']);
            $invoice->update(['status' => 'unpaid']);
            Log::info('[Webhook/INV] FAILED: ' . $orderId);
        }

        return response()->json(['message' => 'ok']);
    }

    private function handleRenewalWebhook(
        string $orderId,
        array  $payload,
        bool   $isPaid,
        bool   $isFailed
    ): \Illuminate\Http\JsonResponse {

        $parts     = explode('-', $orderId);
        $renewalId = isset($parts[1]) && is_numeric($parts[1]) ? (int) $parts[1] : null;

        if (!$renewalId) {
            Log::warning('[Webhook/RNW] Tidak bisa parse renewalId dari: ' . $orderId);
            return response()->json(['message' => 'invalid order id format'], 200);
        }

        $renewal = Renewal::with(['user', 'room', 'invoice', 'booking'])->find($renewalId);

        if (!$renewal) {
            Log::warning('[Webhook/RNW] Renewal tidak ditemukan: ' . $orderId);
            return response()->json(['message' => 'renewal not found'], 404);
        }

        $invoice = $renewal->invoice;

        if (!$invoice) {
            $invoice = \App\Models\Invoice::where('user_id', $renewal->user_id)
                ->where('room_id', $renewal->room_id)
                ->where(function ($q) {
                    $q->where('invoice_type', 'renewal')
                      ->orWhere('invoice_number', 'like', 'INV-RNW-%');
                })
                ->whereIn('status', ['unpaid', 'overdue', 'pending_verification'])
                ->latest()
                ->first();

            if ($invoice) {
                $invoice->update(['renewal_id' => $renewal->id, 'invoice_type' => 'renewal']);
                Log::info('[Webhook/RNW] Invoice ditemukan via fallback dan di-sync: ' . $invoice->invoice_number);
            }
        }

        if (!$invoice) {
            Log::warning('[Webhook/RNW] Invoice renewal tidak ditemukan untuk renewal #' . $renewalId);
            return response()->json(['message' => 'invoice not found'], 404);
        }

        if ($invoice->status === 'paid') {
            Log::info('[Webhook/RNW] Invoice sudah paid, skip: ' . $orderId);
            return response()->json(['message' => 'already paid'], 200);
        }

        $payment = $invoice->payments()
            ->where('midtrans_order_id', $orderId)
            ->whereIn('status', ['initiated', 'pending'])
            ->first();

        if ($isPaid) {
            DB::transaction(function () use ($invoice, $renewal, $payment, $payload) {
                if ($payment) {
                    $payment->update([
                        'status'                  => 'verified',
                        'verified_at'             => now(),
                        'midtrans_transaction_id' => $payload['transaction_id'] ?? null,
                    ]);
                } else {
                    $invoice->payments()->create([
                        'user_id'                 => $renewal->user_id,
                        'payment_code'            => $payload['order_id'],
                        'method'                  => 'midtrans',
                        'amount'                  => $invoice->total_amount,
                        'status'                  => 'verified',
                        'verified_at'             => now(),
                        'midtrans_transaction_id' => $payload['transaction_id'] ?? null,
                        'midtrans_order_id'       => $payload['order_id'],
                    ]);
                }

                $invoiceUpdates = ['status' => 'paid', 'paid_at' => now()];
                if (!$invoice->renewal_id) { $invoiceUpdates['renewal_id'] = $renewal->id; }
                if ($invoice->invoice_type !== 'renewal') { $invoiceUpdates['invoice_type'] = 'renewal'; }
                $invoice->update($invoiceUpdates);
            });

            Log::info('[Webhook/RNW] PAID: ' . $orderId . ' → Renewal #' . $renewalId);

            try {
                $invoice->refresh()->loadMissing(['user.role', 'room', 'booking', 'payments']);
                (new NotificationService())->handleInvoicePaidNotifications($invoice);
                Log::info('[Webhook/RNW] Notifikasi renewal selesai: ' . $invoice->invoice_number);
            } catch (\Throwable $e) {
                Log::error('[Webhook/RNW] Notifikasi gagal: ' . $e->getMessage(), [
                    'order_id'   => $orderId,
                    'renewal_id' => $renewalId,
                    'trace'      => $e->getTraceAsString(),
                ]);
            }

        } elseif ($isFailed) {
            $payment?->update(['status' => 'rejected']);
            $invoice->update(['status' => 'unpaid']);
            Log::info('[Webhook/RNW] FAILED: ' . $orderId);
        }

        return response()->json(['message' => 'ok']);
    }

    private function checkRenewalMonthlyCompletion(\App\Models\Invoice $invoice): void
    {
        $renewal = \App\Models\Renewal::with(['booking', 'room', 'user'])
            ->find($invoice->renewal_id);

        if (!$renewal || $renewal->status === 'approved') return;

        if ($renewal->payment_method !== 'monthly') return;

        $paidCount = \App\Models\Invoice::where('renewal_id', $renewal->id)
            ->where('invoice_type', 'renewal_monthly')
            ->where('status', 'paid')
            ->count();

        if ($paidCount < $renewal->duration_months) {
            Log::info('[RenewalMonthly] Bulan ke-' . $paidCount . '/' .
                $renewal->duration_months . ' lunas, menunggu bulan berikutnya. Renewal #' . $renewal->id);
            return;
        }

        $newEndDate = Carbon::parse($renewal->new_end_date);

        $booking = $renewal->booking
            ?? \App\Models\Booking::where('user_id', $renewal->user_id)
                                ->where('room_id', $renewal->room_id)
                                ->whereIn('status', ['approved', 'active'])
                                ->latest()
                                ->first();

        if (!$booking) {
            Log::warning('[RenewalMonthly] Booking tidak ditemukan untuk renewal #' . $renewal->id);
            return;
        }

        DB::transaction(function () use ($renewal, $booking, $newEndDate) {
            $updateData = [
                'duration_months'        => $booking->duration_months + $renewal->duration_months,
                'end_date'               => $newEndDate->toDateString(),
                'status'                 => 'active',
                'renewal_payment_method' => null,
                'renewal_monthly_count'  => 0,
                'renewal_duration'       => null,
                'renewal_id'             => null,
                'renewal_period_start'   => null,
                'renewal_monthly_day'    => null,
                'renewal_service_monthly'=> 0,
            ];

            if ($renewal->additional_services !== null) {
                $updateData['additional_services'] = $renewal->additional_services;
            }

            $booking->update($updateData);

            $renewal->update([
                'status'      => 'approved',
                'approved_at' => now(),
            ]);
        });

        Log::info('[RenewalMonthly] Renewal #' . $renewal->id . ' selesai semua, status -> approved. ' .
            'Booking end_date -> ' . $newEndDate->toDateString());

        \App\Models\InAppNotification::send(
            $renewal->user_id,
            'renewal_fully_completed',
            ' Semua Cicilan Lunas — Sewa Berhasil Diperpanjang!',
            'Seluruh ' . $renewal->duration_months . ' bulan cicilan telah lunas. ' .
                'Sewa kamar ' . ($renewal->room?->room_number ?? '-') .
                ' diperpanjang hingga ' . $newEndDate->format('d M Y') . '.',
            route('tenant.renewal.invoice', $renewal->id),
            ['renewal_id' => $renewal->id]
        );
    }
}
