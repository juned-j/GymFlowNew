<?php

namespace App\Filament\Resources\Exercises\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ExerciseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            TextInput::make('name')
                ->label('Exercise Name')
                ->required()
                ->maxLength(30),

            Select::make('muscle_group')
                ->label('Muscle Group')
                ->options([
                    'chest' => 'Chest',
                    'back' => 'Back',
                    'shoulders' => 'Shoulders',
                    'biceps' => 'Biceps',
                    'triceps' => 'Triceps',
                    'legs' => 'Legs',
                    'core' => 'Core',
                    'full_body' => 'Full Body',
                ])
                ->required(),

            Select::make('equipment')
                ->label('Equipment')
                ->options([
                    'bodyweight' => 'Bodyweight',
                    'dumbbell' => 'Dumbbell',
                    'barbell' => 'Barbell',
                    'machine' => 'Machine',
                    'cable' => 'Cable',
                    'kettlebell' => 'Kettlebell',
                    'resistance_band' => 'Resistance Band',
                ])
                ->searchable(),

            TextInput::make('video_url')
                ->label('Demo Video URL')
                ->url()
                ->maxLength(255),

        ]);
    }
}
