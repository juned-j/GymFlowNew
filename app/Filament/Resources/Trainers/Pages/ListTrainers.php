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

        $allowed = true;
        $message = null;

        // ✅ tenant exists
        if ($tenant) {
            $result = $tenant->checkLimit(
                'trainers',
                fn () => Trainer::where('tenant_id', $tenant->id)->count() // 🔥 FIXED
            );

            $allowed = $result['allowed'];
            $message = $result['message'];
        }

        return [
            CreateAction::make()
                ->label('Add Trainer')
                ->color(!$allowed ? 'danger' : 'primary')
                ->disabled(!$allowed)
                ->tooltip($message)
                ->before(function () use ($allowed, $message) {

                    if (!$allowed) {
                        Notification::make()
                            ->title('Action Blocked')
                            ->body($message)
                            ->warning()
                            ->send();

                        return false; // ⛔ stop execution cleanly
                    }
                }),
        ];
    }
}