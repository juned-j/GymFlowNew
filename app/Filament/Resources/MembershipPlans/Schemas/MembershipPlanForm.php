<?php

namespace App\Filament\Resources\MembershipPlans\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;


class MembershipPlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
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

                    Toggle::make('has_trainer_support')
                        ->label('Includes Trainer Support')
                        ->default(false)
                        ->inline(false),
                ])->columns(2),
        ]);
    }
}
