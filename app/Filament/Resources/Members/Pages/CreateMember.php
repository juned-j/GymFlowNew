<?php

namespace App\Filament\Resources\Members\Pages;

use App\Filament\Resources\Members\MemberResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMember extends CreateRecord
{
    protected static string $resource = MemberResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure the user's role is always tied to the current gym owner's tenant
        $data['user']['roles']['tenant_id'] = auth()->user()->getTenantId();

        return $data;
    }
}
