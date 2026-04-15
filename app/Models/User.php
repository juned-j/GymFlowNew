<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'email',
    'password',
    'phone',
    'avatar',
    'is_super_admin',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;
    public $timestamps = false;
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
        return $this->hasMany(\App\Models\UserTenantRole::class);
    }
    public function isSuperAdmin(): bool
    {
        return $this->roles()
            ->whereHas('role', function ($q) {
                $q->where('name', 'super_admin');
            })
            ->exists();
    }

    public function isTenantUser(): bool
    {
        return $this->roles()
            ->whereHas('role', function ($q) {
                $q->whereRaw('LOWER(name) IN (?, ?)', ['owner', 'trainer']);
            })
            ->exists();
    }
    public function getTenantId(): ?int
    {
        return $this->roles()
            ->whereHas('role', function ($q) {
                $q->whereIn('name', ['owner', 'trainer']); // tenant-level roles
            })
            ->value('tenant_id');
    }
}
