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
    $user = auth()->user();

    $tenant = $user ? \App\Models\Tenant::find($user->getTenantId()) : null;

    $limitReached = $tenant ? $tenant->reachedLimit('branches') : true;

    return [
        CreateAction::make()
            ->disabled(fn () => $limitReached)
            ->color(fn () => $limitReached ? 'danger' : 'primary')
            ->tooltip(fn () => $this->getLimitMessage($limitReached)),
    ];
}
protected function getLimitMessage(bool $limitReached): string
{
    if (!$limitReached) {
        return 'Create a new branch';
    }

    return 'Branch limit reached for your current plan. Please upgrade your subscription to add more branches.';
}
}