<?php

namespace App\Filament\Resources\Trainers\Pages;

use App\Filament\Resources\Trainers\TrainerResource;
use App\Models\User;
use App\Models\UserTenantRole;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateTrainer extends CreateRecord
{
    protected static string $resource = TrainerResource::class;
    protected ?User $trainerUser = null;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 1. Create Trainer User account
        $userData = $data['user'] ?? [];

        $this->trainerUser = User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'phone' => $userData['phone'] ?? null,
            'password' => Hash::make('password123'),
        ]);

        // 2. Map User ID to Trainer
        $data['user_id'] = $this->trainerUser->id;

        unset($data['user']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $formData = $this->form->getRawState();
        $roleData = $formData['user']['roles'] ?? [];

        // 3. Map Trainer Role and Branch
        UserTenantRole::create([
            'user_id' => $this->trainerUser->id,
            'tenant_id' => auth()->user()->getTenantId(),
            'branch_id' => $roleData['branch_id'] ?? null,
            'role_id' => $roleData['role_id'] ?? null,
        ]);
    }
}
