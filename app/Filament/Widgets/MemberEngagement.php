<?php

namespace App\Filament\Widgets;

use App\Models\Tenant;
use App\Models\WorkoutSession;
use App\Models\UserTenantRole;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MemberEngagement extends StatsOverviewWidget
{
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        $authUser = auth()->user();

        if (!$authUser) {
            return [];
        }

        /**
         * CURRENT TENANT ID
         */
        $tenantId = $authUser->getTenantId();

        if (!$tenantId) {
            return [];
        }

        /**
         * TOTAL MEMBERS
         */
        $totalMembers = UserTenantRole::query()
            ->where('tenant_id', $tenantId)
            ->count();

        /**
         * ACTIVE MEMBERS
         */
        $activeMembers = WorkoutSession::query()
            ->where('tenant_id', $tenantId)
            ->where('created_at', '>=', now()->subDays(7))
            ->distinct('user_id')
            ->count('user_id');

        /**
         * INACTIVE MEMBERS
         */
        $inactiveMembers = max($totalMembers - $activeMembers, 0);

        /**
         * CONSISTENCY %
         */
        $consistency = $totalMembers > 0
            ? round(($activeMembers / $totalMembers) * 100, 2)
            : 0;

        return [

            Stat::make('Active Members (7 days)', $activeMembers)
                ->description('Users who trained recently')
                ->color('success'),

            Stat::make('Inactive Members (>7 days)', $inactiveMembers)
                ->description('No recent workout activity')
                ->color('danger'),

            Stat::make('Workout Consistency', $consistency . '%')
                ->description('Overall engagement rate')
                ->color('primary'),
        ];
    }
}