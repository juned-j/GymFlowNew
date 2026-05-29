<?php

namespace App\Filament\Resources\GymPartnerships\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class GymPartnershipForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tenant_id')
                    ->required()
                    ->numeric(),
                TextInput::make('partner_tenant_id')
                    ->required()
                    ->numeric(),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                TextInput::make('revenue_share_percent')
                    ->required()
                    ->numeric()
                    ->default(50),
            ]);
    }
}
