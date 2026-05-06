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

    // -------------------
    // RELATIONS (UNCHANGED)
    // -------------------

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function memberProfile()
    {
        return $this->hasOne(Member::class, 'user_id', 'id');
    }

   protected static function booted()
{
    static::creating(function ($member) {
        if (! $member->tenant_id) {
            $member->tenant_id = auth()->user()?->getTenantId();
        }
    });

    static::addGlobalScope('tenant', function ($query) {
        if (auth()->check()) {
            $user = auth()->user();
            
          
            if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
                return;
            }

            $tenantId = $user->getTenantId();
            if ($tenantId) {
                $query->where('members.tenant_id', $tenantId);
            } else {
           
                $query->whereRaw('1 = 0');
            }
        }
    });
}
}