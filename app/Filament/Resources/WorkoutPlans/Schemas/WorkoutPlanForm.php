<?php

namespace App\Filament\Resources\WorkoutPlans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

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

        ]);
    }
}
