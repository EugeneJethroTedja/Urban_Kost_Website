<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InAppNotification extends Model
{
    protected $table = 'in_app_notifications';

    protected $fillable = [
        'user_id','type','title','body','action_url','data','is_read','read_at',
    ];

    protected $casts = [
        'data'     => 'array',
        'is_read'  => 'boolean',
        'read_at'  => 'datetime',
    ];

    public function user() { return $this->belongsTo(User::class); }

    public static function send(int $userId, string $type, string $title, string $body, ?string $actionUrl = null, array $data = []): self
    {
        return static::create([
            'user_id'    => $userId,
            'type'       => $type,
            'title'      => $title,
            'body'       => $body,
            'action_url' => $actionUrl,
            'data'       => $data,
            'is_read'    => false,
        ]);
    }
}
