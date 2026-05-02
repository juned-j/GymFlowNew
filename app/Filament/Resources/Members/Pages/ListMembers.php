<?php

namespace App\Filament\Resources\Members\Pages;

use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Members\MemberResource;
use App\Models\Member;

class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        $tenant = app('tenant');

        $allowed = true;
        $message = null;

        // ✅ tenant exists
        if ($tenant) {
            $result = $tenant->checkLimit(
                'members',
                fn () => Member::where('tenant_id', $tenant->id)->count() // ✅ correct scoped count
            );

            $allowed = $result['allowed'];
            $message = $result['message'];
        }

        return [
            CreateAction::make()
                ->label('Add Member')
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

                        return false; // ⛔ stop cleanly
                    }
                }),
        ];
    }
}