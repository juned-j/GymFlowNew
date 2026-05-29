<?php

namespace App\Filament\Resources\GymPartnerships\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Schema;

class GymPartnershipForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('tenant_id')
                    ->default(fn() => auth()->user()?->getTenantId()),

                Placeholder::make('home_gym')
                    ->label('Home Gym')
                    ->content(function () {
                        $tenantId = auth()->user()?->getTenantId();
                        if (! $tenantId) {
                            return 'No Gym Assigned';
                        }
                        return Tenant::find($tenantId)?->name ?? 'No Gym Found';
                    }),

                Select::make('partner_tenant_id')
                    ->label('Partner Gym')
                    ->relationship('partnerTenant', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'active' => 'Active',
                        'rejected' => 'Rejected',
                        'suspended' => 'Suspended',
                    ])
                    ->default('pending')
                    ->disabled(),

                TextInput::make('revenue_share_percent')
                    ->numeric()
                    ->default(50)
            ]);
    }
}
