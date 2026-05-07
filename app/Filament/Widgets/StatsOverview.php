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

    // ✅ FULL WIDTH
    protected int | string | array $columnSpan = 'full';

    // ✅ DISABLE LAZY LOADING FOR DEBUG
    public static bool $isLazy = false;

    protected function getStats(): array
    {
        \Log::info('📊 StatsOverview Widget Loaded');

        $user = auth()->user();

        \Log::info('📊 Auth User', [
            'user_id' => $user?->id,
        ]);

        if (!$user) {

            \Log::warning('❌ No authenticated user');

            return [];
        }

        // ✅ GET TENANT ID
        $tenantId = $user->getTenantId();

        \Log::info('📊 Tenant Info', [
            'tenant_id' => $tenantId,
        ]);

        if (!$tenantId) {

            \Log::warning('❌ No tenant ID found');

            return [];
        }

        /**
         * MEMBERS
         */
        $totalMembers = Member::query()
            ->where('tenant_id', $tenantId)
            ->count();

        \Log::info('📊 Total Members', [
            'count' => $totalMembers,
        ]);

        /**
         * TRAINERS
         */
        $totalTrainers = Trainer::query()
            ->where('tenant_id', $tenantId)
            ->count();

        \Log::info('📊 Total Trainers', [
            'count' => $totalTrainers,
        ]);

        /**
         * ACTIVE SUBSCRIPTIONS
         */
        $activeSubscriptions = Subscription::query()
            ->where('tenant_id', $tenantId)
            ->whereIn('status', ['active', 'trialing'])
            ->count();

        \Log::info('📊 Active Subscriptions', [
            'count' => $activeSubscriptions,
        ]);

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

        \Log::info('📊 Monthly Revenue', [
            'amount' => $monthlyRevenue,
        ]);

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