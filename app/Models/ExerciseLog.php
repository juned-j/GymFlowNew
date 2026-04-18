<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExerciseLog extends Model
{
    protected $fillable = [
        'workout_session_id',
        'exercise_id',
        'set_number',
        'weight',
        'reps',
    ];

    public function session()
    {
        return $this->belongsTo(WorkoutSession::class);
    }

    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
