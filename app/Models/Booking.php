<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    protected $fillable = [
        'user_id', 'room_id', 'booking_code', 'status',
        'booking_type',
        'renewal_responded_at',
        'check_in_date', 'duration_months', 'full_name',
        'phone', 'identity_number',
        'emergency_contact',
        'message',
        'cancellation_reason',
        'additional_services', 'service_amount',
        'admin_notes', 'reviewed_by', 'reviewed_at',
        'payment_method',
        'monthly_billing_day',
        'monthly_invoice_count',
        'renewal_id',
        'renewal_payment_method',
        'renewal_monthly_count',
        'renewal_duration',
        'renewal_period_start',
        'renewal_monthly_day',
        'renewal_service_monthly',
    ];

    protected $casts = [
        'check_in_date'           => 'date',
        'reviewed_at'             => 'datetime',
        'renewal_inquiry_sent_at' => 'datetime',
        'renewal_responded_at'    => 'datetime',
        'additional_services'     => 'array',
        'service_amount'          => 'decimal:2',
        'payment_method'          => 'string',
        'monthly_billing_day'     => 'integer',
        'monthly_invoice_count'   => 'integer',
        'renewal_monthly_count' => 'integer',
        'renewal_duration'      => 'integer',
        'renewal_monthly_day'   => 'integer',
        'renewal_service_monthly' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($booking) {
            if (!$booking->booking_code) {
                $booking->booking_code = 'BK-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            }
        });
    }

    public function user()           { return $this->belongsTo(User::class); }

    public function room()           { return $this->belongsTo(Room::class); }
    
    public function reviewer()       { return $this->belongsTo(User::class, 'reviewed_by'); }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
    
    public function activeRenewal()
    {
        return $this->belongsTo(\App\Models\Renewal::class, 'renewal_id');
    }

    public function waitingList()
    {
        return $this->hasOne(WaitingList::class);
    }

    public function scopePending($q)      { return $q->where('status', 'pending'); }
    public function scopeApproved($q)     { return $q->where('status', 'approved'); }
    public function scopeWaitingList($q)  { return $q->where('status', 'waiting_list'); }
    public function scopeAdvance($q)      { return $q->where('booking_type', 'advance'); }

    public function isAdvance(): bool     { return $this->booking_type === 'advance'; }
    public function isWaitingList(): bool { return $this->status === 'waiting_list'; }
    public function isApproved(): bool    { return $this->status === 'approved'; }
    public function isPending(): bool     { return $this->status === 'pending'; }

    public function isMonthly(): bool
    {
        return $this->payment_method === 'monthly';
    }

    public function isLumpSum(): bool
    {
        return $this->payment_method === 'lump_sum';
    }

    public function nextMonthlyInvoiceDue(): ?\Carbon\Carbon
    {
        if (!$this->isMonthly()) return null;
        if (!$this->check_in_date) return null;

        $nextInvoiceMonth = $this->monthly_invoice_count + 1;
        if ($nextInvoiceMonth > $this->duration_months) return null;

        $checkIn = \Carbon\Carbon::parse($this->check_in_date);

        return $checkIn->copy()->addMonths($nextInvoiceMonth - 1);
    }

    public function nextMonthlyInvoiceCreateDate(): ?\Carbon\Carbon
    {
        $due = $this->nextMonthlyInvoiceDue();
        if (!$due) return null;

        if ($this->monthly_invoice_count === 0) return now();

        return $due->copy()->subDays(5);
    }

    public function shouldCreateNextMonthlyInvoice(): bool
    {
        $createDate = $this->nextMonthlyInvoiceCreateDate();
        if (!$createDate) return false;

        return now()->gte($createDate);
    }

    public function hasMonthlyInvoiceForMonth(int $monthNumber): bool
    {
        return $this->invoices()
            ->where('invoice_type', 'monthly')
            ->where('month_number', $monthNumber)
            ->exists();
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending'      => '<span class="badge bg-secondary">Menunggu</span>',
            'under_review' => '<span class="badge bg-info text-white">Ditinjau</span>',
            'approved'     => '<span class="badge bg-success">Disetujui</span>',
            'waiting_list' => '<span class="badge bg-warning text-dark"> Waiting List</span>',
            'rejected'     => '<span class="badge bg-danger">Ditolak</span>',
            'cancelled'    => '<span class="badge bg-warning text-dark">Dibatalkan</span>',
            'completed'    => '<span class="badge bg-primary">Selesai</span>',
            default        => '<span class="badge bg-light text-dark">-</span>',
        };
    }

    public function getRenewalBadgeAttribute(): string
    {
        return match ($this->renewal_status) {
            'pending_inquiry' => '<span class="badge bg-warning text-dark" style="font-size:.65rem"> Menunggu Jawaban</span>',
            'will_renew'      => '<span class="badge bg-success" style="font-size:.65rem"> Perpanjang</span>',
            'wont_renew'      => '<span class="badge bg-info text-white" style="font-size:.65rem"> Siap Proses</span>',
            default           => '',
        };
    }

    public function getTypeBadgeAttribute(): string
    {
        if ($this->booking_type === 'advance') {
            return '<span class="badge bg-warning text-dark" style="font-size:.62rem"> ADVANCE</span>';
        }
        return '<span class="badge bg-primary bg-opacity-15 text-primary" style="font-size:.62rem">Regular</span>';
    }
}
