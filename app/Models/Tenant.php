<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SaasPlan;
use App\Traits\HasPlanRestrictions;
use App\Services\SubscriptionService;

class Tenant extends Model
{
    use HasFactory;
    use HasPlanRestrictions;

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

   
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }


    public function scopeSuspended($query)
    {
        return $query->where('is_active', false);
    }

  
    public function subscription()
    {
        return $this->hasOne(TenantSubscription::class, 'tenant_id');
    }


    public function getPlanAttribute()
    {
        $sub = $this->subscription;
        return ($sub && $sub->isActive()) ? $sub->plan : null;
    }


    public function reachedLimit(string $type): bool
    {
        return app(SubscriptionService::class)->reachedLimit($this, $type);
    }

    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }

}
