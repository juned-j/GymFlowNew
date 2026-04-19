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
                        ->required(),
                    TextInput::make('day_number')
                        ->label('Day #')
                        ->disabled()
                        ->dehydrated()
                        // This closure automatically calculates the index based on the item's position
                        ->afterStateHydrated(fn(TextInput $component, $state) => $component->state($state))
                        ->default(function (Repeater $component): int {
                            // Get the current items in the repeater
                            $items = $component->getState();
                            if (is_array($items)) {
                                return count($items) + 1;
                            }
                            return 1;
                        })
                        // This ensures that if you reorder the items, the numbers stay 1, 2, 3...
                        ->content(function ($get, $statePath) {
                            // Extract the index from the state path (e.g., 'workouts.0.day_number' -> index 0)
                            preg_match('/workouts\.([^\.]+)/', $statePath, $matches);
                            $index = isset($matches[1]) ? (int)$matches[1] + 1 : 1;
                            return $index;
                        }),
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
