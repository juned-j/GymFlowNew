<?php

namespace App\Filament\Platform\Resources\SaasPlans\Pages;

use App\Filament\Platform\Resources\SaasPlans\SaasPlanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSaasPlans extends ListRecords
{
    protected static string $resource = SaasPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
