<?php

namespace App\Filament\Resources\Branches\Pages;

use App\Filament\Resources\BranchResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use App\Models\Branch;

class EditBranch extends EditRecord
{
    protected static string $resource = BranchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }


    protected function beforeCreate(): void
    {
        if ($this->data['is_main']) {
            Branch::where('tenant_id', auth()->user()->roles()->first()?->tenant_id)
                ->update(['is_main' => false]);
        }
    }
}
