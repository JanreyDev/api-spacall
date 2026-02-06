<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TherapistProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'specializations',
        'bio',
        'years_of_experience',
        'certifications',
        'languages_spoken',
        'professional_photo_url',
        'license_number',
        'license_type',
        'license_expiry_date',
        'base_rate',
        'service_radius_km',
        'base_location_latitude',
        'base_location_longitude',
        'base_address',
        'default_schedule',
        'has_own_equipment',
        'equipment_list',
    ];

    protected $casts = [
        'specializations' => 'array',
        'certifications' => 'array',
        'languages_spoken' => 'array',
        'default_schedule' => 'array',
        'has_own_equipment' => 'boolean',
        'equipment_list' => 'array',
        'base_rate' => 'decimal:2',
        'license_expiry_date' => 'date',
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class);
    }
}
