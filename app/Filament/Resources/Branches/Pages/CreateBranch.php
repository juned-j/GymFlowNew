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
        $data['tenant_id'] = auth()->user()->roles()->first()?->tenant_id;

        return $data;
    }
    protected function beforeCreate(): void
    {
        if ($this->data['is_main']) {
            Branch::where('tenant_id', auth()->user()->roles()->first()?->tenant_id)
                ->update(['is_main' => false]);
        }
    }
}
