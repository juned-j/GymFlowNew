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

    protected function getHeaderActions(): array
    {
        $tenant = app('tenant');

        Log::info('🚀 BRANCH PAGE LOAD');

        $limitReached = false;

        if ($tenant) {

            Log::info('🏢 Tenant Found', [
                'tenant_id' => $tenant->id,
            ]);

            // ✅ count
            $count = Branch::where('tenant_id', $tenant->id)->count();

            // ✅ plan
            $plan = $tenant->plan;

            Log::info('📦 PLAN DATA', [
                'plan_exists' => $plan ? true : false,
                'plan_id' => $plan->id ?? null,
                'max_branches' => $plan->max_branches ?? null,
            ]);

            Log::info('📊 BRANCH COUNT', [
                'count' => $count,
            ]);

            // ✅ limit check
            $limitReached = $tenant->reachedLimit('branches');

            Log::info('🚫 LIMIT RESULT', [
                'limitReached' => $limitReached,
            ]);
        } else {
            Log::warning('❌ No tenant found in context');
        }

        return [
            CreateAction::make()
                ->label('Add Branch')
                ->color($limitReached ? 'danger' : 'primary')
                ->disabled($limitReached)
                ->tooltip(
                    $limitReached
                        ? 'Branch limit reached or plan missing.'
                        : null
                )
                ->before(function () use ($limitReached) {

                    Log::info('⚡ CREATE ACTION TRIGGERED', [
                        'blocked' => $limitReached,
                    ]);

                    if ($limitReached) {

                        Log::warning('⛔ ACTION BLOCKED');

                        Notification::make()
                            ->title('Action Blocked')
                            ->body('Branch limit reached or no active plan.')
                            ->warning()
                            ->send();

                        return false;
                    }
                }),
        ];
    }
}