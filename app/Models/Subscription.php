<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tenant;
use App\Models\User;
use App\Models\MembershipPlan;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\BelongsToTenant;
class Subscription extends Model
{


 use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'membership_plan_id',
        'stripe_subscription_id', // Useful for Stripe integration
        'status',                 // active, trialing, past_due, canceled, expired
        'trial_ends_at',
        'ends_at',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'trial_ends_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    /**
     * The Gym (Tenant) this subscription belongs to.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * The Member (User) who owns this subscription.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The Plan assigned to this subscription.
     */
    public function membershipPlan(): BelongsTo
    {
        return $this->belongsTo(MembershipPlan::class);
    }

    /**
     * Helper to check if the subscription is currently valid.
     */
    public function isActive(): bool
    {
        return in_array($this->status, ['active', 'trialing']) &&
            (is_null($this->ends_at) || $this->ends_at->isFuture());
    }
}
