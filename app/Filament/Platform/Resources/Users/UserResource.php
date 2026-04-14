<?php

namespace App\Filament\Platform\Resources\Users;

use App\Filament\Platform\Resources\Users\Pages\CreateUser;
use App\Filament\Platform\Resources\Users\Pages\EditUser;
use App\Filament\Platform\Resources\Users\Pages\ListUsers;
use App\Filament\Platform\Resources\Users\Schemas\UserForm;
use App\Filament\Platform\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([

            TextInput::make('name')->required(),
            TextInput::make('email')->email()->required(),
            TextInput::make('password')
                ->password()
                ->required(fn($context) => $context === 'create'),
            Repeater::make('roles')
                ->relationship() // uses hasMany
                ->schema([
                    Select::make('role')
                        ->options([
                            'super_admin' => 'Super Admin',
                            'owner' => 'Owner',
                            'trainer' => 'Trainer',
                            'member' => 'Member',
                        ])
                        ->required(),
                    Select::make('tenant_id')
                        ->relationship('tenant', 'name')
                        ->searchable()
                        ->nullable(),
                    Select::make('branch_id')
                        ->relationship('branch', 'name')
                        ->searchable()
                        ->nullable(),
                ])
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
