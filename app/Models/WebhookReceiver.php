<?php

namespace App\Models;

use App\Models\Bot;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WebhookReceiver extends Model
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

    public function bot()
    {
        return $this->belongsTo(Bot::class);
    }
}
