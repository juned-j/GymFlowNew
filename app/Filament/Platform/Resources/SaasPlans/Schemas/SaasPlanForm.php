<?php

namespace App\Filament\Platform\Resources\SaasPlans\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use App\Models\Currency;


class SaasPlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),

                TextInput::make('price')
                    ->numeric()
                    ->required(),

             Select::make('currency')
    ->label('Currency')
    ->options(
        Currency::query()
            ->pluck('currency_name', 'currency_name')
            ->toArray()
    )
    ->searchable()
    ->required(),

                Select::make('billing_interval')
                    ->options([
                        'monthly' => 'Monthly',
                        'yearly' => 'Yearly',
                    ])
                    ->required(),

                TextInput::make('max_branches')
                    ->numeric()
                       ->required()
                    ->default(1),

                TextInput::make('max_trainers')
                    ->numeric()
                       ->required()
                    ->default(1),

                TextInput::make('max_members')
                    ->numeric()
                       ->required()
                    ->default(10),

                Textarea::make('features')
                    ->label('Features (JSON)')
                    ->helperText('Example: ["Feature 1", "Feature 2"]'),

                Toggle::make('is_active')
                    ->default(true),

                TextInput::make('stripe_product_id'),
                TextInput::make('stripe_price_id'),
                TextInput::make('stripe_plan_id'),
            ]);
    }
}