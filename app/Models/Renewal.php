<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Renewal extends Model
{
    protected $fillable = [
        'booking_id',
        'user_id',
        'room_id',
        'duration_months',
        'amount',
        'status',
        'new_end_date',
        'current_end_date',       
        'service_amount_monthly',  
        'additional_services',    
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
        'approved_at',
        'payment_method',          
        'monthly_invoice_count', 
    ];

    protected $casts = [
        'new_end_date'           => 'date',
        'current_end_date'       => 'date',
        'reviewed_at'            => 'datetime',
        'approved_at'            => 'datetime',
        'amount'                 => 'decimal:2',
        'service_amount_monthly' => 'decimal:2',
        'additional_services'    => 'array',
        'monthly_invoice_count'  => 'integer',  
    ];

    public function booking(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function room(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function reviewer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function invoice(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Invoice::class, 'renewal_id');
    }

    public function payments(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            Payment::class,
            Invoice::class,
            'renewal_id',
            'invoice_id',
            'id',
            'id'
        );
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending_payment' => '<span class="badge bg-warning text-dark">Menunggu Pembayaran</span>',
            'pending_admin'   => '<span class="badge bg-info text-white">Menunggu Konfirmasi Admin</span>',
            'approved'        => '<span class="badge bg-success">Disetujui</span>',
            'rejected'        => '<span class="badge bg-danger">Ditolak</span>',
            'cancelled'       => '<span class="badge bg-secondary">Dibatalkan</span>',
            default           => '<span class="badge bg-secondary">-</span>',
        };
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->amount, 0, ',', '.');
    }

    public function isPendingPayment(): bool { return $this->status === 'pending_payment'; }
    public function isPendingAdmin(): bool   { return $this->status === 'pending_admin'; }
    public function isApproved(): bool       { return $this->status === 'approved'; }
}
