<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotResponse extends Model
{
    protected $fillable = ['category', 'keywords', 'response', 'is_active', 'priority'];

    protected $casts = ['keywords' => 'array', 'is_active' => 'boolean'];
}
