<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ServiceCatalog extends Model
{
    protected $table = 'service_catalog';

    protected $fillable = [
        'key', 'label', 'price', 'icon', 'sort_order',
        'description', 'is_active',
    ];

    protected $casts = [
        'price'      => 'integer',
        'is_active'  => 'boolean',
        'sort_order' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function getKeyValueAttribute(): string
    {
        if (!empty($this->attributes['key'])) {
            return $this->attributes['key'];
        }
        $name = strtolower($this->attributes['name'] ?? 'service_' . $this->id);
        return preg_replace('/_+/', '_', preg_replace('/[^a-z0-9_]/', '_', $name));
    }

    public function getLabelValueAttribute(): string
    {
        return $this->attributes['label']
            ?? $this->attributes['name']
            ?? '';
    }

    public function getPriceValueAttribute(): int
    {
        return (int) ($this->attributes['price']
            ?? $this->attributes['monthly_price']
            ?? 0);
    }

    public function getLabelWithIconAttribute(): string
    {
        $icon  = $this->attributes['icon'] ?? '';
        $label = $this->getLabelValueAttribute();
        return trim($icon . ' ' . $label);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->getPriceValueAttribute(), 0, ',', '.');
    }

    public static function toBookingArray(): array
    {
        return static::active()->ordered()->get()
            ->mapWithKeys(function ($svc) {
                $key   = $svc->key_value;
                $icon  = $svc->attributes['icon'] ?? '';
                $label = $svc->label_value;
                $price = $svc->price_value;

                return [
                    $key => [
                        'label' => trim($icon . ' ' . $label),
                        'price' => $price,
                    ],
                ];
            })
            ->toArray();
    }

    public static function validKeys(): array
    {
        return static::active()->ordered()->get()
            ->map(fn($svc) => $svc->key_value)
            ->filter()
            ->values()
            ->toArray();
    }

    public function getAttribute($key)
    {
        if ($key === 'key') {
            return $this->key_value;
        }
        if ($key === 'label') {
            return $this->label_value;
        }
        if ($key === 'price') {
            return $this->price_value;
        }

        return parent::getAttribute($key);
    }
}
