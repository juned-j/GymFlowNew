<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Member extends Model
{
    protected $table = 'members';
    protected $fillable = [
        'user_id',
        'tenant_id',
        'height',
        'weight',
        'bmi',
        'goal',
        'gender',
        'dob',
        'status',
        'age',
        'fitness_level',
        'activity_level',
        'injuries',
        'program_match',
        'branch_id',

    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function memberProfile()
    {
        return $this->hasOne(Member::class, 'user_id', 'id');
    }
}
