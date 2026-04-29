<?php

namespace App\Filament\Resources\DietPlans\Schemas;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Forms\Components\{
    TextInput,
    Textarea,
    Select,
    Toggle,
    Repeater,
    TimePicker
};

class DietPlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // =============================
                // Diet Plan Basic Info
                // =============================

                TextInput::make('name')
                    ->required()
                      ->maxLength(50),

                Textarea::make('description'),

                Select::make('goal')
                    ->options([
                        'weight_loss' => 'Weight Loss',
                        'muscle_gain' => 'Muscle Gain',
                        'maintenance' => 'Maintenance',
                    ]),

                Toggle::make('is_template')
                    ->default(true),

                // =============================
                // Meals Repeater ✅ FIXED
                // =============================

                Repeater::make('meals')
                    ->relationship('meals') // 👈 IMPORTANT (explicit)
                    ->label('Diet Plan Meals')
                    ->schema([

                        Select::make('day_of_week')
                            ->label('Day')
                            ->options([
                                1 => 'Monday',
                                2 => 'Tuesday',
                                3 => 'Wednesday',
                                4 => 'Thursday',
                                5 => 'Friday',
                                6 => 'Saturday',
                                7 => 'Sunday',
                            ])
                            ->required(),

                        Select::make('meal_type')
                            ->options([
                                'breakfast' => 'Breakfast',
                                'lunch'     => 'Lunch',
                                'dinner'    => 'Dinner',
                                'snack'     => 'Snack',
                            ])
                            ->required(),

                        TextInput::make('recipe_name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('total_calories')
                            ->numeric()
                            ->default(0),

                        TimePicker::make('suggested_time')
                            ->seconds(false),

                        // ✅ Ingredients (array fix)
                        Textarea::make('ingredients')
                            ->helperText('Enter JSON or comma separated')
                            ->columnSpanFull()
                            ->dehydrateStateUsing(fn ($state) => 
                                is_array($state) ? $state : array_map('trim', explode(',', $state))
                            ),

                        // ✅ Macros (JSON fix)
                        Textarea::make('macros')
                            ->helperText('Example: {"protein":"20g","carbs":"40g"}')
                            ->columnSpanFull()
                            ->dehydrateStateUsing(fn ($state) => 
                                is_array($state) ? $state : json_decode($state, true)
                            ),

                    ])
                    ->columns(2)
                    ->defaultItems(1)
                    ->collapsible()
                    ->cloneable()
                   
                    ->columnSpanFull(),

            ])
            ->columns(2);
    }
}