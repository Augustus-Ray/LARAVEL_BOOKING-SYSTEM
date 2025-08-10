<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParkTicket extends Model
{
    protected $fillable = [
        'user_id',
        'theme_park_id',
        'hotel_booking_id',
        'visit_date',
        'visitors',
        'total_price',
        'status',
        'payment_status',
        'ticket_reference',
        'booking_date'
    ];

    protected $casts = [
        'visit_date' => 'date',
        'total_price' => 'decimal:2',
        'booking_date' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function themePark(): BelongsTo
    {
        return $this->belongsTo(ThemePark::class);
    }

    public function hotelBooking(): BelongsTo
    {
        return $this->belongsTo(HotelBooking::class);
    }

    public function activityTickets(): HasMany
    {
        return $this->hasMany(ActivityTicket::class);
    }
}
