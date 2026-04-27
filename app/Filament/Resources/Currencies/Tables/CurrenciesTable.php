<?php

namespace App\Filament\Resources\Currencies\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;

class CurrenciesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

     

                TextColumn::make('currency_code')
                    ->label('Code')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('currency_name')
                    ->label('Name')
                    ->searchable(),

                TextColumn::make('exchange_rate')
                    ->label('Rate')
                    ->sortable(),

                TextColumn::make('currency_symbol')
                    ->label('Symbol'),

                IconColumn::make('is_default')
                    ->label('Default')
                    ->boolean(),

            ])

            ->filters([
                //
            ])

            ->recordActions([
                EditAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}