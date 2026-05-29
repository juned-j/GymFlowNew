<?php

namespace App\Filament\Resources\GymPartnerships;

use App\Filament\Resources\GymPartnerships\Pages\CreateGymPartnership;
use App\Filament\Resources\GymPartnerships\Pages\EditGymPartnership;
use App\Filament\Resources\GymPartnerships\Pages\ListGymPartnerships;
use App\Filament\Resources\GymPartnerships\Schemas\GymPartnershipForm;
use App\Filament\Resources\GymPartnerships\Tables\GymPartnershipsTable;
use App\Models\GymPartnership;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class GymPartnershipResource extends Resource
{
    protected static ?string $model = GymPartnership::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return GymPartnershipForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GymPartnershipsTable::configure($table);
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
            'index' => ListGymPartnerships::route('/'),
            'create' => CreateGymPartnership::route('/create'),
            'edit' => EditGymPartnership::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where(function ($query) {

                $user = Auth::user();

                if (! $user) {
                    $query->whereRaw('1 = 0');
                    return;
                }

                $tenantId = $user->getTenantId();

                $query->where('tenant_id', $tenantId)
                    ->orWhere('partner_tenant_id', $tenantId);
            });
    }
}
