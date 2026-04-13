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

        // 1. Assign Owner Role to the user
        // Assuming you use Spatie Permission or a manual 'role' column
        $owner = $tenant->owner;
        $owner->assignRole('owner');

        // 2. Create Default Branch (Goal 2)
        Branch::create([
            'tenant_id' => $tenant->id,
            'name' => $data['branch_name'],
            'address' => $data['branch_address'],
        ]);

        // 3. Create Default Membership Plan (Goal 3)
        // MembershipPlan::create([
        //     'tenant_id' => $tenant->id,
        //     'name' => $data['plan_name'],
        //     'price' => $data['plan_price'],
        //     'is_active' => true,
        // ]);

        // 4. Setup Tenant Settings (Goal 4)
        // Store theme data or settings in your tenant record/metadata
        // $tenant->update([
        //     'primary_color' => $data['primary_color'],
        //     'settings' => [
        //         'notifications' => $data['enable_notifications'],
        //     ]
        // ]);
    }
}
