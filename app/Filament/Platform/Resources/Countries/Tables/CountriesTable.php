<?php

namespace App\Filament\Platform\Resources\Countries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class CountriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

             
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('iso_code')
                    ->label('ISO Code')
                    ->sortable(),

                TextColumn::make('phone_code')
                    ->label('Phone'),

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
