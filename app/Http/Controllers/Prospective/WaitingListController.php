<?php

namespace App\Http\Controllers\Prospective;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\WaitingList;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WaitingListController extends Controller
{
    private function getWlInvoices(WaitingList $wl)
    {
        return Invoice::where(function ($q) use ($wl) {
                $q->where('waiting_list_id', $wl->id);
            })
            ->orWhere(function ($q) use ($wl) {
                if (! $wl->booking_id) return;
                $q->whereNull('waiting_list_id')
                ->where('booking_id', $wl->booking_id)
                ->where('user_id', $wl->user_id)
                ->where('room_id', $wl->room_id)
                ->where(function ($q2) {
                    $q2->where('invoice_number', 'like', '%-DP-%')
                        ->orWhere('invoice_number', 'like', '%-SISA-%');
                });
            })
            ->orderBy('created_at')
            ->get()
            ->unique('id');
    }

    private function calcFinancials(WaitingList $wl, $invoices)
    {
        $roomPrice     = (float) ($wl->room?->price ?? 0);
        $serviceAmount = (float) ($wl->booking?->service_amount ?? 0);
        $duration      = (int)   ($wl->booking?->duration_months ?? 1);
        $paymentMethod = $wl->booking?->payment_method ?? 'lump_sum';  

        $totalSewa = ($roomPrice + $serviceAmount) * $duration;

        $dpInvoice        = $invoices->first(fn($i) => str_contains($i->invoice_number, '-DP-'));
        $remainingInvoice = $invoices->first(fn($i) => str_contains($i->invoice_number, '-SISA-'));

        if ($dpInvoice) {
            $dpAmount = (float) $dpInvoice->total_amount;
        } elseif ($paymentMethod === 'monthly') {
            $dpAmount = round(($roomPrice + $serviceAmount) * 0.5); 
        } else {
            $dpAmount = round($totalSewa * 0.5);  
        }

        $sisaAmount = $remainingInvoice
            ? (float) $remainingInvoice->total_amount
            : max(0, $paymentMethod === 'monthly'
                ? ($roomPrice + $serviceAmount) - $dpAmount   
                : $totalSewa - $dpAmount                       
            );

        $totalPaid = (float) $invoices->where('status', 'paid')->sum('total_amount');

        $remaining = max(0, $totalSewa - $totalPaid);

        $dpIsPaid = $dpInvoice && $dpInvoice->status === 'paid';

        return compact(
            'totalSewa', 'roomPrice', 'serviceAmount', 'duration',
            'dpAmount', 'sisaAmount',
            'totalPaid', 'remaining',
            'dpIsPaid', 'dpInvoice', 'remainingInvoice'
        );
    }

    public function index()
    {
        $waitingLists = WaitingList::where('user_id', auth()->id())
            ->with(['room', 'booking'])
            ->orderBy('created_at', 'desc')
            ->get();

        $invoicesPerWl  = [];
        $activeInvoices = [];

        foreach ($waitingLists as $wl) {
            $all = $this->getWlInvoices($wl);

            foreach ($all as $inv) {
                if (! $inv->waiting_list_id) {
                    $inv->updateQuietly(['waiting_list_id' => $wl->id]);
                }
            }

            $fin = $this->calcFinancials($wl, $all);

            $invoicesPerWl[$wl->id] = [
                'all'               => $all,
                'dp'                => $fin['dpInvoice'],
                'remaining'         => $fin['remainingInvoice'],
                'dp_is_paid'        => $fin['dpIsPaid'],
                'remaining_is_paid' => $fin['remainingInvoice'] && $fin['remainingInvoice']->status === 'paid',
                'total_paid'        => $fin['totalPaid'],
                'dp_amount'         => $fin['dpAmount'],
                'sisa_amount'       => $fin['sisaAmount'],
                'total_sewa'        => $fin['totalSewa'],
                'remaining_amount'  => $fin['remaining'],
                'room_price'        => $fin['roomPrice'],
                'service_amount'    => $fin['serviceAmount'],
                'duration'          => $fin['duration'],
            ];

            $activeInv = null;
            if ($fin['remainingInvoice'] && in_array($fin['remainingInvoice']->status, ['unpaid', 'overdue', 'pending_verification'])) {
                $activeInv = $fin['remainingInvoice'];
            } elseif ($fin['dpInvoice'] && in_array($fin['dpInvoice']->status, ['unpaid', 'overdue', 'pending_verification'])) {
                $activeInv = $fin['dpInvoice'];
            }
            $activeInvoices[$wl->id] = $activeInv;
        }

        return view('prospective.waiting-list.index', compact(
            'waitingLists', 'invoicesPerWl', 'activeInvoices'
        ));
    }

    public function show($id)
    {
        $wl = WaitingList::where('user_id', auth()->id())
            ->with(['room', 'booking', 'approvedBy'])
            ->findOrFail($id);

        $invoices = $this->getWlInvoices($wl);

        foreach ($invoices as $inv) {
            if (! $inv->waiting_list_id) {
                $inv->updateQuietly(['waiting_list_id' => $wl->id]);
            }
        }

        $fin = $this->calcFinancials($wl, $invoices);

        $dpInvoice        = $fin['dpInvoice'];
        $remainingInvoice = $fin['remainingInvoice'];
        $dpIsPaid         = $fin['dpIsPaid'];
        $dpAmount         = $fin['dpAmount'];
        $sisaAmount       = $fin['sisaAmount'];
        $totalSewa        = $fin['totalSewa'];
        $totalPaid        = $fin['totalPaid'];
        $remaining        = $fin['remaining'];
        $roomPrice        = $fin['roomPrice'];
        $serviceAmount    = $fin['serviceAmount'];
        $duration         = $fin['duration'];

        $activeInvoice = null;
        if ($remainingInvoice && in_array($remainingInvoice->status, ['unpaid', 'overdue', 'pending_verification'])) {
            $activeInvoice = $remainingInvoice;
        } elseif ($dpInvoice && in_array($dpInvoice->status, ['unpaid', 'overdue', 'pending_verification'])) {
            $activeInvoice = $dpInvoice;
        }

        return view('prospective.waiting-list.show', compact(
            'wl', 'invoices', 'dpInvoice', 'remainingInvoice',
            'dpIsPaid', 'activeInvoice',
            'totalPaid', 'remaining', 'dpAmount', 'sisaAmount',
            'totalSewa', 'roomPrice', 'serviceAmount', 'duration'
        ));
    }

