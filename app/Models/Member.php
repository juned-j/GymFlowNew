<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'tenant_id',
        'branch_id',
        'user_id',
        'height',
        'weight',
        'goal',
        'gender',
        'dob',
        'status',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
