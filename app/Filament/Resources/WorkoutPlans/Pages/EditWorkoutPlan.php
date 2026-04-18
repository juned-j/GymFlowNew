<?php

namespace App\Filament\Resources\WorkoutPlans\Pages;

use App\Filament\Resources\WorkoutPlans\WorkoutPlanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWorkoutPlan extends EditRecord
{
    protected static string $resource = WorkoutPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
