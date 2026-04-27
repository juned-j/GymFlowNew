<?php

namespace App\Filament\Platform\Widgets;

use App\Models\Branch;
use App\Models\Subscription;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = auth()->user();

       

        /**
         * TOTAL GYMSRevenueOverview
         */
        $totalGyms = Branch::query()->count();

        /**
         * ACTIVE SUBSCRIPTIONS
         */
        $activeSubscriptions = Subscription::query()
            ->where('status', 'active')
            ->count();

        /**
         * MONTHLY REVENUE (safe version)
         * NOTE: since no amount column, we calculate by plan price
         */
        $monthlyRevenue = Subscription::query()
            ->where('status', 'active')
            ->whereMonth('created_at', now()->month)
            ->with('membershipPlan')
            ->get()
            ->sum(fn ($sub) => $sub->membershipPlan?->price ?? 0);

        return [
            Stat::make('Total Gyms', $totalGyms)
                ->description('All gym branches in system')
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