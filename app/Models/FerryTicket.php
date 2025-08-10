<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FerryTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ferry_id',
        'hotel_booking_id',
        'travel_date',
        'passengers',
        'passenger_names',
        'total_price',
        'status',
        'payment_status',
        'booking_reference',
    ];

    protected $casts = [
        'travel_date' => 'date',
        'passenger_names' => 'array',
        'total_price' => 'decimal:2',
    ];

    /**
     * Get the user that owns the ferry ticket.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the ferry associated with the ticket.
     */
    public function ferry()
    {
        return $this->belongsTo(Ferry::class);
    }

    /**
     * Get the hotel booking associated with the ferry ticket.
     */
    public function hotelBooking()
    {
        return $this->belongsTo(HotelBooking::class);
    }
}
