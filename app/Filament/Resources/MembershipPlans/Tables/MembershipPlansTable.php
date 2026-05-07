<?php

namespace App\Filament\Resources\MembershipPlans\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class MembershipPlansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()   ->searchable()
                          ->extraAttributes([
                        'style' => 'max-width: 200px; white-space: normal; word-wrap: break-word;',
                    ])
                    ->wrap()

                    ->sortable(),

                TextColumn::make('price')
                   
                    ->sortable(),

                TextColumn::make('billing_period')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'month' => 'info',
                        'year' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('workout_plan_limit')
                    ->label('Limits')
                    ->suffix(' Plans'),

                IconColumn::make('has_trainer_support')
                    ->label('Trainer')
                    ->boolean(),
            ])
            ->filters([
                //
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
