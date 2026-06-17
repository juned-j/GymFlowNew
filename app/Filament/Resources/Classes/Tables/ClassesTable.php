<?php

namespace App\Filament\Resources\Classes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class ClassesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                               ->extraAttributes([
                        'style' => 'max-width: 200px; white-space: normal; word-wrap: break-word;',
                    ])
                    ->wrap()
                    ->description(fn($record) => $record->branch?->name),

                TextColumn::make('trainer.name')
                    ->label('Instructor')
                    ->sortable(),

                TextColumn::make('start_time')
                    ->label('Date & Time')
                    ->dateTime('M d, H:i')
                    ->sortable(),

                TextColumn::make('capacity')
                    ->label('Spots')
                    ->formatStateUsing(fn($state, $record) => $record->bookings_count . ' / ' . $state)
                    ->badge()
                    ->color(fn($state, $record) => $record->bookings_count >= $record->capacity ? 'danger' : 'success'),

                TextColumn::make('location')
                    ->toggleable(),
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
