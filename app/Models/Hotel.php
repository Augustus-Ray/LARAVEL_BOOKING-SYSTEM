<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hotel extends Model
{
    protected $fillable = [
        'name',
        'description',
        'location',
        'latitude',
        'longitude',
        'price_per_night',
        'total_rooms',
        'available_rooms',
        'amenities',
        'image_url',
        'contact_info',
        'is_active',
        'owner_id'
    ];

    protected $casts = [
        'amenities' => 'array',
        'is_active' => 'boolean',
        'price_per_night' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(HotelBooking::class);
    }

    public function activeBookings(): HasMany
    {
        return $this->hasMany(HotelBooking::class)->where('status', 'confirmed');
    }
}
