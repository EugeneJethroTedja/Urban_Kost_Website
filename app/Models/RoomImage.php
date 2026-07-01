<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomImage extends Model
{
    protected $fillable = ['room_id', 'image_path', 'sort_order', 'is_primary'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }
}
