<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SaasPlan;
use App\Traits\HasPlanRestrictions;


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
    public function subscription()
    {
        return $this->hasOne(TenantSubscription::class);
    }



public function plan()
{
    return $this->hasOneThrough(
        \App\Models\SaasPlan::class,
        \App\Models\TenantSubscription::class,
        'tenant_id',      // FK on tenant_subscriptions
        'id',             // PK on saas_plans
        'id',             // PK on tenants
        'saas_plan_id'    // FK on tenant_subscriptions
    );
}


public function reachedLimit(string $type): bool
{
    $plan = $this->plan;

    if (!$plan) {
        return true; 
    }

    $limit = $plan->{"max_{$type}"} ?? null;

    if ($limit === null) {
        return true; 
    }

    if ($limit == 0) {
        return false;
    }

    $count = match ($type) {
        'members'  => \App\Models\Member::where('tenant_id', $this->id)->count(),
        'trainers' => \App\Models\Trainer::where('tenant_id', $this->id)->count(),
        'branches' => \App\Models\Branch::where('tenant_id', $this->id)->count(),
        default => 0,
    };

    return $count >= (int) $limit;
}
}
