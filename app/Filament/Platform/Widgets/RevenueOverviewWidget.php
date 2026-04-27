<?php

namespace App\Filament\Platform\Widgets;

use App\Models\Subscription;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class RevenueOverviewWidget extends ChartWidget
{
    protected ?string $heading = 'Revenue Overview';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now();

        $dates = collect();

        while ($startDate <= $endDate) {
            $dates->push($startDate->toDateString());
            $startDate->addDay();
        }

        return [
            'labels' => $dates->toArray(),

            'datasets' => [
                [
                    'label' => 'Revenue Overview',

                    'data' => $dates->map(function ($date) {
                        return (float) Subscription::query()
                            ->whereDate('created_at', $date)
                            ->whereIn('status', ['active', 'trialing'])
                            ->with('membershipPlan')
                            ->get()
                            ->sum(fn ($sub) => $sub->membershipPlan?->price ?? 0);
                    })->toArray(),

                    'borderColor' => '#6366F1',
                    'backgroundColor' => 'rgba(99, 102, 241, 0.15)',
                    'tension' => 0.5,
                    'borderWidth' => 3,
                    'fill' => true,

                    'pointRadius' => 0,
                    'pointHoverRadius' => 5,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}