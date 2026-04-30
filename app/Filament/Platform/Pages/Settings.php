<?php

namespace App\Filament\Platform\Pages;

use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class Settings extends Page
{
    protected string $view = 'filament.platform.pages.settings';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog;

    protected static ?string $navigationLabel = 'Settings';

    protected static ?string $title = '';
}
