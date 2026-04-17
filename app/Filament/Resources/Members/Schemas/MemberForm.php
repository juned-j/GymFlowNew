<?php

namespace App\Filament\Resources\Members\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Schemas\Components\Grid;

class MemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Section 1: User Account Details (Linked via 'user' relationship)
                Section::make('Personal Information')
                    ->description('Primary account details for the member.')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('user.name')
                                    ->label('Full Name')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('user.email')
                                    ->label('Email Address')
                                    ->email()
                                    ->required()
                                    ->unique('users', 'email', ignoreRecord: true),

                                TextInput::make('user.phone')
                                    ->label('Phone Number')
                                    ->tel(),
                            ]),
                    ]),

                // Section 2: Gym Assignment (Linked via 'user.roles' relationship)
                Section::make('Gym Assignment')
                    ->description('Assign the member to a specific branch.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('user.roles.branch_id')
                                    ->label('Branch')
                                    ->options(\App\Models\Branch::where('tenant_id', auth()->user()->getTenantId())->pluck('name', 'id'))
                                    ->required()
                                    ->native(false),

                                Select::make('user.roles.role_id')
                                    ->label('System Role')
                                    ->options(\App\Models\Role::where('name', 'member')->pluck('name', 'id'))
                                    ->default(fn() => \App\Models\Role::where('name', 'member')->first()?->id)
                                    ->required()
                                    ->disabled()
                                    ->dehydrated(),
                            ]),
                    ]),

                // Section 3: Fitness Profile (Direct Member Model Fields)
                Section::make('Member Profile')
                    ->description('Physical attributes and fitness goals.')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('gender')
                                    ->options([
                                        'male' => 'Male',
                                        'female' => 'Female',
                                        'other' => 'Other',
                                    ])
                                    ->required(),

                                DatePicker::make('dob')
                                    ->label('Date of Birth')
                                    ->required()
                                    ->native(false)
                                    ->displayFormat('d/m/Y'),

                                TextInput::make('height')
                                    ->numeric()
                                    ->suffix('cm'),

                                TextInput::make('weight')
                                    ->numeric()
                                    ->suffix('kg'),

                                Select::make('goal')
                                    ->options([
                                        'weight_loss' => 'Weight Loss',
                                        'muscle_gain' => 'Muscle Gain',
                                        'maintenance' => 'Maintenance',
                                        'flexibility' => 'Flexibility',
                                    ])
                                    ->placeholder('Select a goal'),

                                Select::make('status')
                                    ->options([
                                        'active' => 'Active',
                                        'inactive' => 'Inactive',
                                        'pending' => 'Pending',
                                    ])
                                    ->default('active')
                                    ->required(),
                            ]),
                    ]),

                // Section 4: Medical & Activity Info
                Section::make('Medical & Activity Info')
                    ->collapsed()
                    ->schema([
                        TextInput::make('injuries')
                            ->placeholder('List any physical limitations...'),

                        Select::make('activity_level')
                            ->options([
                                'sedentary' => 'Sedentary',
                                'moderate' => 'Moderate',
                                'active' => 'Very Active',
                            ]),
                    ]),
            ]);
    }
}
