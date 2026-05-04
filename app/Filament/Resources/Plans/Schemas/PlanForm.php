<?php

namespace App\Filament\Resources\Plans\Schemas;

// Use the Schema-specific component namespace
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Schema;

class PlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            
            // Grid layout instead of Section
            Grid::make(2)
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(50),

                    TextInput::make('slug')
                        ->required()
                        ->maxLength(50),

                    Textarea::make('description')
                        ->rows(4)
                        ->columnSpanFull(),

                    TextInput::make('monthly_price')
                        ->label('Monthly Price')
                        ->numeric()
                        ->required()
                        ->minValue(0),

                    TextInput::make('annual_price')
                        ->label('Annual Price')
                        ->numeric()
                        ->required()
                        ->minValue(0),
                ]),

            Grid::make(3)
                ->schema([
                    Toggle::make('is_custom')
                        ->label('Custom Plan'),
                    
                    Toggle::make('is_active')
                        ->label('Active')
                        ->default(true),
                ]),

            Grid::make(3)
                ->schema([
                    TextInput::make('stripe_product_id'),
                    TextInput::make('stripe_monthly_price_id'),
                    TextInput::make('stripe_annual_price_id'),
                ]),

            Repeater::make('features')
                ->schema([
                    TextInput::make('label')
                        ->required()
                        ->maxLength(255),
                ])
                ->addActionLabel('Add Feature')
                ->columnSpanFull(),
        ]);
    }
}