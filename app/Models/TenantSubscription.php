<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TenantSubscription extends Model
{
    use HasFactory;
    protected $table = 'tenant_subscriptions';

    protected $fillable = [
        'tenant_id',
        'saas_plan_id',
        'stripe_subscription_id',
        'status',
        'trial_ends_at',
        'ends_at',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    /*
    |-----------------------------------
    | Relationships
    |-----------------------------------
    */

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function plan()
    {
        return $this->belongsTo(SaasPlan::class, 'saas_plan_id');
    }
    public function isActive(): bool
    {
        return in_array($this->status, ['active', 'trialing']) &&
            (is_null($this->ends_at) || $this->ends_at->isFuture());
    }
}
