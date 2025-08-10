<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BeachEvent extends Model
{
    protected $fillable = [
        'name',
        'description',
        'location',
        'latitude',
        'longitude',
        'type',
        'price',
        'start_time',
        'end_time',
        'capacity',
        'equipment_included',
        'image_url',
        'requirements',
        'is_active',
        'organizer_id'
    ];

    protected $casts = [
        'equipment_included' => 'array',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(BeachTicket::class);
    }
}
