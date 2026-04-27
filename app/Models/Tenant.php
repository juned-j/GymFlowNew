<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $table = 'tenants';

    protected $fillable = [
        // Identity
        'name',
        'slug',
        'logo_url',

        // Ownership
        'owner_user_id',

        // Contact
        'email',
        'phone',

        // Location
        'address',
        'city',
        'country',

        // SaaS config
        'timezone',
        'currency',
        'currency_symbol',

        // Status
        'status',
        'is_active',
        'trial_ends_at',
        'app_settings',
    ];

    /**
     * Owner of the tenant (gym owner)
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    /**
     * Scope: Active tenants
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Suspended tenants
     */
    public function scopeSuspended($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Check if tenant is active
     */
    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }
    protected $casts = [
        'app_settings' => 'array',
    ];
}
