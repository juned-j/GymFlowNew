<?php

namespace App\Filament\Resources\Trainers\Pages;

use App\Filament\Resources\Trainers\TrainerResource;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Models\Trainer;
use Illuminate\Support\Facades\Log;

class ListTrainers extends ListRecords
{
    protected static string $resource = TrainerResource::class;

    protected function getHeaderActions(): array
{
    $user = auth()->user();

    $tenant = $user ? \App\Models\Tenant::find($user->getTenantId()) : null;

    $limitReached = $tenant ? $tenant->reachedLimit('Trainers') : true;

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
        return 'Create a new Trainers';
    }

    return 'Trainers limit reached for your current plan. Please upgrade your subscription to add more Trainers.';
}
}
    
