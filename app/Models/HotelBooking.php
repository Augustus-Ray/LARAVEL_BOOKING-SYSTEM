<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class HotelBooking extends Model
{
    protected $fillable = [
        'user_id',
        'hotel_id',
        'check_in',
        'check_out',
        'rooms',
        'guests',
        'total_price',
        'status',
        'payment_status',
        'special_requests',
        'booking_date'
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'total_price' => 'decimal:2',
        'booking_date' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function ferryTickets(): HasMany
    {
        return $this->hasMany(FerryTicket::class);
    }

    public function parkTickets(): HasMany
    {
        return $this->hasMany(ParkTicket::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'confirmed' && 
               Carbon::parse($this->check_in)->lte(now()) && 
               Carbon::parse($this->check_out)->gte(now());
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'confirmed')
                    ->whereDate('check_in', '<=', now())
                    ->whereDate('check_out', '>=', now());
    }
}
