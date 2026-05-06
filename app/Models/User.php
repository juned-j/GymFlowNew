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
class User extends Authenticatable implements MustVerifyEmail{
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
        ->whereHas('role', fn ($q) => $q->where('name', 'super_admin'))
        ->exists();
}

  public function isTenantUser(): bool
{
    $tenantId = session('tenant_id');

    return $this->roles()
        ->where('tenant_id', $tenantId)
        ->exists();
}
  public function getTenantId(): ?int
{
    $tenantId = session('tenant_id');

    if ($tenantId) {
        return $tenantId;
    }

    return $this->roles()
        ->whereNotNull('tenant_id')
        ->latest('id')
        ->value('tenant_id');
}
    public function getTenantIds()
    {
        return $this->roles()
            ->pluck('tenant_id')
            ->unique()
            ->values();
    }
    public function currentTenantRole()
{
    return $this->roles()
        ->where('tenant_id', session('tenant_id'))
        ->first();
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
}
