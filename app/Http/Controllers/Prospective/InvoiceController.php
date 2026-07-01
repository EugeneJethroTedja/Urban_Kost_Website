<?php

namespace App\Http\Controllers\Prospective;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;

class InvoiceController extends Controller
{
    private NotificationService $notif;

    public function __construct(NotificationService $notif)
    {
        $this->notif = $notif;
    }

    public function index()
    {
        $invoices = Invoice::where('user_id', auth()->id())
            ->whereNotIn('status', ['cancelled'])
            ->with('room')
            ->latest()
            ->paginate(10);

        $stats = [
            'unpaid'  => Invoice::where('user_id', auth()->id())
                            ->whereNotIn('status', ['cancelled'])
                            ->whereIn('status', ['unpaid', 'overdue'])
                            ->count(),
            'pending' => Invoice::where('user_id', auth()->id())
                            ->whereNotIn('status', ['cancelled'])
                            ->where('status', 'pending_verification')
                            ->count(),
            'paid'    => Invoice::where('user_id', auth()->id())
                            ->whereNotIn('status', ['cancelled'])
                            ->where('status', 'paid')
                            ->count(),
        ];

        return view('prospective.invoices.index', compact('invoices', 'stats'));
    }

    public function show(Invoice $invoice)
    {
        abort_if($invoice->user_id !== auth()->id(), 403);

        $payments = $invoice->payments()
            ->whereNotIn('status', ['initiated'])
            ->latest()
            ->get();

        return view('prospective.invoices.show', compact('invoice', 'payments'));
    }

    public function pay(Request $request, Invoice $invoice)
    {
        abort_if($invoice->user_id !== auth()->id(), 403);
        abort_if(
            !in_array($invoice->status, ['unpaid', 'overdue']),
            422,
            'Invoice tidak dapat dibayar.'
        );

        $request->validate([
            'method'         => 'required|in:transfer_bank,qris,cash',
            'amount'         => 'required|numeric|min:1',
            'transfer_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'bank_name'      => 'nullable|string|max:50',
            'account_number' => 'nullable|string|max:30',
        ]);

        $existing = $invoice->payments()
            ->where('status', 'pending')
            ->whereIn('method', ['transfer_bank', 'qris', 'cash'])
            ->first();

        if ($existing) {
            return back()->with('warning', 'Sudah ada bukti yang sedang diverifikasi untuk invoice ini.');
        }

        $path = $request->file('transfer_proof')->store('payment-proofs', 'public');

        $paymentCode = 'PAY-' . strtoupper(now()->format('YmdHis')) . '-' . $invoice->id;

        $payment = $invoice->payments()->create([
            'user_id'        => auth()->id(),
            'payment_code'   => $paymentCode,
            'method'         => $request->method,
            'amount'         => $request->amount,
            'status'         => 'pending',   
            'transfer_proof' => $path,
            'bank_name'      => $request->bank_name,
            'account_number' => $request->account_number,
        ]);

        $invoice->update(['status' => 'pending_verification']);

        $this->notif->notifyPaymentUploaded(
            $payment->load(['invoice.user', 'invoice.room'])
        );

        return redirect()
            ->route('prospective.invoices.show', $invoice->id)
            ->with('success', ' Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
    }

    public function snapToken(Request $request, Invoice $invoice)
    {
        abort_if($invoice->user_id !== auth()->id(), 403);

        if (!in_array($invoice->status, ['unpaid', 'overdue'])) {
            return response()->json([
                'error' => $invoice->status === 'paid'
                    ? 'Invoice ini sudah lunas.'
                    : 'Invoice sedang dalam proses verifikasi.',
            ], 422);
        }

        $request->validate([
            'method' => 'required|in:qris,bank',
            'bank'   => 'nullable|in:bca_va,bni_va,bri_va,permata_va,other_va',
        ]);

        $existingPayment = $invoice->payments()
            ->whereIn('status', ['initiated', 'pending'])
            ->where('method', 'midtrans')
            ->whereNotNull('snap_token')
            ->latest()
            ->first();

        if ($existingPayment?->snap_token) {
            return response()->json(['snap_token' => $existingPayment->snap_token]);
        }

        $this->configureMidtrans();

        $orderId = 'INV-' . $invoice->id . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $invoice->total_amount,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email'      => auth()->user()->email,
                'phone'      => auth()->user()->phone ?? '',
            ],
            'item_details' => [[
                'id'       => 'ROOM-' . $invoice->room_id,
                'price'    => (int) $invoice->total_amount,
                'quantity' => 1,
                'name'     => mb_substr('Tagihan ' . $invoice->invoice_number, 0, 50),
            ]],
            'callbacks' => [
                'finish' => route('prospective.invoices.show', $invoice->id),
            ],
        ];

        if ($request->method === 'qris') {
            $params['enabled_payments'] = ['other_qris', 'gopay', 'shopeepay', 'dana'];
        } elseif ($request->method === 'bank') {
            $bankMap = [
                'bca_va'     => 'bca_va',
                'bni_va'     => 'bni_va',
                'bri_va'     => 'bri_va',
                'permata_va' => 'permata_va',
                'other_va'   => 'other_va',
            ];
            $params['enabled_payments'] = [$bankMap[$request->bank] ?? 'bca_va'];
        }

