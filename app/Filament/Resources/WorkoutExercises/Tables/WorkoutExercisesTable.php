<?php

namespace App\Filament\Resources\WorkoutExercises\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WorkoutExercisesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order') // 🔥 IMPORTANT for workout flow
            ->columns([

                TextColumn::make('workout.name')
                    ->label('Workout')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('exercise.name')
                    ->label('Exercise')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('sets_target')
                    ->label('Sets'),

                TextColumn::make('reps_target')
                    ->label('Reps'),

                TextColumn::make('rest_seconds')
                    ->label('Rest (sec)'),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),

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
