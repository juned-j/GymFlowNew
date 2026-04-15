<?php

namespace App\Filament\Platform\Resources\Tenants\Pages;

use App\Filament\Platform\Resources\Tenants\TenantResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\User;
use App\Models\UserTenantRole;
use App\Models\Branch;
use Illuminate\Support\Facades\Hash;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    protected ?User $ownerUser = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 1. Create Owner User FIRST
        $this->ownerUser = User::create([
            'name' => $data['owner_name'],
            'email' => $data['owner_email'],
            'password' => Hash::make($data['owner_password']),
        ]);

        // 2. Attach owner to tenant
        $data['owner_user_id'] = $this->ownerUser->id;

        return $data;
    }

    protected function afterCreate(): void
    {
        $tenant = $this->record;

        // 3. Create Role Mapping
        UserTenantRole::create([
            'user_id' => $this->ownerUser->id,
            'tenant_id' => $tenant->id,
            'role_id' => 2,
        ]);
    }
}
