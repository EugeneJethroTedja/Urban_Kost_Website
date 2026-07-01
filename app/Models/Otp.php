<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    public $timestamps = false;

    protected $fillable = ['email', 'otp', 'type', 'expires_at', 'is_used'];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used'    => 'boolean',
    ];

    const UPDATED_AT = null;

    public function isExpired(): bool
    {
        return now()->gt($this->expires_at);
    }

    public function isValid(): bool
    {
        return !$this->is_used && !$this->isExpired();
    }

    public static function invalidatePrevious(string $email, string $type = 'login'): void
    {
        static::where('email', $email)
            ->where('type', $type)
            ->where('is_used', false)
            ->update(['is_used' => true]);
    }
}
