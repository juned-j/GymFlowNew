<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Member;
use App\Traits\BelongsToTenant;

class Branch extends Model
{
    use HasFactory;
     use BelongsToTenant;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'country',
        'postal_code',
        'latitude',
        'longitude',
        'is_main',
    ];

//     protected static function booted()
// {
//     static::creating(function ($branch) {
//         if (! $branch->tenant_id) {
//             $user = auth()->user();

//             $branch->tenant_id = $user?->roles()
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
    /**
     * Get the tenant that owns the branch.
     * * Essential for your multi-tenant scoping.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the members associated with this specific branch.
     */
    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    /**
     * Get the classes scheduled at this branch.
     */
    public function Classes(): HasMany
    {
        return $this->hasMany(Classes::class);
    }
    public function getEffectiveCurrencyAttribute(): string
    {
        // Returns branch currency if set, otherwise tenant currency
        return $this->currency ?? $this->tenant->currency;
    }

    public function getEffectiveSymbolAttribute(): string
    {
        return $this->currency_symbol ?? $this->tenant->currency_symbol;
    }
    public function country(): BelongsTo
{
    return $this->belongsTo(Country::class);
}
}
