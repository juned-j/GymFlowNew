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

    protected int | string | array $columnSpan = 'full';

    // ✅ Disable lazy loading
    public static bool $isLazy = false;

    protected function getData(): array
    {
        \Log::info('📈 RevenueGrowth Widget Loaded');

        $user = auth()->user();

        \Log::info('📈 Auth User', [
            'user_id' => $user?->id,
        ]);

        if (!$user) {

            \Log::warning('❌ No authenticated user');

            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        /**
         * ✅ GET TENANT ID
         */
        $tenantId = $user->getTenantId();

        \Log::info('📈 Tenant Info', [
            'tenant_id' => $tenantId,
        ]);

        if (!$tenantId) {

            \Log::warning('❌ No tenant ID found');

            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        /**
         * 📅 DATE RANGE
         */
        $startDate = Carbon::create(2025, 1, 1);
        $endDate = Carbon::today();

        $dates = collect();

        while ($startDate <= $endDate) {
            $dates->push($startDate->toDateString());
            $startDate->addDay();
        }

        $labels = $dates->toArray();

        /**
         * 💰 TENANT BASED REVENUE
         */
        $revenue = $dates->map(function ($date) use ($tenantId) {

            $amount = (float) Subscription::query()
                ->where('subscriptions.tenant_id', $tenantId)
                ->whereDate('subscriptions.created_at', $date)
                ->whereIn('subscriptions.status', ['active', 'trialing'])
                ->leftJoin(
                    'membership_plans',
                    'subscriptions.membership_plan_id',
                    '=',
                    'membership_plans.id'
                )
                ->sum(DB::raw('COALESCE(membership_plans.price, 0)'));

            // \Log::info('📈 Daily Revenue', [
            //     'date' => $date,
            //     'amount' => $amount,
            // ]);

            return $amount;

        })->toArray();

        return [

            'labels' => $labels,

            'datasets' => [
                [
                    'label' => 'Revenue',

                    'data' => $revenue,

                    'borderColor' => '#6366F1',
                    'backgroundColor' => 'rgba(99, 102, 241, 0.15)',

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