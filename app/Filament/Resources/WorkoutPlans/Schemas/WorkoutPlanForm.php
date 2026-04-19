<?php

namespace App\Filament\Resources\WorkoutPlans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Repeater;

class WorkoutPlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            TextInput::make('name')
                ->label('Plan Name')
                ->placeholder('e.g. Beginner Fat Loss')
                ->required()
                ->maxLength(255),

            Select::make('goal_type')
                ->label('Goal')
                ->options([
                    'fat_loss' => 'Fat Loss',
                    'muscle_gain' => 'Muscle Gain',
                    'strength' => 'Strength',
                ])
                ->required(),

            Select::make('difficulty')
                ->label('Difficulty Level')
                ->options([
                    'beginner' => 'Beginner',
                    'intermediate' => 'Intermediate',
                    'advanced' => 'Advanced',
                ])
                ->required(),

            Toggle::make('is_default')
                ->label('Default Plan')
                ->helperText('Auto-assign this plan after onboarding')
                ->default(false),
            Repeater::make('workouts')
                ->relationship() // Plan → Workouts
                ->schema([
                    TextInput::make('name')
                        ->label('Day Name')
                        ->placeholder('e.g. Day 1 - Upper Body')
                        ->required()
                        ->columnSpan(3), // Takes up 75% of the row

                    TextInput::make('day_number')
                        ->label('Day #')
                        ->disabled()
                        ->dehydrated()
                        ->default(fn($get) => count($get('../../workouts') ?? []) + 1)
                        ->columnSpan(1), // Takes up 25% of the row
                    Repeater::make('exercises')
                        ->relationship() // Workout → Exercises
                        ->schema([
                            TextInput::make('exercise_name')
                                ->required(),
                            TextInput::make('sets')
                                ->numeric()
                                ->required(),
                            TextInput::make('reps')
                                ->numeric()
                                ->required(),
                            TextInput::make('rest_seconds')
                                ->numeric(),
                        ])
                        ->columns(4)
                        ->defaultItems(1)
                        ->collapsible()
                ])
                ->defaultItems(1)
                ->collapsible()
                ->columnSpanFull()

        ]);
    }
}
