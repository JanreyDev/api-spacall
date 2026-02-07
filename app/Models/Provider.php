<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Provider extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'type',
        'verification_status',
        'verified_by',
        'rejection_reason',
        'verified_at',
        'business_name',
        'business_registration_number',
        'business_hours',
        'commission_rate',
        'total_earnings',
        'average_rating',
        'total_reviews',
        'total_bookings',
        'completed_bookings',
        'is_active',
        'is_available',
        'is_accepting_bookings',
        'accepts_home_service',
        'accepts_store_service',
        'uuid',
    ];

    protected $casts = [
        'business_hours' => 'array',
        'commission_rate' => 'decimal:2',
        'total_earnings' => 'decimal:2',
        'average_rating' => 'decimal:2',
        'is_active' => 'boolean',
        'is_available' => 'boolean',
        'is_accepting_bookings' => 'boolean',
        'accepts_home_service' => 'boolean',
        'accepts_store_service' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function therapistProfile(): HasMany
    {
        return $this->hasMany(TherapistProfile::class);
    }

    public function storeProfile(): HasMany
    {
        return $this->hasMany(StoreProfile::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ProviderDocument::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(ProviderLocation::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'provider_services')->withPivot('price','is_available');
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
}