        try {
            $snapToken = Snap::getSnapToken($params);

            $invoice->payments()->create([
                'user_id'           => auth()->id(),
                'payment_code'      => $orderId,
                'method'            => 'midtrans',
                'amount'            => $invoice->total_amount,
                'status'            => 'initiated',   
                'snap_token'        => $snapToken,
                'midtrans_order_id' => $orderId,
            ]);

            return response()->json(['snap_token' => $snapToken]);

        } catch (\Exception $e) {
            Log::error('[InvoiceSnapToken] ' . $e->getMessage(), ['invoice_id' => $invoice->id]);
            return response()->json(['error' => 'Gagal menghubungi Midtrans: ' . $e->getMessage()], 500);
        }
    }

    public function webhook(Request $request)
    {
        MidtransConfig::$serverKey    = config('midtrans.server_key');
        MidtransConfig::$isProduction = config('midtrans.is_production', false);

        $payload     = $request->all();
        $orderId     = $payload['order_id']          ?? '';
        $statusCode  = $payload['status_code']       ?? '';
        $grossAmount = $payload['gross_amount']       ?? '';
        $txStatus    = $payload['transaction_status'] ?? '';
        $fraudStatus = $payload['fraud_status']       ?? '';
        $sigKey      = $payload['signature_key']      ?? '';

        $expectedSig = hash('sha512', $orderId . $statusCode . $grossAmount . config('midtrans.server_key'));
        if ($sigKey !== $expectedSig) {
            Log::warning('[InvoiceWebhook] Signature tidak valid: ' . $orderId);
            return response()->json(['message' => 'invalid signature'], 403);
        }

        if (!str_starts_with($orderId, 'INV-')) {
            return response()->json(['message' => 'bukan order invoice'], 200);
        }

        $parts     = explode('-', $orderId);
        $invoiceId = $parts[1] ?? null;

        if (!$invoiceId || !($invoice = Invoice::with('user', 'room')->find($invoiceId))) {
            return response()->json(['message' => 'invoice not found'], 404);
        }

        if ($invoice->status === 'paid') {
            return response()->json(['message' => 'already paid'], 200);
        }

        $payment = $invoice->payments()
            ->where('midtrans_order_id', $orderId)
            ->whereIn('status', ['initiated', 'pending'])
            ->first();

        $isPaid   = in_array($txStatus, ['capture', 'settlement'])
                    && ($fraudStatus === 'accept' || $fraudStatus === '');
        $isFailed = in_array($txStatus, ['cancel', 'deny', 'expire', 'failure']);

        if ($isPaid) {
            DB::transaction(function () use ($invoice, $payment, $payload) {
                $payment?->update([
                    'status'                  => 'verified',
                    'verified_at'             => now(),
                    'midtrans_transaction_id' => $payload['transaction_id'] ?? null,
                ]);

                $invoice->update([
                    'status'                    => 'paid',
                    'paid_at'                   => now(),
                    'pending_tenant_activation' => true,
                ]);
            });

            $this->notif->notifyInvoicePaid($invoice);
            $this->notif->notifyAdminInvoicePaidNeedsActivation($invoice);

            Log::info('[InvoiceWebhook] ' . $invoice->invoice_number . ' PAID via Midtrans.');

        } elseif ($isFailed) {
            $payment?->update(['status' => 'rejected']);
            $invoice->update(['status' => 'unpaid']);

            Log::info('[InvoiceWebhook] ' . $orderId . ' FAILED — reset ke unpaid.');
        }

        return response()->json(['message' => 'ok'], 200);
    }

    public function activateTenant(Invoice $invoice)
    {
        abort_if(!auth()->user()->hasRole('admin'), 403);
        abort_if($invoice->status !== 'paid', 422, 'Invoice belum lunas.');
        abort_if(!$invoice->pending_tenant_activation, 422, 'Sudah diaktifkan sebelumnya.');

        DB::transaction(function () use ($invoice) {
            $user = $invoice->user;

            if ($user && in_array($user->role?->name, ['prospective_tenant'])) {
                $tenantRole = \App\Models\Role::where('name', 'tenant')->firstOrFail();
                $user->update(['role_id' => $tenantRole->id, 'is_active' => true]);
            }

            $invoice->room?->update(['status' => 'occupied']);
            $invoice->booking?->update(['status' => 'active']);
            $invoice->update(['pending_tenant_activation' => false]);
        });

        $this->notif->notifyTenantUpgraded($invoice->user, $invoice->room);

        return back()->with(
            'success',
            " {$invoice->user?->name} berhasil diaktifkan sebagai penghuni kamar {$invoice->room?->room_number}."
        );
    }

    private function configureMidtrans(): void
    {
        MidtransConfig::$serverKey    = config('midtrans.server_key');
        MidtransConfig::$isProduction = config('midtrans.is_production', false);
        MidtransConfig::$isSanitized  = true;
        MidtransConfig::$is3ds        = true;
    }
}
