<?php

namespace App\Filament\Resources\WorkoutExercises\Pages;

use App\Filament\Resources\WorkoutExercises\WorkoutExerciseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWorkoutExercises extends ListRecords
{
    protected static string $resource = WorkoutExerciseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
