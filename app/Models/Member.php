<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\BelongsToTenant;


class Member extends Model
{
    protected $table = 'members';
    use BelongsToTenant;

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
    protected $casts = [
        'injuries' => 'array',
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


    // protected static function booted()
    // {
    //     static::creating(function ($member) {
    //         if (! $member->tenant_id) {
    //             $user = auth()->user();

    //             $member->tenant_id = $user?->roles()
    //                 ->whereHas('role', fn($q) => $q->where('name', 'owner'))
    //                 ->value('tenant_id');
    //         }
    //     });

    //     static::addGlobalScope('tenant', function ($query) {
    //         if (auth()->check()) {
    //             $user = auth()->user();

    //             $tenantId = $user?->roles()
    //                 ->whereHas('role', fn($q) => $q->where('name', 'owner'))
    //                 ->value('tenant_id');

    //             if ($tenantId) {
    //                 $query->where('tenant_id', $tenantId);
    //             }
    //         }
    //     });
    // }
}
