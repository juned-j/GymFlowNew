<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class Dashboard extends Page
{
    protected string $view = 'filament.pages.dashboard';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;
    protected static ?int $navigationSort = -10;
    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\StatsOverview::class,
            \App\Filament\Widgets\RevenueGrowth::class,
            //  \App\Filament\Widgets\WorkoutActivity::class,
            \App\Filament\Widgets\MemberEngagement::class,


        ];
    }
    public function getHeader(): ?\Illuminate\Contracts\View\View
    {
        $user = auth()->user();
        $tenant = $user?->ownedTenant;

        \Log::info('Dashboard getHeader debug', [
            'user_id' => $user?->id,
            'user_exists' => $user !== null,
            'tenant' => $tenant,
            'tenant_id' => $tenant?->id,
            'tenant_name' => $tenant?->name,
            'tenant_logo_url' => $tenant?->logo_url,
            'tenant_app_settings' => $tenant?->app_settings,
        ]);

        return view('filament.pages.partials.tenant-header', [
            'tenant' => $tenant,
        ]);
    }
}
