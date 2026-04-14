<?php

namespace App\Filament\Resources\Members\Pages;

use App\Filament\Resources\Members\MemberResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\User;
use App\Models\UserTenantRole;
use Illuminate\Support\Facades\Hash;

class CreateMember extends CreateRecord
{
    protected static string $resource = MemberResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // ✅ Create User first
        $user = User::create([
            'name' => $data['user_name'],
            'email' => $data['user_email'],
            'password' => Hash::make($data['user_password']),
        ]);

        // attach to member
        $data['user_id'] = $user->id;

        // store for later
        $this->createdUser = $user;

        return $data;
    }

    protected function afterCreate(): void
    {
        $tenantId = auth()->user()->roles()->first()->tenant_id;

        // ✅ Assign MEMBER role
        UserTenantRole::create([
            'user_id' => $this->createdUser->id,
            'tenant_id' => $tenantId,
            'role' => 'member',
            'branch_id' => $this->data['branch_id'],
        ]);
    }
}
