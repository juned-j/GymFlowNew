<?php

namespace App\Filament\Platform\Widgets;

use App\Models\Subscription;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = auth()->user();

        \Log::info('📊 Widget Loaded', [
            'auth_check' => auth()->check(),
            'user' => $user?->id,
        ]);

        if (!$user) {

            \Log::warning('❌ No authenticated user');

            return [];
        }

        // ✅ GET TENANT ID
        $tenantId = $user->getTenantId();

        \Log::info('📊 Tenant Debug', [
            'user_id' => $user->id,
            'tenant_id' => $tenantId,
        ]);

        if (!$tenantId) {

            \Log::warning('❌ No tenant ID found');

            return [];
        }

        // ✅ CHECK ALL SUBSCRIPTIONS
        $allSubscriptions = Subscription::query()->get();

        \Log::info('📊 ALL SUBSCRIPTIONS', [
            'count' => $allSubscriptions->count(),
            'data' => $allSubscriptions->map(function ($sub) {
                return [
                    'id' => $sub->id,
                    'tenant_id' => $sub->tenant_id,
                    'status' => $sub->status,
                ];
            })->toArray(),
        ]);

        // ✅ FILTERED SUBSCRIPTIONS
        $tenantSubscriptions = Subscription::query()
            ->where('tenant_id', $tenantId)
            ->get();

        \Log::info('📊 FILTERED SUBSCRIPTIONS', [
            'tenant_id' => $tenantId,
            'count' => $tenantSubscriptions->count(),
            'ids' => $tenantSubscriptions->pluck('id')->toArray(),
        ]);

        /**
         * ACTIVE SUBSCRIPTIONS
         */
        $activeSubscriptions = Subscription::query()
            ->where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->count();

        \Log::info('📊 ACTIVE SUBSCRIPTIONS COUNT', [
            'tenant_id' => $tenantId,
            'active_count' => $activeSubscriptions,
        ]);

        /**
         * MONTHLY REVENUE
         */
        $monthlyRevenueSubscriptions = Subscription::query()
            ->where('tenant_id', $tenantId)
            ->where('status', 'active')
            ->whereMonth('created_at', now()->month)
            ->with('membershipPlan')
            ->get();

        \Log::info('📊 MONTHLY REVENUE SUBSCRIPTIONS', [
            'tenant_id' => $tenantId,
            'count' => $monthlyRevenueSubscriptions->count(),
            'subscription_ids' => $monthlyRevenueSubscriptions->pluck('id')->toArray(),
        ]);

        $monthlyRevenue = $monthlyRevenueSubscriptions
            ->sum(fn ($sub) => $sub->membershipPlan?->price ?? 0);

        \Log::info('📊 MONTHLY REVENUE', [
            'tenant_id' => $tenantId,
            'amount' => $monthlyRevenue,
        ]);

        return [

            Stat::make('Tenant ID', $tenantId)
                ->description('Current tenant')
                ->color('primary')
                ->icon('heroicon-o-building-office'),

            Stat::make('Active Subscriptions', $activeSubscriptions)
                ->description('Currently active members')
                ->color('success')
                ->icon('heroicon-o-check-badge'),

            Stat::make('Monthly Revenue', '₹ ' . number_format($monthlyRevenue))
                ->description('Calculated from active plans')
                ->color('warning')
                ->icon('heroicon-o-currency-rupee'),
        ];
    }
}