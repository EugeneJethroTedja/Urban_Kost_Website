<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaitingList extends Model
{
    protected $fillable = [
        'booking_id',
        'room_id',
        'user_id',
        'position',
        'status',
        'dp_amount',
        'dp_paid',
        'dp_paid_at',
        'dp_invoice_id',
        'remaining_invoice_id',
        'penalty_amount',
        'penalty_waived',
        'approved_by',
        'approved_at',
        'notified_at',
        'cancelled_at',
        'cancellation_reason',
        'converted_at',
        'notes',
    ];

    protected $casts = [
        'dp_paid_at'     => 'datetime',
        'approved_at'    => 'datetime',
        'notified_at'    => 'datetime',
        'cancelled_at'   => 'datetime',
        'converted_at'   => 'datetime',
        'penalty_waived' => 'boolean',
        'dp_amount'      => 'decimal:2',
        'dp_paid'        => 'decimal:2',
        'penalty_amount' => 'decimal:2',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function dpInvoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'dp_invoice_id');
    }

    public function remainingInvoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'remaining_invoice_id');
    }

    public function fullInvoice()
    {
        return $this->remainingInvoice();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', ['waiting', 'notified']);
    }

    public function scopeForRoom(Builder $query, int $roomId): Builder
    {
        return $query->where('room_id', $roomId);
    }

    public function isDpPaid(): bool
    {
        return ($this->dp_paid ?? 0) > 0 && $this->dp_paid >= $this->dp_amount;
    }

    public function isRemainingPaid(): bool
    {
        if (!$this->remaining_invoice_id) return true;
        return $this->remainingInvoice?->status === 'paid';
    }

    public function calculatePenalty(): float
    {
        if (!$this->isDpPaid()) return 0;
        return round(($this->dp_paid ?? 0) * 0.10, 2);
    }

    public function remainingAmount(): float
    {
        $total = ($this->room?->price ?? 0) * ($this->booking?->duration_months ?? 1);
        $paid  = $this->dp_paid ?? 0;
        return max(0, $total - $paid);
    }

    public function getActiveInvoice(): ?Invoice
    {
        if ($this->remaining_invoice_id) {
            $remaining = $this->remainingInvoice;
            if ($remaining && in_array($remaining->status, ['unpaid', 'overdue'])) {
                return $remaining;
            }
        }

        if ($this->dp_invoice_id) {
            $dp = $this->dpInvoice;
            if ($dp && in_array($dp->status, ['unpaid', 'overdue'])) {
                return $dp;
            }
        }

        return Invoice::where('waiting_list_id', $this->id)
            ->whereIn('status', ['unpaid', 'overdue'])
            ->latest()
            ->first();
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'waiting'   => '<span class="badge bg-warning text-dark"> Menunggu Kamar</span>',
            'notified'  => '<span class="badge bg-info text-white"> Kamar Tersedia</span>',
            'converted' => '<span class="badge bg-success"> Aktif sebagai Penghuni</span>',
            'cancelled' => '<span class="badge bg-secondary"> Dibatalkan</span>',
            'expired'   => '<span class="badge bg-dark"> Kadaluarsa</span>',
            default     => '<span class="badge bg-light text-dark">-</span>',
        };
    }

    public function getFormattedDpAttribute(): string
    {
        return 'Rp ' . number_format($this->dp_amount ?? 0, 0, ',', '.');
    }

    public function getFormattedPenaltyAttribute(): string
    {
        return 'Rp ' . number_format($this->penalty_amount ?? 0, 0, ',', '.');
    }

    public static function hasActiveForRoom(int $roomId): bool
    {
        return self::forRoom($roomId)->active()->exists();
    }

    public static function getActiveForRoom(int $roomId): ?self
    {
        return self::forRoom($roomId)->active()->with(['user', 'booking'])->first();
    }
}
