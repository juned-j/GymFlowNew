<?php

namespace App\Filament\Resources\Trainers\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;

class TrainerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // USER DATA
            TextInput::make('name')->required(),
            TextInput::make('email')->email()->required(),
            Select::make('branch_id')
                ->label('Branch')
                ->required()
                ->options(function () {

                    $user = auth()->user();

                    $tenantId = $user->getTenantId(); // 👈 BEST SOURCE

                    return \App\Models\Branch::where('tenant_id', $tenantId)
                        ->pluck('name', 'id');
                }),

            // TRAINER PROFILE
            TextInput::make('trainerProfile.specialization')
                ->label('Specialization'),

            TextInput::make('trainerProfile.status')
                ->label('Status'),

            Textarea::make('trainerProfile.bio')
                ->label('Bio'),
        ]);
    }
}
