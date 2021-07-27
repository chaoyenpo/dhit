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
        'domain_expired_at' => 'date',
        'certificate_expired_at' => 'date',
    ];

    protected $guarded = [];

    public function team()
    {
        return $this->belongsTo(Jetstream::teamModel());
    }
}
