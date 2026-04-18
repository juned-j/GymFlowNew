<?php

namespace App\Filament\Resources\WorkoutPlans\Pages;

use App\Filament\Resources\WorkoutPlans\WorkoutPlanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWorkoutPlans extends ListRecords
{
    protected static string $resource = WorkoutPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
