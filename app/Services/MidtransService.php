<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    private string $serverKey;
    private string $clientKey;
    private bool   $isProduction;
    private bool   $enabled;
    private string $baseUrl;

    public function __construct()
    {
        $this->serverKey    = env('MIDTRANS_SERVER_KEY', '');
        $this->clientKey    = env('MIDTRANS_CLIENT_KEY', '');
        $this->isProduction = filter_var(env('MIDTRANS_IS_PRODUCTION', false), FILTER_VALIDATE_BOOLEAN);
        $this->enabled      = filter_var(env('MIDTRANS_ENABLED', false), FILTER_VALIDATE_BOOLEAN);
        $this->baseUrl      = $this->isProduction
            ? 'https://app.midtrans.com/snap/v1'
            : 'https://app.sandbox.midtrans.com/snap/v1';
    }

    public function createSnapToken(Invoice $invoice): ?array
    {
        if (!$this->enabled || empty($this->serverKey)) {
            Log::info("[Midtrans DISABLED] Invoice: {$invoice->invoice_number}");
            return null;
        }

        $orderId = 'INV-' . $invoice->id . '-' . time();
        $user    = $invoice->user;
        $room    = $invoice->room;

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $invoice->total_amount,
            ],
            'customer_details' => [
                'first_name' => $user?->name ?? 'Penghuni',
                'email'      => $user?->email ?? '',
                'phone'      => $user?->phone ?? '',
            ],
            'item_details' => [
                [
                    'id'       => 'SEWA-' . $room?->id,
                    'price'    => (int) $invoice->base_amount,
                    'quantity' => 1,
                    'name'     => 'Sewa Kamar ' . ($room?->room_number ?? '') . ' - ' .
                                  $invoice->period_start?->format('M Y'),
                ],
            ],
            'payment_type' => 'snap',
            'enabled_payments' => ['bca_va', 'bni_va', 'bri_va', 'permata_va', 'qris'],
            'callbacks' => [
                'finish' => route('tenant.invoices.show', $invoice->id),
            ],
        ];

        if ($invoice->service_amount > 0) {
            $params['item_details'][] = [
                'id'       => 'SERVICE-' . $invoice->id,
                'price'    => (int) $invoice->service_amount,
                'quantity' => 1,
                'name'     => 'Layanan Tambahan',
            ];
        }

        try {
            $response = Http::withBasicAuth($this->serverKey, '')
                ->post("{$this->baseUrl}/transactions", $params);

            if ($response->successful()) {
                $data = $response->json();

                Payment::create([
                    'invoice_id'       => $invoice->id,
                    'user_id'          => $invoice->user_id,
                    'amount'           => $invoice->total_amount,
                    'method'           => 'midtrans',
                    'status'           => 'initiated',
                    'midtrans_order_id'=> $orderId,
                ]);

                $invoice->update(['status' => 'pending_verification']);

                return [
                    'token'        => $data['token'],
                    'redirect_url' => $data['redirect_url'],
                    'order_id'     => $orderId,
                    'client_key'   => $this->clientKey,
                    'is_sandbox'   => !$this->isProduction,
                ];
            }

            Log::warning("[Midtrans ERROR] " . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error("[Midtrans EXCEPTION] {$e->getMessage()}");
            return null;
        }
    }

    public function handleWebhook(array $payload): bool
    {
        $signatureKey = hash('sha512',
            $payload['order_id'] .
            $payload['status_code'] .
            $payload['gross_amount'] .
            $this->serverKey
        );

        if ($signatureKey !== $payload['signature_key']) {
            Log::warning('[Midtrans] Invalid signature key');
            return false;
        }

        $transactionStatus = $payload['transaction_status'];
        $orderId           = $payload['order_id'];

        $payment = Payment::where('midtrans_order_id', $orderId)->first();
        if (!$payment) {
            Log::warning("[Midtrans Webhook] Payment not found for order: {$orderId}");
            return false;
        }

        if (in_array($transactionStatus, ['capture', 'settlement'])) {
            $payment->update([
                'status'                 => 'verified',
                'paid_at'                => now(),
                'verified_at'            => now(),
                'midtrans_transaction_id'=> $payload['transaction_id'] ?? null,
            ]);

            $payment->invoice->update(['status' => 'paid']);

            Log::info("[Midtrans] Payment SUCCESS for order: {$orderId}");
            return true;
        }

        if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $payment->update(['status' => 'rejected', 'rejection_reason' => "Midtrans: {$transactionStatus}"]);
            $payment->invoice->update(['status' => 'unpaid']);
            Log::info("[Midtrans] Payment {$transactionStatus} for order: {$orderId}");
        }

        return true;
    }

    public function getClientKey(): string { return $this->clientKey; }
    public function isEnabled(): bool { return $this->enabled; }
    public function isSandbox(): bool { return !$this->isProduction; }
}
