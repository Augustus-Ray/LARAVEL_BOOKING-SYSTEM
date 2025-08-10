<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Advertisement extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image_url',
        'link_url',
        'type',
        'position',
        'priority',
        'start_date',
        'end_date',
        'is_active',
        'clicks',
        'views',
        'advertiser_id'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function advertiser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'advertiser_id');
    }
}
