<?php

namespace App\Filament\Resources\WorkoutExercises;

use App\Filament\Resources\WorkoutExercises\Pages\CreateWorkoutExercise;
use App\Filament\Resources\WorkoutExercises\Pages\EditWorkoutExercise;
use App\Filament\Resources\WorkoutExercises\Pages\ListWorkoutExercises;
use App\Filament\Resources\WorkoutExercises\Schemas\WorkoutExerciseForm;
use App\Filament\Resources\WorkoutExercises\Tables\WorkoutExercisesTable;
use App\Models\WorkoutExercise;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WorkoutExerciseResource extends Resource
{
    protected static ?string $model = WorkoutExercise::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $recordTitleAttribute = 'exercise.name';

    public static function form(Schema $schema): Schema
    {
        return WorkoutExerciseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkoutExercisesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWorkoutExercises::route('/'),
            'create' => CreateWorkoutExercise::route('/create'),
            'edit' => EditWorkoutExercise::route('/{record}/edit'),
        ];
    }
}
