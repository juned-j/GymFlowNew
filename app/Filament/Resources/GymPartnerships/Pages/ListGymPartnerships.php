<?php

namespace App\Filament\Resources\GymPartnerships\Pages;

use App\Filament\Resources\GymPartnerships\GymPartnershipResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGymPartnerships extends ListRecords
{
    protected static string $resource = GymPartnershipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
