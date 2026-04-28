<?php

namespace App\Filament\Platform\Resources\UserLoginLogs\Pages;

use App\Filament\Platform\Resources\UserLoginLogs\UserLoginLogResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUserLoginLog extends EditRecord
{
    protected static string $resource = UserLoginLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
