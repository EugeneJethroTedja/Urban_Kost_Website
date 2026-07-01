<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'phone',
        'email_verified_at',
        'otp_verified',
        'is_active',
        'remember_token',
        'created_by',
    ];

    protected $hidden = ['remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_verified' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function otps()
    {
        return $this->hasMany(Otp::class, 'email', 'email');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function notifications()
    {
        return $this->hasMany(InAppNotification::class);
    }

    public function unreadNotifications()
    {
        return $this->hasMany(InAppNotification::class)->where('is_read', false);
    }

    public function hasRole(string $role): bool
    {
        return $this->role && $this->role->name === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->role && in_array($this->role->name, $roles);
    }

    public function isOwner(): bool { return $this->hasRole('owner'); }
    public function isAdmin(): bool  { return $this->hasRole('admin'); }
    public function isTenant(): bool { return $this->hasRole('tenant'); }
    public function isProspective(): bool { return $this->hasRole('prospective_tenant'); }

    public function getDashboardRoute(): string
    {
        return match($this->role?->name) {
            'owner'               => 'owner.dashboard',
            'admin'               => 'admin.dashboard',
            'tenant'              => 'tenant.dashboard',
            'prospective_tenant'  => 'prospective.dashboard',
            default               => 'home',
        };
    }
}
