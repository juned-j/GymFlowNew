<?php

namespace App\Filament\Platform\Resources\Tenants;

use App\Filament\Platform\Resources\Tenants\Pages\CreateTenant;
use App\Filament\Platform\Resources\Tenants\Pages\EditTenant;
use App\Filament\Platform\Resources\Tenants\Pages\ListTenants;
use App\Filament\Platform\Resources\Tenants\Schemas\TenantForm;
use App\Filament\Platform\Resources\Tenants\Tables\TenantsTable;
use App\Models\Tenant;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use BackedEnum;


class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([

            Wizard::make([

                /*
                |--------------------------------------------------------------------------
                | STEP 1: TENANT IDENTITY
                |--------------------------------------------------------------------------
                */
                Step::make('Gym Identity')
                    ->schema([
                        TextInput::make('name')->required(),
                        TextInput::make('slug')->required()->unique(Tenant::class, 'slug'),

                        TextInput::make('email')->email()->required(),
                        TextInput::make('phone')->tel(),
                    ]),

                /*
                |--------------------------------------------------------------------------
                | STEP 2: LOCATION (TENANT LEVEL - NOT BRANCH)
                |--------------------------------------------------------------------------
                */
                Step::make('Location')
                    ->schema([
                        TextInput::make('address')->label('Address'),
                        TextInput::make('city')->required(),
                        TextInput::make('country')->required()->default('India'),
                    ]),

                /*
                |--------------------------------------------------------------------------
                | STEP 3: SYSTEM CONFIG
                |--------------------------------------------------------------------------
                */
                Step::make('Settings')
                    ->schema([
                        TextInput::make('timezone')
                            ->default('Asia/Kolkata'),

                        TextInput::make('currency')
                            ->default('INR'),

                        Toggle::make('is_active')
                            ->default(true),

                        TextInput::make('trial_ends_at')
                            ->label('Trial Ends At')
                            ->placeholder('YYYY-MM-DD'),
                    ]),

                /*
                |--------------------------------------------------------------------------
                | STEP 4: OWNER ASSIGNMENT
                |--------------------------------------------------------------------------
                */
                Step::make('Owner')
                    ->schema([
                        Select::make('owner_user_id')
                            ->relationship('owner', 'name')
                            ->required(),
                    ]),
            ])
                ->columnSpanFull()
        ]);
    }

    public static function table(Table $table): Table
    {
        return TenantsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTenants::route('/'),
            'create' => CreateTenant::route('/create'),
            'edit' => EditTenant::route('/{record}/edit'),
        ];
    }
}
