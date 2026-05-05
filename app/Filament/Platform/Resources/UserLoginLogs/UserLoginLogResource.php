<?php

namespace App\Filament\Platform\Resources\UserLoginLogs;

use App\Filament\Platform\Resources\UserLoginLogs\Pages\CreateUserLoginLog;
use App\Filament\Platform\Resources\UserLoginLogs\Pages\EditUserLoginLog;
use App\Filament\Platform\Resources\UserLoginLogs\Pages\ListUserLoginLogs;
use App\Filament\Platform\Resources\UserLoginLogs\Schemas\UserLoginLogForm;
use App\Filament\Platform\Resources\UserLoginLogs\Tables\UserLoginLogsTable;
use App\Models\UserLoginLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UserLoginLogResource extends Resource
{
    protected static ?string $model = UserLoginLog::class;

protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowRightOnRectangle;
 

    public static function table(Table $table): Table
    {
        return UserLoginLogsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

   public static function canCreate(): bool
    {
        return false;
    }



  

 

    public static function getPages(): array
    {
        return [
            'index' => ListUserLoginLogs::route('/'),
        ];
    }
}
