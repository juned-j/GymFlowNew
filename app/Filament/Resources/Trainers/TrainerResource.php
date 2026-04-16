<?php

namespace App\Filament\Resources\Trainers;

use App\Filament\Resources\Trainers\Pages\CreateTrainer;
use App\Filament\Resources\Trainers\Pages\EditTrainer;
use App\Filament\Resources\Trainers\Pages\ListTrainers;
use App\Filament\Resources\Trainers\Schemas\TrainerForm;
use App\Filament\Resources\Trainers\Tables\TrainersTable;
use App\Models\Trainer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TrainerResource extends Resource
{
    protected static ?string $model = \App\Models\User::class;
    protected static ?string $navigationLabel = 'Trainers';
    protected static ?string $modelLabel = 'Trainer';
    protected static ?string $pluralModelLabel = 'Trainers';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return TrainerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TrainersTable::configure($table);
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
            'index' => ListTrainers::route('/'),
            'create' => CreateTrainer::route('/create'),
            'edit' => EditTrainer::route('/{record}/edit'),
        ];
    }
    // public static function getEloquentQuery(): Builder
    // {
    //     $user = auth()->user();

    //     // 1. Get the base query and eager load profiles to prevent missing rows
    //     $query = parent::getEloquentQuery()->with(['trainerProfile', 'roles.branch']);

    //     // 2. If the user is a Super Admin, show ALL trainers across all tenants
    //     if ($user->isSuperAdmin()) {
    //         return $query->whereHas('roles', function ($q) {
    //             $q->whereHas('role', fn($rq) => $rq->where('name', 'trainer'));
    //         });
    //     }

    //     // 3. For gym owners, get their specific tenant context
    //     $tenantId = $user->getTenantId();

    //     return $query->whereHas('roles', function ($q) use ($tenantId) {
    //         $q->where('tenant_id', $tenantId)
    //             ->whereHas('role', function ($rq) {
    //                 $rq->where('name', 'trainer');
    //             });
    //     });
    // }
    // public static function getEloquentQuery(): Builder
    // {
    //     $user = auth()->user();
    //     $tenantId = $user->getTenantId();

    //     // 1. You MUST add the 'return' keyword here
    //     return parent::getEloquentQuery()
    //         ->with(['trainerProfile', 'roles.branch', 'roles.role']) // Eager load for performance
    //         ->whereHas('roles', function ($q) use ($tenantId) {
    //             $q->where('tenant_id', $tenantId)
    //                 ->whereHas('role', fn($rq) => $rq->where('name', 'trainer'));
    //         });
    // }
    public static function getEloquentQuery(): Builder
    {
        // Try this to see if ANY users show up
        return parent::getEloquentQuery()
            ->with(['trainerProfile']);
    }
}
