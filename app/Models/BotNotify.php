<?php

namespace App\Models;

use App\Models\Bot;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BotNotify extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'chat' => 'array',
    ];

    public function bot()
    {
        return $this->belongsTo(Bot::class);
    }
}
