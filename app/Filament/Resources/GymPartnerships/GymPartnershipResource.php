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
use Illuminate\Support\Facades\Log;

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
        $tenantId = auth()->user()?->getTenantId();

        // 👇 ADD IT HERE
        Log::info('Tenant Filter', [
            'tenant_id' => $tenantId,
            'user_id' => auth()->id(),
        ]);

        return GymPartnership::query()
            ->where(function ($query) use ($tenantId) {
                $query->where('tenant_id', $tenantId)
                    ->orWhere('partner_tenant_id', $tenantId);
            });
    }
    // public static function getEloquentQuery(): Builder
    // {
    //     dd([
    //         'user' => auth()->id(),
    //         'tenant' => auth()->user()?->getTenantId(),
    //         'roles' => auth()->user()?->roles?->pluck('tenant_id'),
    //     ]);
    // }
}
