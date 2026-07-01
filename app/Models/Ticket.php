<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_number','user_id','assigned_to','subject','description',
        'category','priority','status','attachment','resolved_at','rating',
    ];

    protected $casts = ['resolved_at' => 'datetime'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($t) {
            if (!$t->ticket_number) {
                $t->ticket_number = 'TKT-' . date('Ymd') . '-' . strtoupper(Str::random(5));
            }
        });
    }

    public function user()     { return $this->belongsTo(User::class); }
    public function assignee() { return $this->belongsTo(User::class, 'assigned_to'); }
    public function replies()  { return $this->hasMany(TicketReply::class); }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'open'        => '<span class="badge bg-danger">Open</span>',
            'in_progress' => '<span class="badge bg-primary">In Progress</span>',
            'pending'     => '<span class="badge bg-warning text-dark">Pending</span>',
            'resolved'    => '<span class="badge bg-success">Resolved</span>',
            'closed'      => '<span class="badge bg-secondary">Closed</span>',
            default       => '<span class="badge bg-light text-dark">-</span>',
        };
    }

    public function getPriorityBadgeAttribute(): string
    {
        return match($this->priority) {
            'urgent' => '<span class="badge bg-danger"> Urgent</span>',
            'high'   => '<span class="badge bg-warning text-dark"> High</span>',
            'normal' => '<span class="badge bg-info"> Normal</span>',
            'low'    => '<span class="badge bg-secondary"> Low</span>',
            default  => '<span class="badge bg-light text-dark">-</span>',
        };
    }

    public function getMttrHoursAttribute(): ?float
    {
        if (!$this->resolved_at) return null;
        return round($this->created_at->diffInMinutes($this->resolved_at) / 60, 1);
    }
}
