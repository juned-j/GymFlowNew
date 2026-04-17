<?php

namespace App\Filament\Resources\Branches\Pages;

use App\Filament\Resources\Branches\BranchResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Branch;

class CreateBranch extends CreateRecord
{
    protected static string $resource = BranchResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 1. Check if 'is_main' is present and true
        // We use data['is_main'] ?? false to prevent the "Undefined key" error
        if (isset($data['is_main']) && $data['is_main']) {

            // 2. Reset other branches for this tenant to false
            Branch::where('tenant_id', auth()->user()->getTenantId())
                ->where('is_main', true)
                ->update(['is_main' => false]);
        }

        return $data;
    }
    protected function beforeCreate(): void
    {
        if ($this->data['is_main']) {
            Branch::where('tenant_id', auth()->user()->getTenantId())
                ->update(['is_main' => false]);
        }
    }
}
