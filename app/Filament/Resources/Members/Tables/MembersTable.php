<?php

namespace App\Filament\Resources\Members\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;

class MembersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable(),

                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),

                TextColumn::make('branch.name')
                    ->label('Branch'),

                TextColumn::make('gender'),

                TextColumn::make('weight')
                    ->suffix(' kg'),

                TextColumn::make('goal')
                    ->limit(20),

                TextColumn::make('status')
                    ->badge(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
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
