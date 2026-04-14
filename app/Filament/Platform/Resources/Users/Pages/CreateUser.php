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

        foreach ($user->roles as $role) {

            // OWNER RULE: never branch scoped
            if ($role->role === 'owner') {
                $role->branch_id = null;
                $role->save();
            }

            // SUPER ADMIN RULE: no tenant, no branch
            if ($role->role === 'super_admin') {
                $role->tenant_id = null;
                $role->branch_id = null;
                $role->save();
            }
        }
    }
}
