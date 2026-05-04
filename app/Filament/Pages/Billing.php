<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Plan;
use App\Models\Tenant;

class Billing extends Page
{
protected string $view = 'filament.pages.billing';
    protected static ?string $slug = 'billing';

    public Tenant $tenant;
    public $plans;

    public function mount(): void
    {
        $this->tenant = auth()->user()->tenant;

        if (! $this->tenant) {
            abort(403, 'Tenant not found');
        }

        $this->plans = Plan::where('is_active', 1)
            ->orderBy('monthly_price')
            ->get();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}