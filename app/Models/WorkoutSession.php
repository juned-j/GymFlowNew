<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutSession extends Model
{
    protected $fillable = [
        'tenant_id',
        'user_id',
        'workout_id',
        'start_time',
        'end_time',
        'status',
    ];

    public function logs()
    {
        return $this->hasMany(ExerciseLog::class);
    }

    public function workout()
    {
        return $this->belongsTo(Workout::class);
    }
}
