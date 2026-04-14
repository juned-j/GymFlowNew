<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasFactory;

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
        return $this->hasMany(MemberProfile::class);
    }

    /**
     * Get the classes scheduled at this branch.
     */
    public function gymClasses(): HasMany
    {
        return $this->hasMany(GymClass::class);
    }
}
