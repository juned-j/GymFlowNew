<?php

namespace App\Filament\Resources\Trainers\Pages;

use App\Filament\Resources\Trainers\TrainerResource;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Models\Trainer;

class ListTrainers extends ListRecords
{
    protected static string $resource = TrainerResource::class;

    protected function getHeaderActions(): array
    {
        $tenant = app('tenant');

        // safety fallback
        if (! $tenant) {
            return [
                CreateAction::make()->label('Add Trainer'),
            ];
        }

        $limitReached = $tenant->reachedLimit(
            'trainers',
            fn () => Trainer::count()
        );

        return [
            CreateAction::make()
                ->label('Add Trainer')
                ->color($limitReached ? 'danger' : 'primary')
                ->disabled($limitReached)
                ->tooltip(
                    $limitReached
                        ? 'Trainer limit reached. Upgrade your plan.'
                        : null
                )
                ->action(function () use ($limitReached) {

                    if ($limitReached) {
                        Notification::make()
                            ->title('Limit Reached')
                            ->body('You cannot create more trainers under your current plan.')
                            ->danger()
                            ->send();

                        return;
                    }

                }),
        ];
    }
}