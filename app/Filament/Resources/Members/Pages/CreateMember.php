<?php

namespace App\Filament\Resources\Members\Pages;

use App\Filament\Resources\MemberResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\User;
use App\Models\UserTenantRole;
use Illuminate\Support\Facades\Hash;

class CreateMember extends CreateRecord
{
    protected static string $resource = MemberResource::class;

    protected ?User $createdUser = null;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $state = $this->form->getState();

        $this->createdUser = User::create([
            'name' => $state['user_name'],
            'email' => $state['user_email'],
            'password' => Hash::make($state['user_password']),
        ]);

        $data['user_id'] = $this->createdUser->id;

        return $data;
    }

    protected function afterCreate(): void
    {
        $state = $this->form->getState();

        UserTenantRole::create([
            'user_id' => $this->createdUser->id,
            'tenant_id' => auth()->user()->getTenantId(),
            'role_id' => \App\Models\Role::where('name', 'member')->value('id'),
            'branch_id' => $state['branch_id'] ?? null,
        ]);
    }
}
