<?php

namespace App\Filament\Resources\Branches\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Forms\Components\Section;

class BranchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Wizard::make([
                // Step 1: Identity
                Step::make('Branch Identity')
                    ->description('Basic identification for this gym location')
                    ->schema([
                        // Automatically fetch and set Tenant ID
                        Hidden::make('tenant_id')
                            ->default(function () {
                                $user = auth()->user();
                                if (! $user) {
                                    throw new \Exception('User not authenticated');
                                }
                                // Using your specific logic to find the tenant_id via the 'owner' role
                                $tenantId = $user->roles()
                                    ->whereHas('role', fn($q) => $q->where('name', 'owner'))
                                    ->value('tenant_id');

                                if (! $tenantId) {
                                    // Fallback to your model's helper if the owner check fails
                                    $tenantId = $user->getTenantId();
                                }

                                if (! $tenantId) {
                                    throw new \Exception('Tenant ID not found for user');
                                }
                                return $tenantId;
                            })
                            ->dehydrated(true),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')
                                    ->label('Branch Name')
                                    ->placeholder('e.g. Downtown Fitness')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('email')
                                    ->label('Contact Email')
                                    ->email()
                                    ->placeholder('branch@example.com'),

                                TextInput::make('phone')
                                    ->label('Phone Number')
                                    ->tel(),

                                Toggle::make('is_active')
                                    ->label('Operational Status')
                                    ->default(true),
                            ]),
                    ]),

                // Step 2: Location
                Step::make('Location Details')
                    ->description('Physical address')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('address')
                                    ->columnSpan(2)
                                    ->placeholder('Street address'),

                                TextInput::make('city')
                                    ->required()
                                    ->placeholder('City'),

                                TextInput::make('state')
                                    ->placeholder('State/Province'),

                                TextInput::make('postal_code')
                                    ->placeholder('Zip/Postal Code'),

                                TextInput::make('country')
                                    ->default('India')
                                    ->required(),
                            ]),
                    ]),
            ])
                ->columnSpanFull() // Ensures the wizard takes up the full width
        ]);
    }
}
