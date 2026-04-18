<?php

namespace App\Filament\Resources\Workouts\Schemas;

use App\Models\WorkoutPlan;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WorkoutForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Select::make('workout_plan_id')
                ->label('Workout Plan')
                ->relationship('workoutPlan', 'name')
                ->searchable()
                ->preload()
                ->required(),

            TextInput::make('name')
                ->label('Workout Name')
                ->placeholder('e.g. Day 1 - Upper Body')
                ->required()
                ->maxLength(255),

            TextInput::make('day_number')
                ->label('Day Number')
                ->numeric()
                ->required()
                ->minValue(1),

        ]);
    }
}
