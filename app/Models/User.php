<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the hotel bookings for the user.
     */
    public function hotelBookings()
    {
        return $this->hasMany(HotelBooking::class);
    }

    /**
     * Get the ferry tickets for the user.
     */
    public function ferryTickets()
    {
        return $this->hasMany(FerryTicket::class);
    }

    /**
     * Get the park tickets for the user.
     */
    public function parkTickets()
    {
        return $this->hasMany(ParkTicket::class);
    }

    /**
     * Get the activity tickets for the user.
     */
    public function activityTickets()
    {
        return $this->hasMany(ActivityTicket::class);
    }

    /**
     * Get the beach tickets for the user.
     */
    public function beachTickets()
    {
        return $this->hasMany(BeachTicket::class);
    }

    /**
     * Check if user has an active hotel booking.
     */
    public function hasActiveHotelBooking()
    {
        return $this->hotelBookings()->where('status', 'confirmed')->exists();
    }

    /**
     * Check if user has a valid hotel booking.
     */
    public function hasValidHotelBooking()
    {
        return $this->hotelBookings()->where('status', 'confirmed')->exists();
    }

    /**
     * Get owned hotels (for hotel owners).
     */
    public function ownedHotels()
    {
        return $this->hasMany(Hotel::class, 'owner_id');
    }

    /**
     * Get owned ferries (for ferry operators).
     */
    public function ownedFerries()
    {
        return $this->hasMany(Ferry::class, 'operator_id');
    }

    /**
     * Get owned theme parks (for park owners).
     */
    public function ownedThemeParks()
    {
        return $this->hasMany(ThemePark::class, 'owner_id');
    }

    /**
     * Get owned beach events (for event organizers).
     */
    public function ownedBeachEvents()
    {
        return $this->hasMany(BeachEvent::class, 'organizer_id');
    }
}
