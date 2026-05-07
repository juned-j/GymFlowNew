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

    // 👇 THIS MAKES IT FULL WIDTH (IMPORTANT)
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $totalMembers = Member::count();
        $totalTrainers = Trainer::count();

        $activeSubscriptions = Subscription::whereIn('status', ['active', 'trialing'])->count();

        $monthlyRevenue = Subscription::whereIn('status', ['active', 'trialing'])
            ->join('membership_plans', 'subscriptions.membership_plan_id', '=', 'membership_plans.id')
            ->sum('membership_plans.price');

        $avgRevenue = $activeSubscriptions > 0
            ? $monthlyRevenue / $activeSubscriptions
            : 0;

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