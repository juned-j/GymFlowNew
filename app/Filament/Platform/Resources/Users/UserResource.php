<?php

namespace App\Filament\Platform\Resources\Users;

use App\Filament\Platform\Resources\Users\Pages\CreateUser;
use App\Filament\Platform\Resources\Users\Pages\EditUser;
use App\Filament\Platform\Resources\Users\Pages\ListUsers;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use App\Filament\Platform\Resources\Users\Tables\UsersTable;
use Filament\Tables\Table;
use Filament\Forms\Components\Toggle;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([

            Grid::make(2)->schema([

                TextInput::make('name')
                    ->required(),

                TextInput::make('email')
                    ->email()
                    ->required(),

                TextInput::make('password')
                    ->password()
                    ->required(fn($context) => $context === 'create')
                    ->dehydrated(fn($state) => filled($state))
                    ->confirmed()
                    ->maxLength(255),

                TextInput::make('password_confirmation')
                    ->password()
                    ->required(fn($context) => $context === 'create')
                    ->dehydrated(false),

 Select::make('status')
    ->options([
        'active' => 'Active',
        'inactive' => 'Inactive',
    ])
    ->default('active')
    ->required(),
            ])
                ->columnSpanFull(),
            Repeater::make('roles')
                ->relationship()
                ->columnSpanFull()
                ->schema([
                    Select::make('role')
                        ->options(function () {
                            $user = auth()->user();
                            if ($user->isSuperAdmin()) {
                                return [
                                    'super_admin' => 'Super Admin',
                                    'owner' => 'Owner',
                                ];
                            }
                            // Gym Owner
                            return [
                                'trainer' => 'Trainer',
                                'member' => 'Member',
                            ];
                        })
                        ->required()
                        ->live(),
                    Select::make('tenant_id')
                        ->relationship('tenant', 'name')
                        ->searchable()
                        ->nullable()
                        ->visible(fn($get) => $get('role') !== 'super_admin')
                        ->default(function () {
                            $user = auth()->user();
                            if ($user->isTenantUser()) {
                                return $user->getTenantId();
                            }
                            return null;
                        })
                        ->disabled(fn() => auth()->user()->isTenantUser()),
                    Select::make('branch_id')
                        ->relationship('branch', 'name')
                        ->searchable()
                        ->nullable()
                        ->visible(fn($get) => in_array($get('role'), ['trainer', 'member']))
                ])
        ]);
    }
    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
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