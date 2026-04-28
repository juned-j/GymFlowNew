<?php

namespace App\Filament\Platform\Resources\SaasPlans\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;

class SaasPlansTable
{
   public static function configure(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('name')->searchable(),

            TextColumn::make('slug')->toggleable(),

            TextColumn::make('price')
                ->money(fn ($record) => $record->currency ?? 'INR', true),

            TextColumn::make('currency'),

            TextColumn::make('billing_interval')->badge(),

            TextColumn::make('max_branches'),
            TextColumn::make('max_trainers'),
            TextColumn::make('max_members'),

            TextColumn::make('features')
                ->limit(30)
                ->toggleable(),

            TextColumn::make('stripe_product_id')
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('stripe_price_id')
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('stripe_plan_id')
                ->toggleable(isToggledHiddenByDefault: true),

            IconColumn::make('is_active')->boolean(),
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