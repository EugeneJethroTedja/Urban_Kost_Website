<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\MidtransService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;

class InvoiceController extends Controller
{
    private MidtransService     $midtrans;
    private NotificationService $notif;

    public function __construct()
    {
        $this->midtrans = new MidtransService();
        $this->notif    = new NotificationService();
    }

    public function index()
    {
        $userId = auth()->id();

        $invoiceQuery = Invoice::where('user_id', $userId)
            ->whereNotIn('status', ['cancelled'])
            ->where(function ($q) {
                $q->whereNull('booking_id')
                ->orWhereHas('booking', fn($q) =>
                    $q->whereNotIn('status', ['rejected', 'cancelled'])
                );
            });

        $invoices = $invoiceQuery
            ->with('room')
            ->orderBy('due_date', 'desc')
            ->paginate(10);

        $stats = [
            'unpaid'            => (clone $invoiceQuery)->whereIn('status', ['unpaid', 'overdue'])->count(),
            'paid'              => (clone $invoiceQuery)->where('status', 'paid')->count(),
            'pending'           => (clone $invoiceQuery)->where('status', 'pending_verification')->count(),
            'total_paid_amount' => (clone $invoiceQuery)->where('status', 'paid')->sum('total_amount'),
        ];

        return view('tenant.invoices.index', compact('invoices', 'stats'));
    }

