<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = [
        'tenant_id',
        'name',
        'muscle_group',
        'equipment',
        'video_url',
    ];

    public function workoutExercises()
    {
        return $this->hasMany(WorkoutExercise::class);
    }
}
