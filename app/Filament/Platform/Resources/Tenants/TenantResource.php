<?php

namespace App\Filament\Platform\Resources\Tenants;

use App\Filament\Platform\Resources\Tenants\Pages\CreateTenant;
use App\Filament\Platform\Resources\Tenants\Pages\EditTenant;
use App\Filament\Platform\Resources\Tenants\Pages\ListTenants;
use App\Filament\Platform\Resources\Tenants\Schemas\TenantForm;
use App\Filament\Platform\Resources\Tenants\Tables\TenantsTable;
use App\Models\Tenant;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Infolists\Components\Section;
use Filament\Forms\Get;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    public static function configure($schema)
    {
        return $schema->schema([
            Wizard::make([
                // STEP 1: CREATE TENANT (GYM IDENTITY)
                Step::make('Gym Identity')
                    ->icon('heroicon-o-building-office')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Gym Name')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn($set, $state) => $set('slug', Str::slug($state))),

                            TextInput::make('slug')
                                ->label('Subdomain')
                                ->required()
                                ->unique(Tenant::class, 'slug', ignoreRecord: true)
                                ->prefix('gymflow.app/'),
                        ]),

                        Section::make('Theme Preview')
                            ->description('Customize how your gym appears to members.')
                            ->schema([
                                Grid::make(2)->schema([
                                    ColorPicker::make('primary_color')
                                        ->label('Brand Color')
                                        ->default('#3b82f6')
                                        ->live(), // Important for reactive preview

                                    FileUpload::make('logo')
                                        ->image()
                                        ->directory('tenants/logos'),
                                ]),

                                // THE LIVE PREVIEW CARD
                                Placeholder::make('preview')
                                    ->label('Visual Preview')
                                    ->content(fn(Get $get) => new HtmlString("
                                        <div style='background: #f3f4f6; padding: 20px; border-radius: 10px; border: 1px solid #ddd;'>
                                            <p style='font-size: 12px; color: #666; margin-bottom: 10px;'>Member App Mockup</p>
                                            <div style='background: white; width: 200px; height: 300px; border-radius: 15px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); overflow: hidden; margin: 0 auto;'>
                                                <div style='background: {$get('primary_color')}; height: 50px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;'>
                                                    " . ($get('name') ?: 'Your Gym') . "
                                                </div>
                                                <div style='padding: 15px;'>
                                                    <div style='background: #eee; height: 10px; width: 80%; border-radius: 5px; margin-bottom: 10px;'></div>
                                                    <div style='background: #eee; height: 10px; width: 60%; border-radius: 5px; margin-bottom: 20px;'></div>
                                                    <div style='background: {$get('primary_color')}; height: 30px; width: 100%; border-radius: 5px; color: white; text-align: center; line-height: 30px; font-size: 10px;'>BOOK CLASS</div>
                                                </div>
                                            </div>
                                        </div>
                                    ")),
                            ]),
                    ]),

                // STEP 2: CREATE DEFAULT BRANCH
                Step::make('Main Branch')
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        TextInput::make('branch_name')
                            ->label('Primary Branch Name')
                            ->default('Main Center')
                            ->required(),
                        TextInput::make('branch_address')
                            ->label('Branch Address')
                            ->placeholder('Street, City, India'),
                    ]),

                // STEP 3: CREATE DEFAULT MEMBERSHIP PLANS
                Step::make('Plans')
                    ->icon('heroicon-o-ticket')
                    ->schema([
                        Placeholder::make('info')
                            ->content('We will initialize your platform with a standard Monthly Membership. You can customize this later.'),
                        Grid::make(2)->schema([
                            TextInput::make('plan_name')->default('Monthly Pro')->required(),
                            TextInput::make('plan_price')->numeric()->prefix('₹')->default(1999)->required(),
                        ]),
                    ]),

                // STEP 4: SETUP TENANT SETTINGS (OWNER ASSIGNMENT)
                Step::make('Account Setup')
                    ->icon('heroicon-o-user-plus')
                    ->schema([
                        Select::make('owner_id')
                            ->label('Assign Gym Owner')
                            ->relationship('owner', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->helperText('This user will be assigned the "Owner" role for this gym.'),

                        Toggle::make('enable_notifications')
                            ->label('Enable Automated Invoicing')
                            ->default(true),
                    ])
            ])
                ->submitAction(new HtmlString('<button type="submit" class="fi-btn fi-btn-size-md fi-btn-color-primary fi-ac-btn-action">🚀 Launch Gym Platform</button>'))
                ->columnSpanFull()
        ]);
    }
    public static function form(Schema $schema): Schema
    {
        return TenantForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TenantsTable::configure($table);
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
            'index' => ListTenants::route('/'),
            'create' => CreateTenant::route('/create'),
            'edit' => EditTenant::route('/{record}/edit'),
        ];
    }
}
