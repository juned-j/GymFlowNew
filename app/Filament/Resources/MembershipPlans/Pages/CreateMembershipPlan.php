<?php

namespace App\Filament\Resources\MembershipPlans\Pages;

use App\Filament\Resources\MembershipPlans\MembershipPlanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMembershipPlan extends CreateRecord
{
    protected static string $resource = MembershipPlanResource::class;
            protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Use your existing User helper to get the current tenant ID
        $data['tenant_id'] = auth()->user()->getTenantId();

        return $data;
    }
        protected function getFormActions(): array
{
    return [];
}
}
