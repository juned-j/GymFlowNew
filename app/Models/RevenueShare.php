<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevenueShare extends Model
{
    use HasFactory;

    protected $fillable = [
        'gym_visit_id',
        'from_tenant_id',
        'to_tenant_id',
        'amount',
        'platform_fee',
    ];

    protected $casts = [
        'amount'        => 'decimal:2',
        'platform_fee'  => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Gym visit associated with this revenue split
     */
    public function gymVisit()
    {
        return $this->belongsTo(GymVisit::class);
    }

    /**
     * Home gym / source tenant
     */
    public function fromTenant()
    {
        return $this->belongsTo(Tenant::class, 'from_tenant_id');
    }

    /**
     * Partner gym / destination tenant
     */
    public function toTenant()
    {
        return $this->belongsTo(Tenant::class, 'to_tenant_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where(function ($q) use ($tenantId) {
            $q->where('from_tenant_id', $tenantId)
              ->orWhere('to_tenant_id', $tenantId);
        });
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Net revenue after platform fee
     */
    public function getNetAmountAttribute(): float
    {
        return (float) $this->amount - (float) $this->platform_fee;
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Check if revenue share belongs to tenant
     */
    public function belongsToTenant($tenantId): bool
    {
        return $this->from_tenant_id == $tenantId
            || $this->to_tenant_id == $tenantId;
    }
}