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
    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();

        $query = static::getModel()::query()
            ->whereHas('roles', function ($q) use ($user) {
                // Global filter for trainers
                $q->whereHas('role', fn($rq) => $rq->where('name', 'trainer'));

                // Tenant filter for non-super admins
                if (!$user->isSuperAdmin()) {
                    $q->where('tenant_id', $user->getTenantId());
                }
            });

        // Debug check as requested by the user
        dd([
            'tenant_id' => $user->getTenantId(),
            'branches' => \App\Models\Branch::where('tenant_id', $user->getTenantId())->get()
        ]);

        return $query;
    }
}
