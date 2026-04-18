<?php

namespace App\Filament\Resources\Workouts\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Tables\Table;

class ExercisesRelationManager extends RelationManager
{
    protected static string $relationship = 'exercises';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('exercise.name')
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('exercise.name')
                    ->label('Exercise Name')
                    ->searchable(),

                TextInputColumn::make('sets_target')
                    ->label('Sets'),

                TextInputColumn::make('reps_target')
                    ->label('Reps'),
            ])
            ->headerActions([
                // Use CreateAction instead of AttachAction
                CreateAction::make()
                    ->label('Add Exercise')
                    ->modalHeading('Add Exercise to Workout')
                    ->form([
                        // Dropdown to pick the exercise from the global library
                        Select::make('exercise_id')
                            ->relationship('exercise', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('sets_target')
                            ->numeric()
                            ->default(3)
                            ->required(),

                        TextInput::make('reps_target')
                            ->numeric()
                            ->default(10)
                            ->required(),
                    ])
                    // Ensure the tenant_id is set when adding an exercise to a workout
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['tenant_id'] = auth()->user()->latest_tenant_id;
                        return $data;
                    }),
            ])
            ->actions([
                // Use DeleteAction instead of DetachAction
                DeleteAction::make()
                    ->label('Remove'),
            ]);
    }
}
