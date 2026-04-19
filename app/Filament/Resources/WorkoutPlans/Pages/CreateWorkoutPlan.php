<?php

namespace App\Filament\Resources\WorkoutPlans\Pages;

use App\Filament\Resources\WorkoutPlans\WorkoutPlanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkoutPlan extends CreateRecord
{
    protected static string $resource = WorkoutPlanResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 1. Assign the tenant context
        $data['tenant_id'] = auth()->user()->getTenantId();

        // 2. Loop through the workouts repeater and inject the day_number
        if (isset($data['workouts']) && is_array($data['workouts'])) {
            $index = 1;
            foreach ($data['workouts'] as $key => $workout) {
                // We add the position index as the day_number
                $data['workouts'][$key]['day_number'] = $index;
                $index++;
            }
        }

        return $data;
    }
}
