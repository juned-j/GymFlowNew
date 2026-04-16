<?php

namespace App\Filament\Resources\Members\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;

class MemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // 🔒 Tenant auto attach
                Hidden::make('tenant_id')
                    ->default(fn() => auth()->user()->roles()->first()?->tenant_id)
                    ->dehydrated(true),
                Grid::make(2)->components([
                    TextInput::make('user_name')
                        ->label('Name')
                        ->required(),
                    TextInput::make('user_email')
                        ->label('Email')
                        ->email()
                        ->required(),
                ]),

                Grid::make(2)->components([
                    TextInput::make('user_password')
                        ->label('Password')
                        ->password()
                        ->required(fn($context) => $context === 'create'),
                    Select::make('branch_id')
                        ->label('Branch')
                        ->required()
                        ->options(function () {
                            $user = auth()->user();
                            $tenantId = $user->getTenantId(); // 👈 BEST SOURCE
                            return \App\Models\Branch::where('tenant_id', $tenantId)
                                ->pluck('name', 'id');
                        }),
                ]),
                Grid::make(2)->components([
                    TextInput::make('height')
                        ->numeric()
                        ->suffix('cm'),

                    TextInput::make('weight')
                        ->numeric()
                        ->suffix('kg'),
                ]),
                Grid::make(2)->components([
                    Select::make('gender')
                        ->options([
                            'male' => 'Male',
                            'female' => 'Female',
                            'other' => 'Other',
                        ]),

                    DatePicker::make('dob')
                        ->label('Date of Birth'),
                ]),
                Grid::make(2)->components([
                    TextInput::make('goal')
                        ->placeholder('Weight loss, Muscle gain...'),
                    Select::make('status')
                        ->options([
                            'active' => 'Active',
                            'inactive' => 'Inactive',
                        ])
                        ->default('active')
                        ->required(),
                ])
                    ->columnSpanFull(),
            ]);
    }
}
