<?php

namespace App\Filament\Resources\Members\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Filters\SelectFilter;
use App\Models\Branch;

class MembersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Name')
                    ->searchable(),

                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),

                TextColumn::make('branch.name')
                    ->label('Branch'),

                TextColumn::make('gender'),

                TextColumn::make('weight')
                    ->suffix(' kg'),

                TextColumn::make('goal')
                    ->limit(20),

                TextColumn::make('status')
                    ->badge(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('branch_id')
                    ->label('Branch')
                    ->options(function () {
                        return \App\Models\Branch::where('tenant_id', session('tenant_id'))
                            ->pluck('name', 'id');
                    })
                    ->query(function ($query, $value) {
                        $query->whereHas('user.roles', function ($q) use ($value) {
                            $q->where('branch_id', $value);
                        });
                    }),

                SelectFilter::make('city')
                    ->options(
                        fn() => \App\Models\Branch::query()
                            ->where('tenant_id', session('tenant_id'))
                            ->distinct()
                            ->pluck('city', 'city')
                    ),
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
