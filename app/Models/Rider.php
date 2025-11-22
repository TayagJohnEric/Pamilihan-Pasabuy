<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rider extends Model
{
    protected $table = 'riders';

    protected $fillable = [
        'user_id',
        'average_rating',
        'total_deliveries',
        'daily_deliveries',
        'is_available',
        'verification_status',
        'license_number',
        'vehicle_type',
        'gcash_number',
        'gcash_qr_path',
        'gcash_name',
        'selfie_verification_url',
        'current_latitude',
        'current_longitude',
        'location_last_updated_at',
    ];

    protected $casts = [
        'average_rating' => 'decimal:2',
        'is_available' => 'boolean',
        'verification_status' => 'string',
        'current_latitude' => 'decimal:7',
        'current_longitude' => 'decimal:7',
        'location_last_updated_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ratings()
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

    public function ratingsReceived()
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

    /**
     * Check if rider has incomplete GCash information
     * Returns true if any of the GCash fields are missing
     */
    public function hasIncompleteGCashInfo()
    {
        return empty($this->gcash_number);
    }
}
