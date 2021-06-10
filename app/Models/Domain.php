<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Laravel\Jetstream\Jetstream;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Domain extends Model
{
    use HasFactory;

    protected $casts = [
        'expired_at' => 'date',
    ];

    protected $guarded = [];

    public function team()
    {
        return $this->belongsTo(Jetstream::teamModel());
    }
}
