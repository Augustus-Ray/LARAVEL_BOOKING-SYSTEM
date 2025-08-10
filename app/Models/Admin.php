<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'created_by',
        'business_type',
        'business_name',
        'permissions',
        'business_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'permissions' => 'array',
        ];
    }

    public function createdBy()
    {
        return $this->belongsTo(SuperAdmin::class, 'created_by');
    }

    public function hasPermission($permission)
    {
        if (!$this->permissions) {
            return false;
        }
        return in_array($permission, $this->permissions);
    }

    public function canManageBookingType($type)
    {
        // System admins can manage all types
        if ($this->business_type === 'system' || $this->business_type === 'general') {
            return true;
        }
        
        // Direct match for business type
        return $this->business_type === $type;
    }

    public function getBusinessNameAttribute()
    {
        switch ($this->business_type) {
            case 'hotel':
                return $this->business_id ? \App\Models\Hotel::find($this->business_id)?->name : 'All Hotels';
            case 'ferry':
                return $this->business_id ? \App\Models\Ferry::find($this->business_id)?->name : 'All Ferries';
            case 'theme_park':
                return $this->business_id ? \App\Models\ThemePark::find($this->business_id)?->name : 'All Theme Parks';
            case 'beach_event':
                return $this->business_id ? \App\Models\BeachEvent::find($this->business_id)?->name : 'All Beach Events';
            default:
                return 'General Admin';
        }
    }
}
