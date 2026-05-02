<?php

namespace App\Filament\Platform\Resources\Tenants\Pages;

use App\Filament\Platform\Resources\Tenants\TenantResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\User;
use App\Models\UserTenantRole;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    protected static bool $canCreateAnother = false;

    protected ?User $ownerUser = null;

    /**
     * Create owner user before tenant is created
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->ownerUser = User::create([
            'name' => $data['owner_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['owner_password']),
        ]);

        $data['owner_user_id'] = $this->ownerUser->id;

        return $data;
    }

    /**
     * After tenant creation → assign role mapping
     */
    protected function afterCreate(): void
    {
        $tenant = $this->record;

        // ✅ SAFE: fetch owner role
        $roleId = Role::where('name', 'owner')->value('id');

        // 🔥 auto-fallback (prevents future crash)
        if (! $roleId) {
            $roleId = Role::firstOrCreate(
                ['name' => 'owner'],
                ['scope' => 'tenant']
            )->id;
        }

        UserTenantRole::create([
            'user_id'   => $this->ownerUser->id,
            'tenant_id' => $tenant->id,
            'role_id'   => $roleId,
        ]);
    }

    /**
     * Remove default Filament buttons if needed
     */
    protected function getFormActions(): array
    {
        return [];
    }
}