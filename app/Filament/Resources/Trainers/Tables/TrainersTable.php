<?php

namespace App\Filament\Resources\Trainers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TrainersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Pulling data via the 'user' relationship defined in Trainer model
                TextColumn::make('user.name')
                    ->label('Trainer Name')
                      ->searchable()
                          ->extraAttributes([
                        'style' => 'max-width: 200px; white-space: normal; word-wrap: break-word;',
                    ])
                    ->wrap()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.email')
                    ->label('Email Address')
                    ->searchable(),

                TextColumn::make('user.phone')
                    ->label('Phone Number')
                    ->searchable(),

                TextColumn::make('specialization')
                    ->searchable(),

                TextColumn::make('status')
                    ->badge() // Adds nice visual formatting
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filters go here
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
