<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiderApplication extends Model
{
    protected $table = 'rider_applications';

    protected $fillable = [
        'full_name',
        'email',
        'contact_number',
        'birth_date',
        'address',
        'vehicle_type',
        'vehicle_model',
        'license_plate_number',
        'driver_license_number',
        'license_expiry_date',
        'nbi_clearance_url',
        'valid_id_url',
        'selfie_with_id_url',
        'tin_number',
        'gcash_number',
        'status',
        'admin_notes',
        'reviewed_by_user_id',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'license_expiry_date' => 'date',
        'status' => 'string',
    ];

    // Relationships
    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by_user_id');
    }

    // Helper methods to get full URLs for documents
    public function getNbiClearanceFullUrl()
    {
        return $this->nbi_clearance_url ? \Storage::url($this->nbi_clearance_url) : null;
    }

    public function getValidIdFullUrl()
    {
        return $this->valid_id_url ? \Storage::url($this->valid_id_url) : null;
    }

    public function getSelfieWithIdFullUrl()
    {
        return $this->selfie_with_id_url ? \Storage::url($this->selfie_with_id_url) : null;
    }
}
