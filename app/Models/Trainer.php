<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    protected $table = 'trainers';
    protected $fillable = [
        'user_id',
        'specialization',
        'bio',
        'status',
        'branch_id',
        'role_id',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
