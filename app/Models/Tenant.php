<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasPlanRestrictions;

class Tenant extends Model
{
    use HasFactory, HasPlanRestrictions;

    protected $table = 'tenants';

    protected $fillable = [
        'name',
        'slug',
        'logo_url',
        'owner_user_id',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'timezone',
        'currency',
        'currency_symbol',
        'status',
        'is_active',
        'trial_ends_at',
        'app_settings',
    ];

    protected $casts = [
        'app_settings' => 'array',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function subscription()
    {
        return $this->hasOne(TenantSubscription::class);
    }

    public function plan()
    {
        return $this->belongsTo(\App\Models\SaasPlan::class, 'plan_id');
    }

    /**
     * FINAL SAFE LIMIT CHECK
     */
    public function reachedLimit(string $type): bool
    {
        $rawLimit = $this->plan?->{"max_{$type}"} ?? null;

        // 🔥 CLEAN LIMIT (handles int + string + dirty values)
        if ($rawLimit === null) {
            return false;
        } elseif (is_numeric($rawLimit)) {
            $limit = (int) $rawLimit;
        } else {
            $clean = preg_replace('/[^0-9]/', '', $rawLimit);
            $limit = $clean !== '' ? (int) $clean : 0;
        }

        // unlimited
        if ($limit === 0) {
            return false;
        }

        // 🔥 GLOBAL COUNT (since no tenant_id)
        $count = match ($type) {
            'members'  => \App\Models\Member::count(),
            'trainers' => \App\Models\Trainer::count(),
            'branches' => \App\Models\Branch::count(),
            default => 0,
        };

        return $count >= $limit;
    }
}