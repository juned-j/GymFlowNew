<?php

namespace App\Filament\Resources\MembershipPlans\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;

class MembershipPlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            // Automatically inject the tenant_id here
            Hidden::make('tenant_id')
                ->default(fn() => auth()->user()->getTenantId()),
            Section::make('Plan Details')
                ->description('Define the pricing and access for this membership.')
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->placeholder('e.g., Monthly Pro'),

                    Select::make('billing_period')
                        ->options([
                            'month' => 'Monthly',
                            'year' => 'Yearly',
                        ])
                        ->required()
                        ->default('month'),

                    TextInput::make('price')
                        ->numeric()
                        ->required()
                        ->prefix(fn() => auth()->user()->getTenantCurrencySymbol() ?? '$') // Dynamic Currency
                        ->hint('Set to 0.00 for a Free Plan'),

                    TextInput::make('workout_plan_limit')
                        ->label('Workout Plan Limit')
                        ->numeric()
                        ->default(1)
                        ->minValue(1)
                        ->required(),

                    TextInput::make('stripe_product_id')
                        ->label('Stripe Product ID')
                        ->nullable()
                        ->maxLength(255),

                    TextInput::make('stripe_price_id')
                        ->label('Stripe Price ID')
                        ->nullable()
                        ->maxLength(255),

                    Toggle::make('has_trainer_support')
                        ->label('Includes Trainer Support')
                        ->default(false)
                        ->inline(false),
                    Toggle::make('is_active')
                        ->label('Active Plan')
                        ->default(true)
                        ->inline(false),
                ])->columns(2)
                ->columnSpanFull(),
            Section::make('Feature Access')
                ->description('Manage advanced plan capabilities.')
                ->schema([
                    Toggle::make('has_trainer_support')
                        ->label('Includes Trainer Support')
                        ->columnSpanFull(),

                    // The dynamic JSON features field
                    Repeater::make('features')
                        // ->grid(2)
                        ->schema([
                            TextInput::make('feature_name')
                                ->label('Feature Label')
                                ->placeholder('e.g. Diet Plan')
                                ->required(),

                            Toggle::make('is_enabled')
                                ->label('Enabled')
                                ->default(true),
                        ])
                        ->itemLabel(fn(array $state): ?string => $state['feature_name'] ?? null)
                        ->collapsible()
                        ->columnSpanFull()
                        ->helperText('Add custom feature flags for your mobile app to check.'),
                ])->columnSpanFull(),
        ]);
    }
}
