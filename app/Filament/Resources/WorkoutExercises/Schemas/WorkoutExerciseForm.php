<?php

namespace App\Filament\Resources\WorkoutExercises\Schemas;

use App\Models\Exercise;
use App\Models\Workout;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WorkoutExerciseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // 🏋️ Workout selector
            Select::make('workout_id')
                ->label('Workout')
                ->relationship('workout', 'name')
                ->searchable()
                ->required(),

            // 💪 Exercise selector (library)
            Select::make('exercise_id')
                // ->searchable()
                ->preload()
                ->relationship('exercise', 'name')
                ->getOptionLabelFromRecordUsing(
                    fn($record) =>
                    "{$record->name} ({$record->muscle_group})"
                ),

            // 📊 Training prescription
            TextInput::make('sets_target')
                ->label('Sets')
                ->numeric()
                ->minValue(1)
                ->required(),

            TextInput::make('reps_target')
                ->label('Reps')
                ->numeric()
                ->minValue(1)
                ->required(),

            TextInput::make('rest_seconds')
                ->label('Rest (seconds)')
                ->numeric()
                ->minValue(0)
                ->default(60),

            // 🔁 ordering (important for drag/drop later)
            TextInput::make('sort_order')
                ->label('Order')
                ->numeric()
                ->default(1),
        ]);
    }
}
