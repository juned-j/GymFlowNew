<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTenantRole extends Model
{
    protected $fillable = [
        'user_id',
        'tenant_id',
        'role',
        'branch_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant::class);
    }
    public function branch()
    {
        return $this->belongsTo(\App\Models\Branch::class);
    }
}
