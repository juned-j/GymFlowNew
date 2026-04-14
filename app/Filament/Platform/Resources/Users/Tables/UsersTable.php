<?php

namespace App\Filament\Platform\Resources\Users\Tables;

use Filament\Tables\Table;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->searchable(),

                TextColumn::make('roles.role')
                    ->label('Role')
                    ->badge()
                    ->formatStateUsing(fn($state) => ucfirst($state)),

                TextColumn::make('roles.tenant.name')
                    ->label('Tenant')
                    ->placeholder('-'),

                TextColumn::make('roles.branch.name')
                    ->label('Branch')
                    ->placeholder('-'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
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
