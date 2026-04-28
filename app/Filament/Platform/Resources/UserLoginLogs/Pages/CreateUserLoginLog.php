<?php

namespace App\Filament\Platform\Resources\UserLoginLogs\Pages;

use App\Filament\Platform\Resources\UserLoginLogs\UserLoginLogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUserLoginLog extends CreateRecord
{
    protected static string $resource = UserLoginLogResource::class;
}
