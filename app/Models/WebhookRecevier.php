<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookRecevier extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'chat' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
