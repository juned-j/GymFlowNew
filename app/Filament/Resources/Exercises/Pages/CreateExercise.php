<?php

namespace App\Filament\Resources\Exercises\Pages;

use App\Filament\Resources\Exercises\ExerciseResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateExercise extends CreateRecord
{
    protected static string $resource = ExerciseResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Automatically set the tenant_id to the currently logged-in user's tenant
        $data['tenant_id'] = auth()->user()->getTenantId();

        return $data;
    }
}
