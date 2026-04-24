<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MembershipPlan extends Model
{
    protected $fillable = [
        'tenant_id',
        'name',
        'price',
        'billing_period',
        'workout_plan_limit',
        'stripe_product_id',
        'stripe_price_id',
      
        'has_trainer_support'
    ];
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'features' => 'array', // Crucial for the Repeater to work
            'has_trainer_support' => 'boolean',
        ];
    }
    public function subscriptions()
{
    return $this->hasMany(\App\Models\Subscription::class, 'membership_plan_id');
}
    protected static function booted()
{
    static::deleting(function ($plan) {
        $plan->subscriptions()->delete();
    });
}
}