    public function show($id)
    {
        $invoice = Invoice::with(['room', 'payments.verifier'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        $payments        = $invoice->payments()
            ->whereNotIn('status', ['initiated'])
            ->orderBy('created_at', 'desc')
            ->get();

        $midtransEnabled = $this->midtrans->isEnabled();

        return view('tenant.invoices.show', compact(
            'invoice', 'payments', 'midtransEnabled'
        ));
    }

    public function snapToken(Request $request, $id)
    {
        $request->validate([
            'method' => 'required|in:qris,bank',
            'bank'   => 'nullable|in:bca_va,bni_va,bri_va,permata_va,other_va',
        ]);

        $invoice = Invoice::where('user_id', auth()->id())
            ->whereIn('status', ['unpaid', 'overdue'])
            ->with(['user', 'room'])
            ->findOrFail($id);

        $existingPayment = $invoice->payments()
            ->whereIn('status', ['initiated', 'pending'])
            ->where('method', 'midtrans')
            ->whereNotNull('snap_token')
            ->latest()
            ->first();

        if ($existingPayment) {
            return response()->json(['snap_token' => $existingPayment->snap_token]);
        }

        $this->configureMidtrans();

        $method  = $request->input('method');
        $bank    = $request->input('bank');
        $orderId = 'INV-' . $invoice->id . '-' . now()->format('YmdHis') . '-' . random_int(1000, 9999);

        $payableAmount = (int) $invoice->total_amount;

        $items = [];

        $baseAmount    = (int) ($invoice->base_amount    ?? 0);
        $serviceAmount = (int) ($invoice->service_amount ?? 0);

        if ($baseAmount > 0) {
            $items[] = [
                'id'       => 'ROOM-INV-' . $invoice->id,
                'price'    => $baseAmount,
                'quantity' => 1,
                'name'     => 'Sewa Kamar ' . ($invoice->room?->room_number ?? '-'),
            ];
        }

        if ($serviceAmount > 0) {
            $items[] = [
                'id'       => 'SVC-INV-' . $invoice->id,
                'price'    => $serviceAmount,
                'quantity' => 1,
                'name'     => 'Layanan Tambahan',
            ];
        }

        if (empty($items)) {
            $items[] = [
                'id'       => 'INV-' . $invoice->id,
                'price'    => $payableAmount,
                'quantity' => 1,
                'name'     => 'Tagihan ' . $invoice->invoice_number,
            ];
        }

        $itemsTotal = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $items));
        if ($itemsTotal !== $payableAmount) {
            $items[count($items) - 1]['price'] += ($payableAmount - $itemsTotal);
        }

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $payableAmount,
            ],
            'customer_details' => [
                'first_name' => $invoice->user?->name ?? auth()->user()->name,
                'email'      => $invoice->user?->email ?? auth()->user()->email,
                'phone'      => $invoice->user?->phone ?? auth()->user()->phone ?? '',
            ],
            'item_details' => $items,
            'callbacks'    => [
                'finish' => route('tenant.invoices.show', $invoice->id),
            ],
        ];

        if ($method === 'qris') {
            $params['enabled_payments'] = ['other_qris', 'gopay', 'shopeepay', 'dana'];
        } elseif ($method === 'bank') {
            $bankMap = [
                'bca_va'     => 'bca_va',
                'bni_va'     => 'bni_va',
                'bri_va'     => 'bri_va',
                'permata_va' => 'permata_va',
                'other_va'   => 'other_va',
            ];
            $params['enabled_payments'] = [$bankMap[$bank] ?? 'other_va'];
        }

        try {
            $snapToken = Snap::getSnapToken($params);
        } catch (\Exception $e) {
            Log::error('[InvoiceSnapToken] ' . $e->getMessage(), [
                'invoice_id' => $invoice->id,
                'params'     => $params,
            ]);
            return response()->json([
                'error' => 'Gagal menghubungi Midtrans: ' . $e->getMessage(),
            ], 500);
        }

        try {
            $invoice->payments()->create([
                'user_id'           => auth()->id(),
                'payment_code'      => $orderId,
                'method'            => 'midtrans',
                'amount'            => $payableAmount,
                'status'            => 'initiated',   
                'snap_token'        => $snapToken,
                'midtrans_order_id' => $orderId,
            ]);
        } catch (\Exception $e) {
            Log::error('[InvoiceSnapToken] Gagal simpan draft payment: ' . $e->getMessage(), [
                'invoice_id' => $invoice->id,
                'order_id'   => $orderId,
            ]);
        }

        return response()->json(['snap_token' => $snapToken]);
    }

    public function midtransWebhook(Request $request)
    {
        $payload = $request->all();
        $success = $this->midtrans->handleWebhook($payload);

        if ($success && in_array($payload['transaction_status'] ?? '', ['capture', 'settlement'])) {
            $payment = Payment::where('midtrans_order_id', $payload['order_id'])->first();
            if ($payment) {
                $this->notif->notifyPaymentVerified($payment);
            }
        }

        return response()->json(['status' => $success ? 'ok' : 'error']);
    }

    public function pay(Request $request, $id)
    {
        $invoice = Invoice::where('user_id', auth()->id())
            ->whereIn('status', ['unpaid', 'overdue'])
            ->findOrFail($id);

        $request->validate([
            'method'         => 'required|in:transfer_bank,qris,cash',
            'bank_name'      => 'nullable|string|max:50',
            'account_number' => 'nullable|string|max:50',
            'transfer_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:3072',
            'amount'         => 'required|numeric|min:1',
        ], [
            'method.required'         => 'Metode pembayaran wajib dipilih.',
            'transfer_proof.required' => 'Bukti pembayaran wajib diupload.',
            'transfer_proof.mimes'    => 'Format file harus JPG, PNG, atau PDF.',
            'transfer_proof.max'      => 'Ukuran file maksimal 3MB.',
            'amount.required'         => 'Nominal bayar wajib diisi.',
            'amount.min'              => 'Nominal tidak valid.',
        ]);

        $existing = Payment::where('invoice_id', $invoice->id)
            ->where('status', 'pending')
            ->whereIn('method', ['transfer_bank', 'qris', 'cash'])
            ->first();

        if ($existing) {
            return back()->with('warning', 'Sudah ada bukti yang sedang diverifikasi untuk invoice ini.');
        }

        $proofPath = $request->file('transfer_proof')->store('payment-proofs', 'public');

        $payment = Payment::create([
            'invoice_id'     => $invoice->id,
            'user_id'        => auth()->id(),
            'amount'         => $request->amount,
            'method'         => $request->method,
            'bank_name'      => $request->bank_name,
            'account_number' => $request->account_number,
            'transfer_proof' => $proofPath,
            'status'         => 'pending',   
            'paid_at'        => now(),
        ]);

        $invoice->update(['status' => 'pending_verification']);

        $this->notif->notifyPaymentUploaded($payment);

        return redirect()->route('tenant.invoices.show', $invoice->id)
            ->with('success', ' Bukti pembayaran berhasil diupload! Admin akan memverifikasi dalam 1×24 jam.');
    }

    private function configureMidtrans(): void
    {
        MidtransConfig::$serverKey    = config('midtrans.server_key');
        MidtransConfig::$isProduction = config('midtrans.is_production');
        MidtransConfig::$isSanitized  = true;
        MidtransConfig::$is3ds        = true;
    }
}
