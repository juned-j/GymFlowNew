<?php

namespace App\Filament\Resources\Members\Schemas;

use App\Models\Branch;
use App\Models\Role;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Schema;

class MemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Wizard::make([

                Step::make('Account Details')
                    ->description('Create or link the user account')
                    ->schema([
                        TextInput::make('user.name')
                            ->label('Full Name')
                            ->maxLength(50)
                            ->required(),

                 TextInput::make('user.email')
    ->label('Email Address')
    ->email()
    ->required()
    ->unique(
        table: 'users',
        column: 'email',
        ignorable: fn ($record) => $record?->user 
    ),

                        TextInput::make('user.phone')
                            ->tel(),
                    ]),

                Step::make('Gym & Role')
                    ->description('Assign to a branch')
                    ->schema([
                 Select::make('branch_id') 
    ->label('Branch')
    ->options(fn () =>
        Branch::where('tenant_id', auth()->user()->getTenantId())
            ->pluck('name', 'id')
            ->toArray()
    )
    ->required()
    ->searchable()
    ->preload()
    ->native(false),

                      Select::make('user.roles.role_id')
    ->label('Assigned Role')
    ->options(fn () => 
        \App\Models\Role::where('name', 'member')->pluck('name', 'id')->toArray()
    )
    ->default(fn () => 
        \App\Models\Role::where('name', 'member')->value('id')
    )
   
    ->dehydrated(true) 
    ->required(false), 
                    ]),

                Step::make('Physical Profile')
                    ->description('Metrics and Goals')
                    ->columns(2)
                    ->schema([
                        Select::make('gender')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                                'other' => 'Other'
                            ])
                            ->required(),

                        DatePicker::make('dob')
                            ->label('Date of Birth')
                            ->required()
                            ->native(false),

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
                            ]),
                    ]),

                Step::make('Fitness & Status')
                    ->description('Final details')
                    ->schema([
                        Select::make('activity_level')
                            ->options([
                                'sedentary' => 'Sedentary',
                                'moderate' => 'Moderate',
                                'active' => 'Active'
                            ]),

                        TextInput::make('injuries')
                            ->placeholder('None'),

                        Select::make('status')
                            ->options([
                                'active' => 'Active',
                                'inactive' => 'Inactive'
                            ])
                            ->default('active')
                            ->required(),
                    ]),

            ])

            ->afterStateHydrated(function ($state, $record, $set) {
                if (!$record || !$record->user) {
                    return;
                }

                $set('user.name', $record->user->name);
                $set('user.email', $record->user->email);
                $set('user.phone', $record->user->phone);

                $role = $record->user->roles()->first();

                if ($role) {
                    $set('user.roles.branch_id', $role->pivot->branch_id ?? null);
                    $set('user.roles.role_id', $role->id);
                }
            })

            // ✅ SUBMIT BUTTON
            ->submitAction(
                \Filament\Actions\Action::make('submit')
                    ->label(fn () => request()->routeIs('*edit*')
                        ? 'Save Changes'
                        : 'Create Member'
                    )
                    ->submit('create')
                    ->color('primary')
            )

            ->skippable(str(request()->route()->getName())->endsWith('.edit'))
            ->columnSpanFull()
        ]);
    }
}