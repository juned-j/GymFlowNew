<?php

namespace App\Filament\Resources\Members\Pages;

use App\Filament\Resources\Members\MemberResource;
use App\Models\User;
use App\Models\UserTenantRole;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Hash;

class CreateMember extends CreateRecord
{
    protected static string $resource = MemberResource::class;
    protected ?User $memberUser = null;
                protected static bool $canCreateAnother = false;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 1. Create the User account from nested 'user' key in form
        $userData = $data['user'] ?? [];

        $this->memberUser = User::create([
            'name' => $userData['name'],
            'email' => $userData['email'],
            'phone' => $userData['phone'] ?? null,
            'password' => Hash::make('password123'), // Default password
        ]);

        // 2. Link User ID to the Member data
        $data['user_id'] = $this->memberUser->id;

        // Remove nested user data so it doesn't cause issues during Member insertion
        unset($data['user']);

        return $data;
    }

    protected function afterCreate(): void
    {
        // 3. Create Role Mapping (Branch & Role)
        // Note: We reach back into the raw form data for the branch and role IDs
        $formData = $this->form->getRawState();
        $roleData = $formData['user']['roles'] ?? [];

        UserTenantRole::create([
            'user_id' => $this->memberUser->id,
            'tenant_id' => auth()->user()->getTenantId(),
            'branch_id' => $roleData['branch_id'] ?? null,
            'role_id' => $roleData['role_id'] ?? null,
        ]);
    }
            protected function getFormActions(): array
{
    return [];
}
}
