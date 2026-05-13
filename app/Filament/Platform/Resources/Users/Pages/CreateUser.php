<?php

namespace App\Filament\Platform\Resources\Users\Pages;

use App\Filament\Platform\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
    protected function afterCreate(): void
    {
        $user = $this->record;
        foreach ($user->roles as $userRole) {
            $roleName = $userRole->role?->name;
            // OWNER
            if ($roleName === 'owner') {
                $userRole->branch_id = null;
                $userRole->save();
            }
            // SUPER ADMIN
            if ($roleName === 'super_admin') {
                $user->update([
                    'is_super_admin' => true,
                    'email_verified_at' => now(),
                ]);
                $userRole->tenant_id = null;
                $userRole->branch_id = null;
                $userRole->save();
            }
        }
    }
}
