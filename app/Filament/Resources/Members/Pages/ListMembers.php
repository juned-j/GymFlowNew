<?php

namespace App\Filament\Resources\Members\Pages;

use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Members\MemberResource;

class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        $tenant = app('tenant');

        $limitReached = false;

        if ($tenant) {
            $limitReached = $tenant->reachedLimit('members');
        }

        return [
            CreateAction::make()
                ->label('Add Member')
                ->color($limitReached ? 'danger' : 'primary')
                ->disabled($limitReached)
                ->tooltip(
                    $limitReached
                        ? 'Member limit reached. Please upgrade your plan.'
                        : null
                )
                ->before(function () use ($limitReached) {

                    if ($limitReached) {
                        Notification::make()
                            ->title('Limit Reached')
                            ->body('You cannot add more members under your current plan.')
                            ->danger()
                            ->send();

                        return false; // ⛔ stop action
                    }
                }),
        ];
    }
}