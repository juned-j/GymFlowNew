<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
class Exercise extends Model
{

use BelongsToTenant;
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
