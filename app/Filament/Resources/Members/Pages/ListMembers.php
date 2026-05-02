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
        $tenant = app('tenant');

        Log::info('🚀 MEMBER PAGE LOAD');

        $limitReached = false;

        if ($tenant) {

            Log::info('🏢 Tenant Found', [
                'tenant_id' => $tenant->id,
            ]);

            // ✅ COUNT (tenant scoped)
            $count = Member::where('tenant_id', $tenant->id)->count();

            // ✅ PLAN
            $plan = $tenant->plan;

            Log::info('📦 PLAN DATA', [
                'plan_exists' => $plan ? true : false,
                'saas_plan_id' => $plan->id ?? null,
                'max_members' => $plan->max_members ?? null,
            ]);

            Log::info('📊 MEMBER COUNT', [
                'count' => $count,
            ]);

            // ✅ LIMIT CHECK
            $limitReached = $tenant->reachedLimit('members');

            Log::info('🚫 LIMIT RESULT', [
                'limitReached' => $limitReached,
            ]);

        } else {
            Log::warning('❌ No tenant found in context');
        }

        return [
            CreateAction::make()
                ->label('Add Member')
                ->color($limitReached ? 'danger' : 'primary')
                ->disabled($limitReached)
                ->tooltip(
                    $limitReached
                        ? 'Member limit reached or plan missing.'
                        : null
                )
                ->before(function () use ($limitReached) {

                    Log::info('⚡ MEMBER CREATE CLICK', [
                        'blocked' => $limitReached,
                    ]);

                    if ($limitReached) {

                        Log::warning('⛔ MEMBER ACTION BLOCKED');

                        Notification::make()
                            ->title('Action Blocked')
                            ->body('Member limit reached or no active plan.')
                            ->warning()
                            ->send();

                        return false;
                    }
                }),
        ];
    }
}