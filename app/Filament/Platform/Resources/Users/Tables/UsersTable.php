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
              ->searchable(isIndividual: true)
                    ->sortable(),

                TextColumn::make('email')
              ->searchable(isIndividual: true),
                
                       TextColumn::make('status')
                  
                    ->searchable(),

 TextColumn::make('roles')
    ->label('Role')
    ->formatStateUsing(function ($record) {
        return $record->roles
            ->pluck('role.name')
            ->unique()  
            ->filter()
            ->map(fn ($name) => ucfirst($name))
            ->implode('<br>');
    })
    ->html(),

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
