<?php

namespace App\Filament\Resources\WorkoutPlans\Schemas;

use App\Models\Exercise;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Hidden;

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
                ->relationship()
                ->schema([
                    Grid::make(4)
                        ->schema([
                            TextInput::make('name')
                                ->label('Day Name')
                                ->placeholder('e.g. Day 1 - Upper Body')
                                ->required()
                                ->columnSpan(3),

                            // This is now an editable text field that auto-increments
                            TextInput::make('day_number')
                                ->label('Day #')
                                ->numeric()
                                ->required()
                                ->default(function ($get) {
                                    // Counts existing items in the repeater and adds 1
                                    $items = $get('../../workouts') ?? [];
                                    return count($items) + 1;
                                })
                                ->columnSpan(1),
                        ]),

                    Repeater::make('exercises')
                        ->relationship()
                        ->schema([
                            Select::make('exercise_id')
                                ->label('Exercise')
                                ->options(Exercise::all()->pluck('name', 'id'))
                                ->searchable()
                                ->required()
                                ->preload()
                                ->columnSpan(2),

                            TextInput::make('sets_target')
                                ->label('Sets')
                                ->numeric()
                                ->required(),

                            TextInput::make('reps_target')
                                ->label('Reps')
                                ->numeric()
                                ->required(),

                            TextInput::make('rest_seconds')
                                ->label('Rest (sec)')
                                ->numeric()
                                ->default(60),
                        ])
                        ->columns(5)
                        ->defaultItems(1)
                        ->collapsible()
                ])
                ->defaultItems(1)
                ->collapsible()
                ->columnSpanFull()

        ]);
    }
}
