<?php


namespace App\Filament\Resources\Branches\Schemas;

use Filament\Forms\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;

class BranchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // Hidden field to automatically associate the branch with the owner's tenant
            Hidden::make('tenant_id')
                ->default(fn() => auth()->user()?->getTenantId())
                ->dehydrated(true),

            // Section 1: Basic Info
            Section::make('Branch Details')
                ->description('Provide the basic identification for this gym location.')
                ->icon('heroicon-o-building-storefront')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->label('Branch Name')
                        ->placeholder('e.g., Downtown Elite')
                        ->required()
                        ->maxLength(255),

                    Toggle::make('is_main')
                        ->label('Set as Main Branch')
                        ->helperText('Enable if this is the primary headquarters.')
                        ->default(false)
                        ->inline(false),
                ]),

            // Section 2: Address
            Section::make('Location Address')
                ->description('Physical address details for member navigation.')
                ->icon('heroicon-o-map-pin')
                ->columns(2)
                ->schema([
                    TextInput::make('address_line_1')
                        ->label('Address Line 1')
                        ->placeholder('Street address, P.O. box, etc.')
                        ->required()
                        ->columnSpanFull(),

                    TextInput::make('address_line_2')
                        ->label('Address Line 2')
                        ->placeholder('Apartment, suite, unit, etc.')
                        ->columnSpanFull(),

                    TextInput::make('city')
                        ->required(),

                    TextInput::make('state')
                        ->required(),

                    TextInput::make('postal_code')
                        ->label('Postal Code')
                        ->numeric(),

                    TextInput::make('country')
                        ->required()
                        ->default('India'),
                ]),

            // Section 3: Tech & Maps
            Section::make('Navigation & Coordinates')
                ->description('Coordinates for precise map placement.')
                ->icon('heroicon-o-globe-alt')
                ->columns(2)
                ->schema([
                    TextInput::make('latitude')
                        ->numeric()
                        ->placeholder('19.0760'),

                    TextInput::make('longitude')
                        ->numeric()
                        ->placeholder('72.8777'),

                    TextInput::make('map_link')
                        ->label('Google Maps URL')
                        ->url()
                        ->placeholder('https://maps.google.com/...')
                        ->columnSpanFull(),
                ]),
        ]);
    }
}
