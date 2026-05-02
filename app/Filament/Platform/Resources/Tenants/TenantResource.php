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
use App\Models\Country;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Wizard::make([
                Step::make('Gym Identity')->schema([
                    TextInput::make('name')->required()
                    ->maxLength(255),
                    TextInput::make('slug')->required()
                    ->maxLength(255)->unique(Tenant::class, 'slug'),

                    TextInput::make('email')
                    ->email()
                    ->unique(Tenant::class, 'email')
                    ->required(),
                    TextInput::make('phone')->tel(),
                ]),

                Step::make('Location')->schema([
                    TextInput::make('address'),
                    TextInput::make('city')->required(),
  Select::make('country')
    ->label('Country')
    ->options(Country::query()->pluck('name', 'name'))
    ->searchable()
    ->required(),                ]),

                Step::make('Settings')->schema([
                    TextInput::make('timezone')->default('Asia/Kolkata'),
                    TextInput::make('currency')->default('INR'),
                    Toggle::make('is_active')->default(true),
                ]),

                Step::make('Owner')->schema([
                    TextInput::make('owner_name')
                        ->required(),

     TextInput::make('email')
    ->email()
    ->required()
    ->unique(ignoreRecord: true),

                    TextInput::make('owner_password')
                        ->password()
                        ->required(),
                ])
            ])
              ->submitAction(
                \Filament\Actions\Action::make('submit')
                    ->label(fn () => request()->routeIs('*edit*')
                        ? 'Save Changes'
                        : 'Create Tenant'
                    )
                    ->submit('create')
                    ->color('primary')
            )

            ->skippable(str(request()->route()->getName())->endsWith('.edit'))
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
