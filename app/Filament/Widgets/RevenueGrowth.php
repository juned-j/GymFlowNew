<?php

namespace App\Filament\Widgets;

use App\Models\Subscription;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class RevenueGrowth extends ChartWidget
{
    protected ?string $heading = 'Revenue Overview';
    protected static ?int $sort = 2;
    

    protected function getData(): array
    {
        // 📅 Start from 1st Jan 2025 to today
        $startDate = Carbon::create(2025, 1, 1);
        $endDate = Carbon::today();

        $dates = collect();

        while ($startDate <= $endDate) {
            $dates->push($startDate->toDateString());
            $startDate->addDay();
        }

        $labels = $dates->toArray();

        // 💰 Revenue per day
        $revenue = $dates->map(function ($date) {
            return (float) Subscription::query()
                ->leftJoin('membership_plans', 'subscriptions.membership_plan_id', '=', 'membership_plans.id')
                ->whereDate('subscriptions.created_at', $date)
                ->whereIn('subscriptions.status', ['active', 'trialing'])
                ->sum(DB::raw('COALESCE(membership_plans.price, 0)'));
        })->toArray();

        return [
            'labels' => $labels,

            'datasets' => [
                [
                    'label' => 'Revenue',

                    'data' => $revenue,

                    // 🎨 Dribbble style
                    'borderColor' => '#6366F1',
                    'backgroundColor' => 'rgba(99, 102, 241, 0.15)',

                    // ✨ smooth curve
                    'tension' => 0.6,
                    'borderWidth' => 3,

                    'fill' => true,
                    'pointRadius' => 0,
                    'pointHoverRadius' => 6,

                    'spanGaps' => true,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,

            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],

            'interaction' => [
                'mode' => 'index',
                'intersect' => false,
            ],

            'scales' => [
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
                'y' => [
                    'grid' => [
                        'color' => 'rgba(0,0,0,0.05)',
                    ],
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}