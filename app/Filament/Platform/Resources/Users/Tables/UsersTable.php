<?php

namespace App\Filament\Platform\Resources\Users\Tables;

use Filament\Tables\Table;

use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;

use Filament\Actions\Action;


class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
              ->searchable(isIndividual: true)
                             ->extraAttributes([
                        'style' => 'max-width: 200px; white-space: normal; word-wrap: break-word;',
                    ])
                    ->wrap()
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
    ->placeholder('-')
    ->badge() 
    ->listWithLineBreaks() 
    ->separator(',')
    ->formatStateUsing(fn ($state) => ucfirst($state)) ,

                TextColumn::make('roles.branch.name')
                    ->label('Branch')
                                  ->searchable(isIndividual: true)

                    ->placeholder('-'),

             TextColumn::make('created_at')
    ->date()
    ->sortable(),
            ])
           ->recordActions([
  EditAction::make(),

   \Filament\Actions\Action::make('toggle_status')
->label(fn ($record) => $record->status === 'active' ? 'Block' : 'Activate')
    
    ->icon(fn ($record) => $record->status === 'active' 
        ? 'heroicon-o-lock-closed' 
        : 'heroicon-o-lock-open'
    )
    
    ->color(fn ($record) => $record->status === 'active' ? 'danger' : 'success')
    
    ->requiresConfirmation()
    ->action(function ($record) {
        $record->update([
            'status' => $record->status === 'active' ? 'inactive' : 'active', 
        ]);

        Notification::make()
            ->title('User status updated')
            ->success()
            ->send();
    }),
])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
