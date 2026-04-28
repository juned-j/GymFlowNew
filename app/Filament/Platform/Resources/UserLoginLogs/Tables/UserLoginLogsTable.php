<?php

namespace App\Filament\Platform\Resources\UserLoginLogs\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Helpers\DateHelper;

class UserLoginLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('email')
                    ->label('Email')
                    ->width('150px'),

                TextColumn::make('ip_address'),

                TextColumn::make('status')
                    ->searchable(),

                TextColumn::make('failure_reason'),
TextColumn::make('logged_in_at')
    ->label('Login Time')
    ->dateTime('d M Y, h:i A'),
            ])
            ->defaultSort('logged_in_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                // ❌ edit hata diya kyuki report hai
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}