<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    protected $fillable = [
        'workout_plan_id',
        'name',
        'day_number',
    ];

    public function exercises()
    {
        return $this->hasMany(WorkoutExercise::class);
    }

    public function workoutPlan()
    {
        return $this->belongsTo(WorkoutPlan::class, 'workout_plan_id');
    }
}
