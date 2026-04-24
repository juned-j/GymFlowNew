<?php

namespace App\Filament\Resources\Branches\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Forms\Components\Section;

class BranchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Wizard::make([
                // Step 1: Identity
                Step::make('Branch Identity')
                    ->description('Basic identification and status')
                    ->icon('heroicon-o-building-storefront')
                    ->schema([
                        Hidden::make('tenant_id')
                            ->default(fn() => auth()->user()?->getTenantId())
                            ->dehydrated(true),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Branch Name')
                                      ->maxLength(50)
                    ->rules(['max:50'])
                                    ->required()
                                    ->maxLength(255),

                                Toggle::make('is_main')
                                    ->label('Main Branch')
                                    ->helperText('If enabled, this will be the primary headquarters.')
                                    ->default(false),

                                // Toggle::make('is_active')
                                //     ->label('Operational Status')
                                //     ->default(true),
                            ]),
                    ]),

                // Step 2: Location (Mapping exactly to your Model's $fillable)
                Step::make('Location Details')
                    ->description('Physical address and mapping')
                      ->maxLength(50)
                    ->rules(['max:50'])
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('address_line_1')
                                    ->label('Address Line 1')
                                      ->maxLength(50)
                    ->rules(['max:50'])
                                    ->required()
                                    ->columnSpanFull(),

                                TextInput::make('address_line_2')
                                    ->label('Address Line 2')
                                    ->maxLength(50)
                    ->rules(['max:50'])
                                    ->columnSpanFull(),

                                TextInput::make('city')
                                  ->maxLength(50)
                    ->rules(['max:50'])
                                    ->required(),

                                TextInput::make('state')
                                  ->maxLength(50)
                    ->rules(['max:50'])
                                    ->required(),

                                TextInput::make('postal_code')
                                  ->maxLength(10)
                    ->rules(['max:50'])
                                    ->label('Postal Code'),

                                TextInput::make('country')
                                    ->default('India')
                                    ->required(),

                                TextInput::make('latitude')
                                    ->numeric(),

                                TextInput::make('longitude')
                                    ->numeric(),
                            ]),
                    ]),
            ])
            ->submitAction(
    \Filament\Actions\Action::make('submit')
        ->label(fn () => request()->routeIs('*edit*')
            ? 'Save Changes'
            : 'Create Branch'
        )
        ->submit('create')
        ->color('primary')
)
                    ->skippable(str(request()->route()->getName())->endsWith('.edit'))

            ->columnSpanFull()
        ]);
    }
}
