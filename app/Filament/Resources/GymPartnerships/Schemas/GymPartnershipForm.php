<?php

namespace App\Filament\Resources\GymPartnerships\Schemas;

use App\Models\Tenant;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GymPartnershipForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // 🏠 HOME GYM (AUTO-SET IN REAL SYSTEM)
                Select::make('tenant_id')
                    ->label('Home Gym')
                    ->relationship('tenant', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->default(fn () => auth()->user()->gym_id)
                    ->disabled(),

                // 🤝 PARTNER GYM DROPDOWN
                Select::make('partner_tenant_id')
                    ->label('Partner Gym')
                    ->relationship('partnerTenant', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                // 📊 STATUS (AUTO CONTROLLED, NOT MANUAL)
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'active' => 'Active',
                        'rejected' => 'Rejected',
                        'suspended' => 'Suspended',
                    ])
                    ->default('pending')
                    ->disabled(),

                // 💰 REVENUE SPLIT
                TextInput::make('revenue_share_percent')
                    ->label('Partner Revenue %')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->default(50)
                    ->required(),
            ]);
    }
}