<?php

namespace App\Filament\Widgets;

use App\Models\Member;
use App\Models\Trainer;
use App\Models\Subscription;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    public static bool $isLazy = false;

    protected function getStats(): array
    {
        $user = auth()->user();

        // ✅ SAFE FALLBACK
        if (!$user) {

            return [
                Stat::make('👥 Total Members', 0),
                Stat::make('🧑‍🏫 Total Trainers', 0),
                Stat::make('💳 Active Subscriptions', 0),
                Stat::make('💰 Monthly Revenue', '₹ 0'),
            ];
        }

        // ✅ SAFE TENANT ID
        $tenantId = $user->getTenantId();

        if (!$tenantId) {

            return [
                Stat::make('👥 Total Members', 0),
                Stat::make('🧑‍🏫 Total Trainers', 0),
                Stat::make('💳 Active Subscriptions', 0),
                Stat::make('💰 Monthly Revenue', '₹ 0'),
            ];
        }

        /**
         * MEMBERS
         */
        $totalMembers = Member::query()
            ->where('tenant_id', $tenantId)
            ->count();

        /**
         * TRAINERS
         */
     $totalTrainers = Trainer::query()
    ->whereHas('user', function ($query) use ($tenantId) {

        $query->whereHas('tenantRoles', function ($q) use ($tenantId) {
            $q->where('tenant_id', $tenantId);
        });

    })
    ->count();

        /**
         * ACTIVE SUBSCRIPTIONS
         */
        $activeSubscriptions = Subscription::query()
            ->where('tenant_id', $tenantId)
            ->whereIn('status', ['active', 'trialing'])
            ->count();

        /**
         * MONTHLY REVENUE
         */
        $monthlyRevenue = Subscription::query()
            ->where('subscriptions.tenant_id', $tenantId)
            ->whereIn('subscriptions.status', ['active', 'trialing'])
            ->join(
                'membership_plans',
                'subscriptions.membership_plan_id',
                '=',
                'membership_plans.id'
            )
            ->sum('membership_plans.price');

        return [

            Stat::make('👥 Total Members', $totalMembers)
                ->description('All registered gym members')
                ->color('primary'),

            Stat::make('🧑‍🏫 Total Trainers', $totalTrainers)
                ->description('Active trainers in system')
                ->color('success'),

            Stat::make('💳 Active Subscriptions', $activeSubscriptions)
                ->description('Currently active & trialing plans')
                ->color('warning'),

            Stat::make('💰 Monthly Revenue', '₹ ' . number_format($monthlyRevenue, 2))
                ->description('Revenue from active subscriptions')
                ->color('success'),
        ];
    }
}