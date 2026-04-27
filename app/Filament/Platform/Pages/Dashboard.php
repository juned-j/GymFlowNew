<?php

namespace App\Filament\Platform\Pages;

use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use App\Filament\Platform\Widgets\StatsOverviewWidget;
use App\Filament\Platform\Widgets\RevenueOverviewWidget;

class Dashboard extends Page
{
    protected string $view = 'filament.platform.pages.dashboard';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;
protected function getHeaderWidgets(): array
{
    return [
     
  StatsOverviewWidget::class,
RevenueOverviewWidget::class


    ];
}
}