    public function cancel(Request $request, $id)
    {
        $wl = WaitingList::where('user_id', auth()->id())
            ->whereIn('status', ['waiting', 'notified'])
            ->with(['room', 'booking'])
            ->findOrFail($id);

        $request->validate(['cancellation_reason' => 'required|string|max:500']);

        $paidDp       = $this->getWlInvoices($wl)
                            ->filter(fn($i) => str_contains($i->invoice_number, '-DP-') && $i->status === 'paid')
                            ->first();
        $dpPaidAmount = $paidDp ? (int) $paidDp->total_amount : 0;
        $penalty      = $dpPaidAmount > 0 ? (int) round($dpPaidAmount * 0.10) : 0;

        $wl->update([
            'status'              => 'cancelled',
            'cancelled_at'        => now(),
            'cancellation_reason' => $request->cancellation_reason,
            'penalty_amount'      => $penalty, 
        ]);

        $wl->booking?->update([
            'status'              => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason,
        ]);

        (new NotificationService())->notifyWaitingListCancelled($wl->fresh()); 

            $msg = ' Posisi waiting list kamar <strong>' . ($wl->room?->room_number ?? '-') . '</strong> dibatalkan.';
            $msg .= $penalty > 0
                ? ' Denda 10% = <strong>Rp ' . number_format($penalty, 0, ',', '.') . '</strong>.'
                : ' Tidak ada denda (DP belum dibayar).';

        return redirect()->route('prospective.waiting-list.index')->with('warning', $msg);
    }

    public function midtransWebhook(Request $request)
    {
        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production', false);

        $payload     = $request->all();
        $orderId     = $payload['order_id']            ?? '';
        $txStatus    = $payload['transaction_status']   ?? '';
        $fraudStatus = $payload['fraud_status']         ?? '';

        if (! str_starts_with($orderId, 'WL-')) {
            return response()->json(['message' => 'ignored'], 200);
        }

        $signature = hash('sha512',
            $orderId .
            ($payload['status_code']  ?? '') .
            ($payload['gross_amount'] ?? '') .
            config('midtrans.server_key')
        );
        if ($signature !== ($payload['signature_key'] ?? '')) {
            return response()->json(['message' => 'invalid signature'], 403);
        }

        preg_match('/^WL-(\d+)-INV-(\d+)-/', $orderId, $m);
        $wlId      = $m[1] ?? null;
        $invoiceId = $m[2] ?? null;

        $wl = WaitingList::with(['room', 'user'])->find($wlId);
        if (! $wl) return response()->json(['message' => 'wl not found'], 404);

        $isPaid   = in_array($txStatus, ['capture', 'settlement'])
                    && ($fraudStatus === 'accept' || $fraudStatus === '');
        $isFailed = in_array($txStatus, ['cancel', 'deny', 'expire', 'failure']);

        if ($isPaid) {
            DB::transaction(function () use ($wl, $invoiceId, $orderId, $payload) {
                $invoice = $invoiceId ? Invoice::find($invoiceId) : null;
                if (! $invoice) {
                    $invoice = $this->getWlInvoices($wl)
                        ->whereIn('status', ['unpaid', 'overdue'])
                        ->sortByDesc('created_at')
                        ->first();
                }
                if (! $invoice || $invoice->status === 'paid') return;

                $payment = $invoice->payments()->where('midtrans_order_id', $orderId)->first();
                if ($payment) {
                    $payment->update([
                        'status'                  => 'verified',
                        'verified_at'             => now(),
                        'midtrans_transaction_id' => $payload['transaction_id'] ?? null,
                    ]);
                } else {
                    $invoice->payments()->create([
                        'user_id'                 => $wl->user_id,
                        'payment_code'            => $orderId,
                        'method'                  => 'midtrans',
                        'amount'                  => $invoice->total_amount,
                        'status'                  => 'verified',
                        'verified_at'             => now(),
                        'midtrans_transaction_id' => $payload['transaction_id'] ?? null,
                        'midtrans_order_id'       => $orderId,
                    ]);
                }

                $invoice->update(['status' => 'paid', 'paid_at' => now()]);

                if (! $invoice->waiting_list_id) {
                    $invoice->updateQuietly(['waiting_list_id' => $wl->id]);
                }
                if (str_contains($invoice->invoice_number, '-DP-')) {
                    $wl->update(['dp_paid' => $invoice->total_amount, 'dp_paid_at' => now()]);
                }
                if (str_contains($invoice->invoice_number, '-SISA-')) {
                    $invoice->updateQuietly(['pending_tenant_activation' => true]);
                }
            });

            (new NotificationService())->notifyWaitingListDpPaid($wl->fresh());
            Log::info('[WL Webhook] ' . $orderId . ' PAID.');
        }

        if ($isFailed) {
            Invoice::find($invoiceId)?->payments()
                ->where('midtrans_order_id', $orderId)
                ->update(['status' => 'rejected']);
            Log::info('[WL Webhook] ' . $orderId . ' FAILED.');
        }

        return response()->json(['message' => 'ok'], 200);
    }
}
