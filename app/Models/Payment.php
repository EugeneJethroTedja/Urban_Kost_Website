<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    protected $fillable = [
        'payment_code','invoice_id','user_id','amount','method',
        'bank_name','account_number','transfer_proof','status',
        'paid_at','verified_at','verified_by','rejection_reason',
        'midtrans_order_id','midtrans_transaction_id',
    ];

    protected $casts = [
        'paid_at'     => 'datetime',
        'verified_at' => 'datetime',
        'amount'      => 'float',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($payment) {
            if (!$payment->payment_code) {
                $payment->payment_code = 'PAY-' . date('YmdHis') . '-' . strtoupper(Str::random(4));
            }
        });
    }

    public function invoice()  { return $this->belongsTo(Invoice::class); }
    public function user()     { return $this->belongsTo(User::class); }
    public function verifier() { return $this->belongsTo(User::class, 'verified_by'); }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending'  => '<span class="badge bg-warning text-dark">Menunggu Verifikasi</span>',
            'verified' => '<span class="badge bg-success">Terverifikasi</span>',
            'rejected' => '<span class="badge bg-danger">Ditolak</span>',
            default    => '<span class="badge bg-secondary">-</span>',
        };
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getMethodLabelAttribute(): string
    {
        return match($this->method) {
            'transfer_bank' => ' Transfer Bank',
            'qris'          => ' QRIS',
            'cash'          => ' Tunai',
            'midtrans'      => ' Midtrans',
            default         => $this->method,
        };
    }
}
