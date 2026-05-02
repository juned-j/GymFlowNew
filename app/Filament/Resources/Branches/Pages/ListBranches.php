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

            // ✅ SAFE COUNT
            $count = Branch::where('tenant_id', $tenant->id)->count();

            // ✅ PLAN DATA
            $plan = $tenant->plan ?? null;
            $rawLimit = $plan?->max_branches;

            // 🔥 CLEAN LIMIT
            $limit = null;

            if ($rawLimit !== null) {
                $limit = is_numeric($rawLimit)
                    ? (int) $rawLimit
                    : (int) preg_replace('/[^0-9]/', '', $rawLimit);
            }

            // 🔍 DEBUG LOG (IMPORTANT)
            Log::info('BRANCH LIMIT DEBUG', [
                'tenant_id'   => $tenant->id,
                'plan_id'     => $tenant->plan_id ?? null,
                'raw_limit'   => $rawLimit,
                'clean_limit' => $limit,
                'count'       => $count,
                'comparison'  => $limit !== null ? ($count >= $limit) : null,
            ]);

            // 🚨 LOGIC
            if ($limit === null) {
                $allowed = false;
                $message = 'Branch limit is not configured in your plan.';
            } elseif ($limit === 0) {
                $allowed = true; // unlimited
            } elseif ($count >= $limit) {
                $allowed = false;
                $message = 'Branch limit reached. Upgrade your plan.';
            } else {
                $allowed = true;
            }
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

                        return false; // ⛔ stop action
                    }
                }),
        ];
    }
}