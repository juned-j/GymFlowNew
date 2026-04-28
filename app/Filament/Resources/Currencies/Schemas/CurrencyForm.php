<?php

namespace App\Filament\Resources\Currencies\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class CurrencyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('currency_code')
                    ->label('Currency Code')
                    ->required()
                    ->maxLength(10)
                    ->unique(ignoreRecord: true),

                TextInput::make('currency_name')
                    ->label('Currency Name')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                TextInput::make('exchange_rate')
                    ->label('Exchange Rate')
                    ->numeric()
                    ->required(),

                TextInput::make('currency_symbol')
                    ->label('Currency Symbol')
                    ->maxLength(10)
                    ->required()
                    ->unique(ignoreRecord: true),

                Toggle::make('is_default')
                    ->label('Default Currency')
                    ->default(false),

            ]);
    }
}