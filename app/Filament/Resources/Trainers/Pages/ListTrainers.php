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
        $tenant = app('tenant');

        Log::info('🚀 TRAINER PAGE LOAD');

        $limitReached = false;

        if ($tenant) {

            Log::info('🏢 Tenant Found', [
                'tenant_id' => $tenant->id,
            ]);

            // ✅ SAFE COUNT (tenant scoped)
            $count = Trainer::where('tenant_id', $tenant->id)->count();

            // ✅ PLAN
            $plan = $tenant->plan;

            Log::info('📦 PLAN DATA', [
                'plan_exists' => $plan ? true : false,
                'saas_plan_id' => $plan->id ?? null,
                'max_trainers' => $plan->max_trainers ?? null,
            ]);

            Log::info('📊 TRAINER COUNT', [
                'count' => $count,
            ]);

            // ✅ LIMIT CHECK (centralized)
            $limitReached = $tenant->reachedLimit('trainers');

            Log::info('🚫 LIMIT RESULT', [
                'limitReached' => $limitReached,
            ]);

        } else {
            Log::warning('❌ No tenant found in context');
        }

        return [
            CreateAction::make()
                ->label('Add Trainer')
                ->color($limitReached ? 'danger' : 'primary')
                ->disabled($limitReached)
                ->tooltip(
                    $limitReached
                        ? 'Trainer limit reached or plan missing.'
                        : null
                )
                ->before(function () use ($limitReached) {

                    Log::info('⚡ TRAINER CREATE CLICK', [
                        'blocked' => $limitReached,
                    ]);

                    if ($limitReached) {

                        Log::warning('⛔ TRAINER ACTION BLOCKED');

                        Notification::make()
                            ->title('Action Blocked')
                            ->body('Trainer limit reached or no active plan.')
                            ->warning()
                            ->send();

                        return false;
                    }
                }),
        ];
    }
}