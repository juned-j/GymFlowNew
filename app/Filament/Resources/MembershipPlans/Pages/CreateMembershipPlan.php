<?php

namespace App\Filament\Resources\MembershipPlans\Pages;

use App\Filament\Resources\MembershipPlans\MembershipPlanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMembershipPlan extends CreateRecord
{
    protected static string $resource = MembershipPlanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Use your existing User helper to get the current tenant ID
        $data['tenant_id'] = auth()->user()->getTenantId();

        return $data;
    }
      
}
