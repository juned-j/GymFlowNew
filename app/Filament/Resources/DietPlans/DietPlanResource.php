<?php

namespace App\Filament\Resources\DietPlans;

use App\Filament\Resources\DietPlans\Pages\CreateDietPlan;
use App\Filament\Resources\DietPlans\Pages\EditDietPlan;
use App\Filament\Resources\DietPlans\Pages\ListDietPlans;
use App\Filament\Resources\DietPlans\Schemas\DietPlanForm;
use App\Filament\Resources\DietPlans\Tables\DietPlansTable;
use App\Models\DietPlan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DietPlanResource extends Resource
{
    protected static ?string $model = DietPlan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return DietPlanForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DietPlansTable::configure($table);
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
            'index' => ListDietPlans::route('/'),
            'create' => CreateDietPlan::route('/create'),
            'edit' => EditDietPlan::route('/{record}/edit'),
        ];
    }
}
