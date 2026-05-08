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
        return view('filament.pages.partials.tenant-header', [
            'tenant' => auth()->user()?->ownedTenant,
        ]);
    }
}
