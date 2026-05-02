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

        $allowed = true;
        $message = null;

        // ✅ SAME AS TRAINER
        if ($tenant) {
            $result = $tenant->checkLimit(
                'branches',
                fn () => Branch::where('tenant_id', $tenant->id)->count()
            );

            $allowed = $result['allowed'];
            $message = $result['message'];
        }

        return [
            CreateAction::make()
                ->label('Add Branch')
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

                        return false;
                    }
                }),
        ];
    }
}