<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class WorkoutPlan extends Model
{

use BelongsToTenant;
    protected $fillable = [
        'tenant_id',
        'name',
        'goal_type',
        'difficulty',
    ];

    public function workouts()
    {
        return $this->hasMany(Workout::class);
    }

    public function memberPlans()
    {
        return $this->hasMany(MemberWorkoutPlan::class);
    }
}
