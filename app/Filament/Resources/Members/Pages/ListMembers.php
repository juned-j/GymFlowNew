<?php

namespace App\Filament\Resources\Members\Pages;

use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Members\MemberResource;
use Illuminate\Support\Facades\Log;
use App\Models\Member;

class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;
protected function getHeaderActions(): array
{
    $user = auth()->user();

    $tenant = $user ? \App\Models\Tenant::find($user->getTenantId()) : null;

    $limitReached = $tenant ? $tenant->reachedLimit('Members') : true;

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
        return 'Create a new Members';
    }

    return 'Members limit reached for your current plan. Please upgrade your subscription to add more Members.';
}
}
    
