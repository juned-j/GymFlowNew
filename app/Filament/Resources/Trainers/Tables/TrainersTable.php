<?php

namespace App\Filament\Resources\Trainers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class TrainersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('roles')
                    ->label('Branch')
                    ->formatStateUsing(function ($record) {
                        return $record->roles
                            ->where('role.name', 'trainer') // 👈 Filter only trainer roles
                            ->pluck('branch.name')
                            ->filter()
                            ->unique() // 👈 Remove duplicates
                            ->join(', ');
                    }),
                TextColumn::make('trainerProfile.specialization')
                    ->label('Specialization'),

                TextColumn::make('trainerProfile.status')
                    ->badge(),

                TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                SelectFilter::make('branch_id')
                    ->label('Branch')
                    ->options(function () {
                        return \App\Models\Branch::where(
                            'tenant_id',
                            auth()->user()->getTenantId()
                        )->pluck('name', 'id');
                    })
                    ->query(function ($query, $value) {
                        $query->whereHas('roles', function ($q) use ($value) {
                            $q->where('branch_id', $value);
                        });
                    }),
            ]);
    }
}
