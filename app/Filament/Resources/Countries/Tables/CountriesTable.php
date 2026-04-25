<?php

namespace App\Filament\Resources\Countries\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;

class CountriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('iso2')
                    ->label('ISO2')
                    ->sortable(),

                TextColumn::make('iso3')
                    ->label('ISO3'),

                TextColumn::make('phone_code')
                    ->label('Phone'),

                TextColumn::make('currency'),

                ImageColumn::make('flag')
                    ->label('Flag')
                    ->circular(),

                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),

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