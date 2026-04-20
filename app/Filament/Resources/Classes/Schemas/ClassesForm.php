<?php

namespace App\Filament\Resources\Classes\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Components\Section;
use App\Models\Branch;
use App\Models\User;

class ClassesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('tenant_id')
                    ->default(fn() => auth()->user()->getTenantId()),

                Section::make('Class Essentials')
                    ->description('Basic information about the session.')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Morning Yoga Flow'),

                        Select::make('branch_id')
                            ->label('Branch')
                            ->required()
                            ->options(fn() => Branch::where('tenant_id', auth()->user()->getTenantId())->pluck('name', 'id'))
                            ->searchable(),

                        Select::make('trainer_id')
                            ->label('Trainer')
                            ->required()
                            ->options(function () {
                                $tenantId = auth()->user()->getTenantId();

                                return User::whereHas('roles', function ($query) use ($tenantId) {
                                    $query->where('tenant_id', $tenantId) // Column is here, not in users table
                                        ->whereIn('name', ['trainer', 'admin']);
                                })->pluck('name', 'id');
                            })
                            ->searchable(),

                        TextInput::make('capacity')
                            ->numeric()
                            ->default(20)
                            ->minValue(1)
                            ->required(),
                    ])->columns(2),

                Section::make('Schedule & Location')
                    ->schema([
                        DateTimePicker::make('start_time')
                            ->required()
                            ->native(false)
                            ->after('now'),

                        DateTimePicker::make('end_time')
                            ->required()
                            ->native(false)
                            ->after('start_time'),

                        TextInput::make('location')
                            ->placeholder('e.g., Studio A or Online'),

                        Textarea::make('description')
                            ->columnSpanFull()
                            ->rows(3),
                    ])->columns(2),
            ]);
    }
}
