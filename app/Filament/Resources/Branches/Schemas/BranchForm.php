<?php

namespace App\Filament\Resources\Branches\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;

class BranchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Hidden::make('tenant_id')
                ->default(fn() => auth()->user()->roles()->first()?->tenant_id)
                ->dehydrated(true),

            // 🧱 Basic Info (2 per row)
            Grid::make(2)->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(1),

                Toggle::make('is_main')
                    ->label('Main Branch')
                    ->default(false)
                    ->columnSpan(1),
            ]),

            // 📍 Address (2 per row)
            Grid::make(2)->components([
                TextInput::make('address_line_1')
                    ->label('Address Line 1')
                    ->required()
                    ->columnSpan(1),

                TextInput::make('address_line_2')
                    ->label('Address Line 2')
                    ->columnSpan(1),
            ]),

            // 🏙️ City / State (2 per row)
            Grid::make(2)->components([
                TextInput::make('city')
                    ->required()
                    ->columnSpan(1),

                TextInput::make('state')
                    ->required()
                    ->columnSpan(1),
            ]),

            // 🌍 Postal / Country (2 per row)
            Grid::make(2)->components([
                TextInput::make('postal_code')
                    ->label('Postal Code')
                    ->columnSpan(1),

                TextInput::make('country')
                    ->required()
                    ->default('India')
                    ->columnSpan(1),
            ]),

            // 📍 Coordinates (2 per row)
            Grid::make(2)->components([
                TextInput::make('latitude')
                    ->numeric()
                    ->placeholder('e.g. 19.0760')
                    ->columnSpan(1),

                TextInput::make('longitude')
                    ->numeric()
                    ->placeholder('e.g. 72.8777')
                    ->columnSpan(1),
            ]),

            // 🗺️ Map link (full width)
            TextInput::make('map_link')
                ->label('Google Map Link')
                ->placeholder('https://maps.google.com/...')
                ->columnSpanFull(),
        ]);
    }
}
