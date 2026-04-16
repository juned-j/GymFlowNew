<?php

namespace App\Filament\Resources\Trainers\Pages;

use App\Filament\Resources\Trainers\TrainerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTrainer extends CreateRecord
{
    protected static string $resource = TrainerResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $tenantId = auth()->user()->getTenantId();

        // 1. Create user
        $user = \App\Models\User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt('password'),
        ]);

        // 2. Create trainer profile
        $user->trainerProfile()->create([
            'specialization' => $data['trainerProfile']['specialization'] ?? null,
            'bio' => $data['trainerProfile']['bio'] ?? null,
            'status' => $data['trainerProfile']['status'] ?? null,
        ]);

        // 3. Assign role + branch
        \App\Models\UserTenantRole::create([
            'user_id'   => $user->id,
            'tenant_id' => $tenantId,
            'branch_id' => $data['branch_id'], // ✅ THIS IS THE KEY
            'role_id'   => \App\Models\Role::where('name', 'trainer')->value('id'),
        ]);

        return [];
    }
}
