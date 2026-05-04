<?php

namespace App\Filament\Resources\Plans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;

class PlansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('name')
                    ->label('Plan Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->toggleable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('monthly_price')
                    ->label('Monthly')
                    ->money('usd')
                    ->sortable(),

                Tables\Columns\TextColumn::make('annual_price')
                    ->label('Annual')
                    ->money('usd')
                    ->sortable(),

                Tables\Columns\TextColumn::make('max_users')
                    ->label('Users'),

               

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_custom')
                    ->label('Custom')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
                Tables\Filters\TernaryFilter::make('is_custom'),
            ])
            ->recordActions([
                EditAction::make()->modalWidth('xl'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}