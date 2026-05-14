<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Trainer extends Model
{
    protected $table = 'trainers';
    use BelongsToTenant;
    protected $fillable = [
        'user_id',
        'specialization',
        'bio',
        'status',
        'branch_id',
        'tenant_id',
    ];

    // -------------------
    // RELATION (UNCHANGED)
    // -------------------
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // -------------------
    // 🔥 TENANT ISOLATION FIX  fx 
    // -------------------
    // protected static function booted()
    // {
    //     // ✅ AUTO SET tenant_id on create (if column exists in table)
    //     static::creating(function ($trainer) {
    //         if (empty($trainer->tenant_id)) {
    //             $user = auth()->user();

    //             $trainer->tenant_id = $user?->roles()
    //                 ->whereHas('role', fn($q) => $q->where('name', 'owner'))
    //                 ->value('tenant_id');
    //         }
    //     });

    //     // 🔥 GLOBAL SCOPE (IMPORTANT)
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
