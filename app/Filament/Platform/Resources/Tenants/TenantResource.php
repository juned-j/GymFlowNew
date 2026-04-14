<?php

namespace App\Filament\Platform\Resources\Tenants;

use App\Filament\Platform\Resources\Tenants\Pages\CreateTenant;
use App\Filament\Platform\Resources\Tenants\Pages\EditTenant;
use App\Filament\Platform\Resources\Tenants\Pages\ListTenants;
use App\Filament\Platform\Resources\Tenants\Schemas\TenantForm;
use App\Filament\Platform\Resources\Tenants\Tables\TenantsTable;

use App\Models\Tenant;
use Filament\Schemas\Components\Grid;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use BackedEnum;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Wizard::make([
                Step::make('Gym Identity')->schema([
                    TextInput::make('name')->required(),
                    TextInput::make('slug')->required()->unique(Tenant::class, 'slug'),

                    TextInput::make('email')->email()->required(),
                    TextInput::make('phone')->tel(),
                ]),

                Step::make('Location')->schema([
                    TextInput::make('address'),
                    TextInput::make('city')->required(),
                    TextInput::make('country')->required()->default('India'),
                ]),

                Step::make('Settings')->schema([
                    TextInput::make('timezone')->default('Asia/Kolkata'),
                    TextInput::make('currency')->default('INR'),
                    Toggle::make('is_active')->default(true),
                ]),

                Step::make('Owner')->schema([
                    Select::make('owner_user_id')
                        ->relationship('owner', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                ]),
            ])
        ]);
    }
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('slug'),
                TextColumn::make('email'),
                TextColumn::make('city'),
                TextColumn::make('country'),
                TextColumn::make('is_active')->badge(),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([])
            ->actions([
                EditAction::make(),
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
