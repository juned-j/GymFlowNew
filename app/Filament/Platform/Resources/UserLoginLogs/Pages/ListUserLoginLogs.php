<?php

namespace App\Filament\Platform\Resources\UserLoginLogs\Pages;

use App\Filament\Platform\Resources\UserLoginLogs\UserLoginLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUserLoginLogs extends ListRecords
{
    protected static string $resource = UserLoginLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
