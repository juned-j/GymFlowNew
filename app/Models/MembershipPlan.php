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
        'has_trainer_support'
    ];
}
