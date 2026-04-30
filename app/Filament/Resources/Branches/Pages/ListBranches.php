<?php

namespace App\Filament\Resources\Branches\Pages;

use App\Filament\Resources\Branches\BranchResource;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Models\Branch;

class ListBranches extends ListRecords
{
    protected static string $resource = BranchResource::class;

    protected function getHeaderActions(): array
    {
        $tenant = app('tenant');

        // safety check
        $limitReached = false;

        if ($tenant) {
            $limitReached = $tenant->reachedLimit(
                'branches',
                fn () => Branch::where('tenant_id', $tenant->id)->count()
            );
        }

        return [
            CreateAction::make()
                ->label('Add Branch')
                ->color($limitReached ? 'danger' : 'primary')
                ->disabled($limitReached)
                ->tooltip($limitReached ? 'Branch limit reached. Upgrade plan.' : null)
                ->action(function () use ($limitReached) {

                    if ($limitReached) {
                        Notification::make()
                            ->title('Limit Reached')
                            ->body('You cannot create more branches under your plan.')
                            ->danger()
                            ->send();

                        return;
                    }

                }),
        ];
    }
}