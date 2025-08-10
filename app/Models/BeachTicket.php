<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BeachTicket extends Model
{
    protected $fillable = [
        'user_id',
        'beach_event_id',
        'participants',
        'total_price',
        'status',
        'payment_status',
        'ticket_reference',
        'special_requests'
    ];

    protected $casts = [
        'booking_date' => 'datetime',
        'total_price' => 'decimal:2'
    ];

    /**
     * Get the user that owns the beach ticket.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the beach event that this ticket is for.
     */
    public function beachEvent(): BelongsTo
    {
        return $this->belongsTo(BeachEvent::class);
    }
}
