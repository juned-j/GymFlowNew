<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Tenant;
use Filament\Panel;

#[Fillable([
    'name',
    'email',
    'stripe_customer_id',
    'password',
    'phone',
    'avatar',
    'is_super_admin',
    'provider_name',
    'provider_id',
    'status',
    'email_verified_at',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    public $timestamps = true;
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function roles()
    {
        return $this->hasMany(UserTenantRole::class, 'user_id');
    }
    public function isSuperAdmin(): bool
    {
        if ($this->is_super_admin) {
            return true;
        }
        return $this->roles()
            ->whereHas('role', fn($q) => $q->where('name', 'super_admin'))
            ->exists();
    }

    public function isTenantUser()
    {
        return $this->ownedTenant()->where('is_active', true)->exists();
    }

    public function ownedTenant()
    {
        return $this->hasOne(Tenant::class, 'owner_user_id');
    }
    public function getTenantId(): ?int
    {
        $ownedTenantId = $this->ownedTenant()->value('id');
        if ($ownedTenantId) {
            return (int) $ownedTenantId;
        }
        return $this->roles()
            ->whereNotNull('tenant_id')
            ->orderBy('id', 'asc')
            ->value('tenant_id');
    }
    public function getTenantIds()
    {
        return $this->roles()
            ->pluck('tenant_id')
            ->unique()
            ->values();
    }
    public function memberProfile()
    {
        return $this->hasOne(Member::class);
    }

    public function trainerProfile()
    {
        return $this->hasOne(\App\Models\Trainer::class, 'user_id', 'id');
    }
    public function getTenantCurrencySymbol(): string
    {
        // Fetches the first available tenant role and gets the symbol
        // Based on your UserTenantRole model
        $role = $this->roles()->whereNotNull('tenant_id')->with('tenant')->first();
        return $role?->tenant?->currency_symbol ?? '$';
    }

    public function getTenantCurrencyCode(): string
    {
        $role = $this->roles()->whereNotNull('tenant_id')->with('tenant')->first();

        return $role?->tenant?->currency ?? 'USD';
    }
    public function tenantRoles()
    {
        return $this->hasMany(\App\Models\UserTenantRole::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function subscription()
    {
        return $this->hasOne(\App\Models\TenantSubscription::class, 'user_id');
    }
    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
    public function canAccessPanel(Panel $panel): bool
    {
        /*
    |--------------------------------------------------------------------------
    | PLATFORM PANEL
    |--------------------------------------------------------------------------
    */
        if ($panel->getId() === 'platform') {
            return $this->isSuperAdmin();
        }
        /*
    |--------------------------------------------------------------------------
    | ADMIN PANEL
    |--------------------------------------------------------------------------
    */
        if ($panel->getId() === 'admin') {
            // BLOCK super admins from admin panel
            if ($this->isSuperAdmin()) {
                return false;
            }
            return $this->roles()
                ->whereNotNull('tenant_id')
                ->exists();
        }
        return false;
    }
}
