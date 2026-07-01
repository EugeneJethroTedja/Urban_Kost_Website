<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'user_id',
        'room_id',
        'waiting_list_id',  
        'booking_id',
        'renewal_id',
        'invoice_type',    
        'month_number',
        'base_amount',
        'service_amount',
        'total_amount',
        'tax_amount',
        'period_start',
        'period_end',
        'due_date',
        'status',
        'paid_at',
        'notes',
        'pending_tenant_activation',
    ];

    protected $casts = [
        'period_start'              => 'date',
        'period_end'                => 'date',
        'due_date'                  => 'date',
        'paid_at'                   => 'datetime',
        'pending_tenant_activation' => 'boolean',
        'base_amount'               => 'decimal:2',
        'service_amount'            => 'decimal:2',
        'total_amount'              => 'decimal:2',
        'tax_amount'                => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($invoice) {
            if (!$invoice->invoice_number) {
                $invoice->invoice_number = 'INV-' . date('Ym') . '-' . strtoupper(Str::random(6));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function renewal()
    {
        return $this->belongsTo(Renewal::class);
    }
    
    public function waitingList()
    {
        return $this->belongsTo(WaitingList::class, 'waiting_list_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'unpaid'               => '<span class="badge bg-warning text-dark">Belum Bayar</span>',
            'pending_verification' => '<span class="badge bg-info">Menunggu Verifikasi</span>',
            'paid'                 => '<span class="badge bg-success">Lunas</span>',
            'overdue'              => '<span class="badge bg-danger">Terlambat</span>',
            'cancelled'            => '<span class="badge bg-secondary">Dibatalkan</span>',
            default                => '<span class="badge bg-light text-dark">-</span>',
        };
    }

    public function getDaysUntilDueAttribute(): int
    {
        return (int) now()->diffInDays($this->due_date, false);
    }

    public function isOverdue(): bool
    {
        return $this->status === 'unpaid'
            && $this->due_date
            && $this->due_date->isPast();
    }

    public function isWaitingListInvoice(): bool
    {
        return in_array($this->invoice_type, ['waiting_list_dp', 'waiting_list_remaining']);
    }
}
