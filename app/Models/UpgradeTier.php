<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MemberNetworkAccess;

class UpgradeTier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'access_level',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Subscriptions using this tier
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Membership plans linked to this tier
     */
    public function membershipPlans()
    {
        return $this->hasMany(MembershipPlan::class);
    }

    /**
     * Member network access records
     */
    public function memberNetworkAccesses()
    {
        return $this->hasMany(MemberNetworkAccess::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeOffPeak($query)
    {
        return $query->where('access_level', 'off_peak');
    }

    public function scopeDaytime($query)
    {
        return $query->where('access_level', 'daytime');
    }

    public function scopeFull($query)
    {
        return $query->where('access_level', 'full');
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function isOffPeak(): bool
    {
        return $this->access_level === 'off_peak';
    }

    public function isDaytime(): bool
    {
        return $this->access_level === 'daytime';
    }

    public function isFull(): bool
    {
        return $this->access_level === 'full';
    }
    
}