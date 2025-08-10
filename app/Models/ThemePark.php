<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ThemePark extends Model
{
    protected $fillable = [
        'name',
        'description',
        'location',
        'latitude',
        'longitude',
        'entry_price',
        'opening_time',
        'closing_time',
        'operating_days',
        'capacity',
        'image_url',
        'is_active',
        'owner_id'
    ];

    protected $casts = [
        'operating_days' => 'array',
        'is_active' => 'boolean',
        'entry_price' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(ParkActivity::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(ParkTicket::class);
    }
}
