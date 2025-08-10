<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityTicket extends Model
{
    protected $fillable = [
        'user_id',
        'park_activity_id',
        'park_ticket_id',
        'scheduled_time',
        'participants',
        'total_price',
        'status',
        'payment_status',
        'activity_reference',
        'booking_date'
    ];

    protected $casts = [
        'scheduled_time' => 'datetime',
        'total_price' => 'decimal:2',
        'booking_date' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parkActivity(): BelongsTo
    {
        return $this->belongsTo(ParkActivity::class);
    }

    public function parkTicket(): BelongsTo
    {
        return $this->belongsTo(ParkTicket::class);
    }
}
