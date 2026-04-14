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
        return $schema
            ->components([
                // 🔒 Tenant auto-attach
                Hidden::make('tenant_id')
                    ->default(fn() => auth()->user()->roles()->first()?->tenant_id)
                    ->dehydrated(true),

                // 🧱 Basic Info
                Grid::make(2)->components([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),

                    Toggle::make('is_main')
                        ->label('Main Branch')
                        ->default(false),
                ]),

                // 📍 Address
                Grid::make(2)->components([
                    TextInput::make('address_line_1')
                        ->label('Address Line 1')
                        ->required(),

                    TextInput::make('address_line_2')
                        ->label('Address Line 2'),
                ]),

                Grid::make(3)->components([
                    TextInput::make('city')->required(),
                    TextInput::make('state')->required(),

                ]),

                // 🌍 Location + Coordinates (FIXED)
                Grid::make(2)->components([
                    TextInput::make('postal_code')->label('Postal Code'),
                    TextInput::make('country')
                        ->required()
                        ->default('India'),
                ]),

                Grid::make(2)->components([
                    TextInput::make('latitude')
                        ->numeric()
                        ->placeholder('e.g. 19.0760'),
                    TextInput::make('longitude')
                        ->numeric()
                        ->placeholder('e.g. 72.8777'),
                ]),
                Grid::make(2)->components([
                    TextInput::make('map_link') // optional nice addition
                        ->label('Google Map Link')
                        ->placeholder('https://maps.google.com/...'),
                ])
                    ->columnSpanFull(),
            ]);
    }
}
