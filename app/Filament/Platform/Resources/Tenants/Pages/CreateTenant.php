<?php

namespace App\Filament\Platform\Resources\Tenants\Pages;

use App\Filament\Platform\Resources\Tenants\TenantResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Branch;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;
    protected function afterCreate(): void
    {
        $tenant = $this->record;
        $data = $this->form->getRawState();

        /**
         * 1. Create DEFAULT BRANCH (FULL SCHEMA)
         */
        \App\Models\Branch::create([
            'tenant_id'      => $tenant->id,
            'name'           => $data['branch_name'] ?? 'Main Branch',

            'address_line_1' => $data['address_line_1'] ?? null,
            'address_line_2' => $data['address_line_2'] ?? null,
            'city'           => $data['city'] ?? null,
            'state'          => $data['state'] ?? null,
            'country'        => $data['country'] ?? null,
            'postal_code'    => $data['postal_code'] ?? null,
            'latitude'       => $data['latitude'] ?? null,
            'longitude'      => $data['longitude'] ?? null,

            'is_main'        => true,
        ]);

        /**
         * 2. Assign Owner Role (no Spatie needed)
         */
        \App\Models\UserTenantRole::updateOrCreate([
            'user_id'   => $data['owner_user_id'],
            'tenant_id' => $tenant->id,
            'role'      => 'owner',
            'branch_id' => null, // IMPORTANT: owner is global inside tenant
        ]);
    }
}
