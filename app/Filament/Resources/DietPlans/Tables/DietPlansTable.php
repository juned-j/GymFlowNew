<?php

namespace App\Filament\Resources\DietPlans\Tables;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\{
    TextColumn,
    BadgeColumn,
    IconColumn
};
use Filament\Tables\Filters\SelectFilter;

class DietPlansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                // =============================
                // Name
                // =============================
                TextColumn::make('name')
                    ->searchable()
                      ->extraAttributes([
                        'style' => 'max-width: 200px; white-space: normal; word-wrap: break-word;',
                    ])
                    ->wrap()
                    ->sortable(),

                // =============================
                // Goal
                // =============================
                BadgeColumn::make('goal')
                    ->colors([
                        'danger'  => 'weight_loss',
                        'success' => 'muscle_gain',
                        'primary' => 'maintenance',
                    ])
                    ->formatStateUsing(fn ($state) => ucfirst(str_replace('_', ' ', $state)))
                    ->sortable(),

                // =============================
                // Meals Count (from repeater)
                // =============================
                TextColumn::make('meals_count')
                    ->counts('meals')
                    ->label('Meals')
                    ->sortable(),

                // =============================
                // Template Toggle
                // =============================
                IconColumn::make('is_template')
                    ->boolean()
                    ->label('Template'),

                // =============================
                // Created Date
                // =============================
                TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable(),

            ])

            ->filters([

                // Filter by Goal
                SelectFilter::make('goal')
                    ->options([
                        'weight_loss' => 'Weight Loss',
                        'muscle_gain' => 'Muscle Gain',
                        'maintenance' => 'Maintenance',
                    ]),

                // Filter Template
                SelectFilter::make('is_template')
                    ->options([
                        1 => 'Template',
                        0 => 'Custom',
                    ]),

            ])

            ->recordActions([
                EditAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}