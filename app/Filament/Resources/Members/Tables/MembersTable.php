<?php

namespace App\Filament\Resources\Members\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class MembersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Data from User Table
                TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),

                // Data from UserTenantRole (via User)
                TextColumn::make('user.roles.branch.name')
                    ->label('Branch')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->searchable(),

                TextColumn::make('user.roles.role.name')
                    ->label('Role')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => ucfirst($state)),

                // Data from Member Table
                TextColumn::make('goal')
                    ->toggleable(),

                TextColumn::make('gender')
                    ->toggleable(),

                TextColumn::make('dob')
                    ->label('Date of Birth')
                    ->date()
                    ->toggleable(),

                // Status from User table
                TextColumn::make('user.status')
                    ->label('Account Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        'pending' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->filters([
                // Add filters for branch or status here
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
