<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\WorkoutSession;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MemberEngagement extends StatsOverviewWidget
{

    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        $totalMembers = User::count();

        $activeMembers = WorkoutSession::where('created_at', '>=', now()->subDays(7))
            ->distinct('user_id')
            ->count('user_id');

        $inactiveMembers = $totalMembers - $activeMembers;

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