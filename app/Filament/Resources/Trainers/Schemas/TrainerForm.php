<?php

namespace App\Filament\Resources\Trainers\Schemas;

use App\Models\Branch;
use App\Models\Role;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;

class TrainerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Wizard::make([
                Step::make('Trainer Account')
                    ->schema([
                        TextInput::make('user.name')->required(),
                        TextInput::make('user.email')->email()->required(),
                        TextInput::make('user.phone')->tel(),
                    ]),

                Step::make('Professional Profile')
                    ->schema([
                        TextInput::make('specialization')
                            ->required()
                            ->placeholder('e.g. Strength Training, Yoga'),
                        Textarea::make('bio')
                            ->rows(3),
                        Select::make('status')
                            ->options(['active' => 'Active', 'inactive' => 'Inactive'])
                            ->default('active'),
                    ]),

                Step::make('Assignment')
                    ->schema([
                        Select::make('user.roles.branch_id')
                            ->label('Primary Branch')
                            ->options(Branch::where('tenant_id', auth()->user()->getTenantId())->pluck('name', 'id'))
                            ->required(),
                        Select::make('user.roles.role_id')
                            ->label('Role')
                            ->options(Role::where('name', 'trainer')->pluck('name', 'id'))
                            ->default(fn() => Role::where('name', 'trainer')->first()?->id)
                            ->disabled()
                            ->dehydrated(),
                    ]),
            ])->columnSpanFull()
        ]);
    }
}
