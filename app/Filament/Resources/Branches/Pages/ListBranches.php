<?php

namespace App\Filament\Resources\Branches\Pages;

use App\Filament\Resources\Branches\BranchResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Log;

class ListBranches extends ListRecords
{
    protected static string $resource = BranchResource::class;

    protected function getHeaderActions(): array
    {
        $user = auth()->user();

        Log::info('📍 [BRANCH PAGE] HEADER ACTION START', [
            'user_id' => $user?->id,
        ]);

        $tenant = null;
        $limitReached = true;

        if ($user) {

            $tenantId = $user->getTenantId();

            Log::info('🏢 [BRANCH PAGE] TENANT FETCH', [
                'tenant_id' => $tenantId,
                'user_id' => $user->id
            ]);

            $tenant = \App\Models\Tenant::find($tenantId);

            if ($tenant) {

                $limitReached = $tenant->reachedLimit('branches');

                Log::info('📊 [BRANCH LIMIT CHECK]', [
                    'tenant_id' => $tenant->id,
                    'limit_reached' => $limitReached
                ]);

            } else {
                Log::error('❌ [BRANCH PAGE] TENANT NOT FOUND', [
                    'tenant_id' => $tenantId,
                    'user_id' => $user->id
                ]);
            }
        } else {
            Log::error('❌ [BRANCH PAGE] NO AUTH USER');
        }

        return [
            CreateAction::make()
                ->disabled(fn () => $limitReached)
                ->color(fn () => $limitReached ? 'danger' : 'primary')
                ->tooltip(fn () => $this->getLimitMessage($limitReached)),
        ];
    }

    protected function getLimitMessage(bool $limitReached): string
    {
        Log::info('💬 [BRANCH PAGE] LIMIT MESSAGE', [
            'limit_reached' => $limitReached
        ]);

        if (!$limitReached) {
            return 'Create a new branch';
        }

        return 'Branch limit reached for your current plan. Please upgrade your subscription to add more branches.';
    }
}