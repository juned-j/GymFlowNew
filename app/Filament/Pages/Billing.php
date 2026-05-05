<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\SaasPlan; // Use the correct model
use App\Models\Tenant;

class Billing extends Page
{
    protected string $view = 'filament.pages.billing';
    protected static ?string $slug = 'billing';

    public $tenant;
    public $plans;

    public function mount(): void
    {
        $this->tenant = auth()->user()->tenant;

        if (! $this->tenant) {
            abort(403, 'Tenant not found');
        }

        // Use SaasPlan:: class to ensure $casts['features'] = 'array' works
        $this->plans = SaasPlan::where('is_active', 1)
            ->orderBy('price')
            ->get();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}