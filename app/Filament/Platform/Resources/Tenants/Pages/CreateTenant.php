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

        /*
    |--------------------------------------------------------------------------
    | 1. CREATE OWNER USER
    |--------------------------------------------------------------------------
    */
        $owner = User::create([
            'name' => $data['owner_name'],
            'email' => $data['owner_email'],
            'password' => Hash::make($data['owner_password']),
        ]);

        /*
    |--------------------------------------------------------------------------
    | 2. LINK OWNER TO TENANT
    |--------------------------------------------------------------------------
    */
        $tenant->update([
            'owner_user_id' => $owner->id,
        ]);

        /*
    |--------------------------------------------------------------------------
    | 3. USER ROLE (OWNER GLOBAL ACCESS)
    |--------------------------------------------------------------------------
    */
        UserTenantRole::create([
            'user_id' => $owner->id,
            'tenant_id' => $tenant->id,
            'role' => 'owner',
            'branch_id' => null, // IMPORTANT → full access
        ]);

        /*
    |--------------------------------------------------------------------------
    | 4. DEFAULT BRANCH
    |--------------------------------------------------------------------------
    */
        Branch::create([
            'tenant_id' => $tenant->id,
            'name' => $data['branch_name'] ?? 'Main Branch',
            'address_line_1' => $data['address_line_1'] ?? null,
            'address_line_2' => $data['address_line_2'] ?? null,
            'city' => $data['city'] ?? null,
            'state' => $data['state'] ?? null,
            'country' => $data['country'] ?? null,
            'postal_code' => $data['postal_code'] ?? null,
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'is_main' => true,
        ]);
    }
}
