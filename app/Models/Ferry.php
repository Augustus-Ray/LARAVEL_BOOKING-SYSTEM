<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ferry extends Model
{
    protected $fillable = [
        'name',
        'departure_location',
        'arrival_location',
        'departure_time',
        'arrival_time',
        'capacity',
        'price',
        'operating_days',
        'is_active',
        'operator_id'
    ];

    protected $casts = [
        'operating_days' => 'array',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(FerryTicket::class);
    }
}
