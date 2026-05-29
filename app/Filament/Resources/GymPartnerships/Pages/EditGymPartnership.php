<?php

namespace App\Filament\Resources\GymPartnerships\Pages;

use App\Filament\Resources\GymPartnerships\GymPartnershipResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGymPartnership extends EditRecord
{
    protected static string $resource = GymPartnershipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
