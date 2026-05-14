<?php

namespace App\Filament\Widgets;

use App\Models\WorkoutSession;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class WorkoutActivity extends ChartWidget
{
    protected ?string $heading = 'Workout Consistency (%)';
    protected string $contentHeight = '250px';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $dates = collect();

        for ($i = 6; $i >= 0; $i--) {
            $dates->push(Carbon::today()->subDays($i)->toDateString());
        }

        $labels = $dates->toArray();

        $targetPerDay = 2;

        $consistency = $dates->map(function ($date) use ($targetPerDay) {

            $count = WorkoutSession::whereDate('start_time', $date)->count();

            $percent = ($count / $targetPerDay) * 100;

            return min(round($percent, 2), 100);
        })->toArray();

        return [
            'labels' => $labels,

            'datasets' => [
                [
                    'label' => 'Consistency %',
                    'data' => $consistency,
                    // 🔥 THICK & PREMIUM LINE
                    'borderColor' => '#6366F1',
                    'borderWidth' => 5,   // 👈 thicker line
                    'tension' => 0.6,
                    'pointRadius' => 3,   // 👈 visible points
                    'pointHoverRadius' => 7,
                    'pointBackgroundColor' => '#6366F1',
                    'fill' => false,
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
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],

                // 🎯 Tooltip with % label
                'tooltip' => [
                    'callbacks' => [
                        'label' => "function(context) {
                            return context.parsed.y + '% Consistency';
                        }",
                    ],
                    'displayColors' => false,
                ],
            ],

            'scales' => [
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
                'y' => [
                    'beginAtZero' => true,
                    'max' => 100,
                    'ticks' => [
                        'callback' => "function(value) {
                            return value + '%';
                        }"
                    ],
                ],
            ],

            // 🎯 smooth interaction feel
            'interaction' => [
                'mode' => 'index',
                'intersect' => false,
            ],
        ];
    }
}
