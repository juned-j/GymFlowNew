<?php

namespace App\Filament\Resources\WorkoutExercises\Pages;

use App\Filament\Resources\WorkoutExercises\WorkoutExerciseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWorkoutExercise extends EditRecord
{
    protected static string $resource = WorkoutExerciseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
