<?php

namespace App\Filament\Resources\GymPartnerships\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Services\GymPartnershipService;

class GymPartnershipsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tenant_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('partner_tenant_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('revenue_share_percent')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('accept')
                    ->visible(fn($record) => $record->status === 'pending')
                    ->color('success')
                    ->action(
                        fn($record, GymPartnershipService $service) =>
                        $service->accept($record)
                    ),
                Action::make('suspend')
                    ->visible(fn($record) => $record->status === 'active')
                    ->color('warning')
                    ->action(
                        fn($record, GymPartnershipService $service) =>
                        $service->suspend($record)
                    )
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
