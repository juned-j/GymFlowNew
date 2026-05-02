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

        $allowed = true;
        $message = null;

        if ($tenant) {

            // ✅ COUNT
            $count = Branch::where('tenant_id', $tenant->id)->count();

            // ✅ PLAN DATA
            $plan = $tenant->plan ?? null;
            $rawLimit = $plan?->max_branches ?? null;

            // 🔥 CLEAN LIMIT
            $limit = null;

            if ($rawLimit !== null) {
                $limit = is_numeric($rawLimit)
                    ? (int) $rawLimit
                    : (int) preg_replace('/[^0-9]/', '', $rawLimit);
            }

            // 🔍 FULL DEBUG LOG
            Log::info('BRANCH LIMIT CHECK', [
                'tenant_id'   => $tenant->id,
                'plan_id'     => $tenant->plan_id ?? null,
                'plan_exists' => $plan ? true : false,
                'raw_limit'   => $rawLimit,
                'clean_limit' => $limit,
                'count'       => $count,
            ]);

            // ✅ USE CENTRAL METHOD
            $result = $tenant->checkLimit(
                'branches',
                fn () => $count
            );

            $allowed = $result['allowed'];
            $message = $result['message'];

            // 🔍 RESULT LOG
            Log::info('BRANCH LIMIT RESULT', [
                'allowed' => $allowed,
                'message' => $message,
            ]);
        } else {

            // 🔥 NO TENANT LOG
            Log::warning('No tenant found in ListBranches');
        }

        return [
            CreateAction::make()
                ->label('Add Branch')
                ->color(!$allowed ? 'danger' : 'primary')
                ->disabled(!$allowed)
                ->tooltip($message)
                ->before(function () use ($allowed, $message) {

                    if (!$allowed) {

                        Log::warning('Branch creation blocked', [
                            'reason' => $message,
                        ]);

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