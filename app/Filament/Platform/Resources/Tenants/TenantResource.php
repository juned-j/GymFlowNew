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
                        TextInput::make('branch_name')->required(),
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
