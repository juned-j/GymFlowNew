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
    protected function afterCreate(): void
    {
        $tenant = $this->record;
        $data = $this->form->getRawState();

        // 1. CREATE OWNER USER
        $owner = User::create([
            'name' => $data['owner_name'],
            'email' => $data['owner_email'],
            'password' => Hash::make($data['owner_password']),
        ]);

        // 2. LINK OWNER TO TENANT
        $tenant->update([
            'owner_user_id' => $owner->id,
        ]);

        // 3. CREATE ROLE ENTRY
        UserTenantRole::create([
            'user_id' => $owner->id,
            'tenant_id' => $tenant->id,
            'role' => 'owner',
        ]);
    }
}
