<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParkActivity extends Model
{
    protected $fillable = [
        'theme_park_id',
        'name',
        'description',
        'type',
        'price',
        'duration_minutes',
        'capacity_per_session',
        'min_age',
        'max_age',
        'requirements',
        'image_url',
        'is_active',
        'start_time',
        'end_time'
    ];

    protected $casts = [
        'requirements' => 'array',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function themePark(): BelongsTo
    {
        return $this->belongsTo(ThemePark::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(ActivityTicket::class);
    }
}
