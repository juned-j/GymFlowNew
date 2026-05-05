<?php

namespace App\Filament\Resources\Branches\Pages;

use App\Filament\Resources\Branches\BranchResource;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Models\Branch;
use Illuminate\Support\Facades\Log;

class ListBranches extends ListRecords
{
    protected static string $resource = BranchResource::class;

   // ListBranches.php
protected function getHeaderActions(): array
{
    $tenant = app('tenant');
    
    $limitReached = $tenant ? $tenant->reachedLimit('branches') : true;

    return [
        CreateAction::make()
            ->disabled($limitReached)
            ->tooltip($limitReached ? 'Limit reached' : null),
    ];
}
}