<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Member extends Model
{
    protected $fillable = [
        'user_id',
        'height',
        'weight',
        'goal',
        'gender',
        'dob',
        'status',
        'age',
        'fitness_level',
        'activity_level',
        'injuries',
        'program_match',

    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
