<?php

namespace App\Filament\Platform\Resources\Branches\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Form;

class BranchForm
{
    public static function configure(Form $form): Form
    {
        return $form->schema([

            // Hidden tenant assignment
            Hidden::make('tenant_id')
                ->default(fn() => auth()->user()->tenant_id) // simplified logic
                ->dehydrated(true),

            // Row 1: Name and Toggle
            Grid::make(2)->schema([
                TextInput::make('name')
                    ->label('Branch Name')
                    ->placeholder('e.g. South Mumbai Center')
                    ->required()
                    ->maxLength(255),

                Toggle::make('is_main')
                    ->label('Main Branch')
                    ->inline(false) // Better vertical alignment in grids
                    ->default(false),
            ]),

            // Row 2: Address
            Grid::make(2)->schema([
                TextInput::make('address_line_1')
                    ->label('Address Line 1')
                    ->required(),

                TextInput::make('address_line_2')
                    ->label('Address Line 2'),
            ]),

            // Row 3: Location
            Grid::make(2)->schema([
                TextInput::make('city')->required(),
                TextInput::make('state')->required(),
            ]),

            // Row 4: Regional
            Grid::make(2)->schema([
                TextInput::make('postal_code')->label('Postal Code'),

                TextInput::make('country')
                    ->required()
                    ->default('India'),
            ]),

            // Row 5: Coordinates
            Grid::make(2)->schema([
                TextInput::make('latitude')
                    ->numeric()
                    ->placeholder('e.g. 19.0760'),

                TextInput::make('longitude')
                    ->numeric()
                    ->placeholder('e.g. 72.8777'),
            ]),

            // Full Width Row
            TextInput::make('map_link')
                ->label('Google Map Link')
                ->url()
                ->placeholder('https://maps.google.com/...')
                ->columnSpanFull(),
        ]);
    }
}
