<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutExercise extends Model
{
    protected $fillable = [
        'workout_id',
        'exercise_id',
        'sets_target',
        'reps_target',
        'rest_seconds',
        'sort_order',
    ];

    public function workout()
    {
        return $this->belongsTo(Workout::class);
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
