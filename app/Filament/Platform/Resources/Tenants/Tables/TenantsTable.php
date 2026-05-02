<?php

namespace App\Filament\Platform\Resources\Tenants\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;


class TenantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('name')
                    ->searchable()
                              ->extraAttributes([
                        'style' => 'max-width: 200px; white-space: normal; word-wrap: break-word;',
                    ])
                    ->wrap()
                    ->sortable(),

                TextColumn::make('slug')
                          ->extraAttributes([
                        'style' => 'max-width: 200px; white-space: normal; word-wrap: break-word;',
                    ])
                    ->wrap()
                    ->searchable(),

                TextColumn::make('email')
                    ->searchable(),

                TextColumn::make('phone'),

                TextColumn::make('city') 
                ->extraAttributes([
                        'style' => 'max-width: 200px; white-space: normal; word-wrap: break-word;',
                    ])
                    ->wrap(),

                TextColumn::make('country'),

                IconColumn::make('is_active')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
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
