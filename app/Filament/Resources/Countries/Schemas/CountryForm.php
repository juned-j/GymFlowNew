<?php

namespace App\Filament\Resources\Countries\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;


class CountryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('iso2')
                    ->label('ISO2')
                    ->maxLength(2),

                TextInput::make('iso3')
                    ->label('ISO3')
                    ->maxLength(3),

                TextInput::make('phone_code')
                    ->label('Phone Code'),

           Select::make('currency')
    ->label('Currency')
    ->options(
        \App\Models\Currency::pluck('currency_name', 'currency_code')
    )
    ->searchable()
    ->reactive()
    ->afterStateUpdated(function ($state, callable $set) {
        $currency = \App\Models\Currency::where('currency_code', $state)->first();

        if ($currency) {
            $set('currency_symbol', $currency->currency_symbol);
        }
    })
    ->required(),

Select::make('currency_symbol')
    ->label('Currency Symbol')
    ->options(
        \App\Models\Currency::pluck('currency_symbol', 'currency_symbol')
    )
    ->disabled(),

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