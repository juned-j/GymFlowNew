<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberWorkoutPlan extends Model
{
    protected $fillable = [
        'tenant_id',
        'user_id',
        'workout_plan_id',
        'start_date',
        'current_day',
        'is_active',
    ];

    public function plan()
    {
        return $this->belongsTo(WorkoutPlan::class);
    }
}
