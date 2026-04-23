<?php

namespace App\Filament\Resources\DietPlans\Pages;

use App\Filament\Resources\DietPlans\DietPlanResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;


class CreateDietPlan extends CreateRecord
{
    protected static string $resource = DietPlanResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
{
    $user = auth()->user();

    if (! $user) {
        Notification::make()
            ->title('Error')
            ->body('User not logged in')
            ->danger()
            ->send();

        $this->halt();
    }

    $tenantId = $user->roles()
        ->whereHas('role', fn ($q) => $q->where('name', 'owner'))
        ->whereNotNull('tenant_id')
        ->value('tenant_id');

    if (! $tenantId) {
        Notification::make()
            ->title('Tenant Missing')
            ->body('Tenant not found for this user')
            ->danger()
            ->send();

        $this->halt();
    }

    $data['tenant_id'] = $tenantId;
    $data['trainer_id'] = $user->id;

    return $data;
}
}
