<?php

namespace App\Filament\Resources\WorkoutPlans;

use App\Filament\Resources\WorkoutPlans\Pages\CreateWorkoutPlan;
use App\Filament\Resources\WorkoutPlans\Pages\EditWorkoutPlan;
use App\Filament\Resources\WorkoutPlans\Pages\ListWorkoutPlans;
use App\Filament\Resources\WorkoutPlans\Schemas\WorkoutPlanForm;
use App\Filament\Resources\WorkoutPlans\Tables\WorkoutPlansTable;
use App\Models\WorkoutPlan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WorkoutPlanResource extends Resource
{
    protected static ?string $model = WorkoutPlan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return WorkoutPlanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkoutPlansTable::configure($table);
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
            'index' => ListWorkoutPlans::route('/'),
            'create' => CreateWorkoutPlan::route('/create'),
            'edit' => EditWorkoutPlan::route('/{record}/edit'),
        ];
    }
}
