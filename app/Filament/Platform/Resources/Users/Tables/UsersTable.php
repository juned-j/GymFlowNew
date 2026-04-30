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

    TextColumn::make('roles.role.name')
    ->label('Role')
    ->badge()
    ->separator(',')
    ->listWithLineBreaks() 
    ->formatStateUsing(fn ($state) => ucfirst($state))
    ->color('primary') ,

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
