<?php

namespace App\Filament\Resources\Countries\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;

class CountryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('name')
                    ->label('Country Name')
                    ->required()
                    ->maxLength(255),

             TextInput::make('iso_code')
    ->label('ISO Code')
    ->maxLength(2)
   
    ->unique(ignoreRecord: true)
    ->regex('/^[A-Za-z]{2}$/')
    ->helperText('Enter 2-letter ISO code (e.g., IN, US)'),

                TextInput::make('phone_code')
                    ->label('Phone Code')
                    ->placeholder('+91'),

                FileUpload::make('flag')
                    ->label('Flag')
                    ->image()
                    ->directory('flags')
                    ->nullable(),

                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),

            ]);
    }
}