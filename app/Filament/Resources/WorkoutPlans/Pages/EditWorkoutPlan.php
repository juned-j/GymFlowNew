<?php

namespace App\Filament\Resources\WorkoutPlans\Pages;

use App\Filament\Resources\WorkoutPlans\WorkoutPlanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWorkoutPlan extends EditRecord
{
    protected static string $resource = WorkoutPlanResource::class;
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['workouts']) && is_array($data['workouts'])) {
            $index = 1;
            foreach ($data['workouts'] as $key => $workout) {
                $data['workouts'][$key]['day_number'] = $index;
                $index++;
            }
        }

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
