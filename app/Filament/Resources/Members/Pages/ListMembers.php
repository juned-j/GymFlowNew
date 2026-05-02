<?php

namespace App\Filament\Resources\Members\Pages;

use Filament\Actions\Action;
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
    $tenant = app('tenant');

    if (! $tenant) {
        return [
            CreateAction::make()->label('Add Member'),
        ];
    }

    $limitReached = $tenant->reachedLimit(
        'members',
        fn () => Member::where('tenant_id', $tenant->id)->count()
    );

    return [
        CreateAction::make()
            ->label('Add Member')
            ->color($limitReached ? 'danger' : 'primary') // 🔥 red button
            ->disabled($limitReached) // 🔥 disable click
            ->tooltip(
                $limitReached
                    ? 'Member limit reached. Please upgrade your plan.'
                    : null
            )
            ->action(function () use ($limitReached) {

                if ($limitReached) {
                    Notification::make()
                        ->title('Limit Reached')
                        ->body('You have reached your member limit.')
                        ->danger()
                        ->send();

                    return; // ❌ no redirect
                }

                // normal Filament create flow
            }),
    ];
}
}