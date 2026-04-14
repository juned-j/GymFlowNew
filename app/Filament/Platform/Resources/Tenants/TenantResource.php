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
                Step::make('Gym Identity')
                    ->schema([
                        TextInput::make('name')->required(),
                        TextInput::make('slug')->required(),
                    ]),

                Step::make('Branch')
                    ->schema([
                        TextInput::make('branch_name')
                            ->label('Branch Name')
                            ->required()
                            ->default('Main Branch'),
                        Grid::make(2)->schema([
                            TextInput::make('address_line_1')
                                ->label('Address Line 1')
                                ->required(),
                            TextInput::make('address_line_2')
                                ->label('Address Line 2'),
                        ]),
                        Grid::make(3)->schema([
                            TextInput::make('city')
                                ->required(),
                            TextInput::make('state')
                                ->required(),
                            TextInput::make('postal_code')
                                ->label('ZIP Code'),
                        ]),
                        TextInput::make('country')
                            ->default('India')
                            ->required(),
                        Grid::make(2)->schema([
                            TextInput::make('latitude')
                                ->numeric()
                                ->placeholder('e.g. 19.0760'),
                            TextInput::make('longitude')
                                ->numeric()
                                ->placeholder('e.g. 72.8777'),
                        ]),
                        \Filament\Forms\Components\Toggle::make('is_main')
                            ->label('Set as Main Branch')
                            ->default(true),
                    ]),

                Step::make('Plans')
                    ->schema([
                        TextInput::make('plan_name')->default('Monthly Pro'),
                        TextInput::make('plan_price')->numeric(),
                    ]),

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
