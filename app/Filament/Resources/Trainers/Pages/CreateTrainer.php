<?php

namespace App\Filament\Resources\Trainers\Pages;

use App\Filament\Resources\Trainers\TrainerResource;
use App\Models\User;
use App\Models\UserTenantRole;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\TrainerWelcomeMail;

class CreateTrainer extends CreateRecord
{
    protected static string $resource = TrainerResource::class;
    protected ?User $trainerUser = null;
    protected ?string $plainPassword = null;
    protected static bool $canCreateAnother = false;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $userData = $data['user'] ?? [];

        // Generate random password
        $this->plainPassword = Str::password(10);

        // Create user
        $this->trainerUser = User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'phone' => $userData['phone'] ?? null,
            'password' => Hash::make($this->plainPassword),
        ]);

        // Attach user to trainer
        $data['user_id'] = $this->trainerUser->id;

        unset($data['user']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $formData = $this->form->getRawState();

        $userData = $formData['user'] ?? [];

        UserTenantRole::create([
            'user_id' => $this->trainerUser->id,
            'tenant_id' => auth()->user()->getTenantId(),
            'branch_id' => $userData['branch_id'] ?? null,
            'role_id' => $userData['role_id'] ?? null,
        ]);

        Mail::to($this->trainerUser->email)
            ->send(new TrainerWelcomeMail(
                $this->trainerUser,
                $this->plainPassword
            ));
    }

    protected function getFormActions(): array
    {
        return [];
    }
}
