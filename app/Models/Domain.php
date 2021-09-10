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
        'nameservers' => 'array',
    ];

    protected $guarded = [];

    public function team()
    {
        return $this->belongsTo(Jetstream::teamModel());
    }

    public function scopeSearch($query, string $terms = null)
    {
        collect(explode(' ', $terms))->filter()->each(function ($term) use ($query) {
            $term = '%' . $term . '%';
            $query->where(function ($query) use ($term) {
                $query->where('name', 'like', $term);
            });
        });
    }
}
