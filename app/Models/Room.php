<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Room extends Model
{
    protected $fillable = [
        'room_number', 'room_name', 'price', 'size',
        'floor', 'status', 'description', 'facilities', 'image_path',
    ];

    protected $casts = [
        'facilities' => 'array',
        'price'      => 'float',
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'room_id');
    }

    public function waitingLists(): HasMany
    {
        return $this->hasMany(WaitingList::class, 'room_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(RoomImage::class)->orderBy('sort_order');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(RoomImage::class)
            ->where('is_primary', true)
            ->orderBy('sort_order');
    }

    public function activeTenant(): HasOne
    {
        return $this->hasOne(Invoice::class, 'room_id')
            ->where('status', 'paid')
            ->where('period_end', '>=', now()->startOfDay())
            ->whereNull('waiting_list_id')
            ->orderByDesc('period_end')
            ->with('user');
    }

    public function getPrimaryImageUrlAttribute(): ?string
    {
        if ($this->relationLoaded('images')) {
            $primary = $this->images->where('is_primary', true)->first()
                ?? $this->images->first();

            if ($primary) {
                return asset('storage/' . $primary->image_path);
            }
        }

        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }

        return null;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'available'   => '<span class="badge bg-success">Tersedia</span>',
            'occupied'    => '<span class="badge bg-danger">Terisi</span>',
            'reserved'    => '<span class="badge bg-warning text-dark">Dipesan</span>',
            'maintenance' => '<span class="badge bg-secondary">Perbaikan</span>',
            default       => '<span class="badge bg-light text-dark">Unknown</span>',
        };
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }
}
