<?php

namespace App\Filament\Resources\Classes\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Components\Section;
use App\Models\Branch;
use App\Models\User;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;

class ClassesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Hidden::make('tenant_id')
                    ->default(fn() => auth()->user()->getTenantId()),

                /*
                |--------------------------------------------------------------------------
                | CLASS ESSENTIALS
                |--------------------------------------------------------------------------
                */
                Section::make('Class Essentials')
                    ->description('Basic information about the class.')
                    ->schema([

                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. Weight Loss Bootcamp'),

                        Select::make('branch_id')
                            ->label('Branch')
                            ->required()
                            ->searchable()
                            ->options(
                                fn() =>
                                Branch::where(
                                    'tenant_id',
                                    auth()->user()->getTenantId()
                                )->pluck('name', 'id')
                            ),

                        Select::make('trainer_id')
                            ->label('Trainer')
                            ->required()
                            ->searchable()
                            ->options(function () {

                                $tenantId = auth()->user()->getTenantId();

                                return User::whereHas('roles', function ($query) use ($tenantId) {

                                    $query->where('tenant_id', $tenantId)
                                        ->whereHas('role', function ($q) {
                                            $q->whereIn('name', ['trainer', 'admin']);
                                        });
                                })
                                    ->pluck('name', 'id');
                            }),

                        TextInput::make('capacity')
                            ->numeric()
                            ->required()
                            ->default(20)
                            ->minValue(1)
                            ->suffix('members'),

                    ])
                    ->columns(2),

                /*
                |--------------------------------------------------------------------------
                | CLASS SCHEDULE
                |--------------------------------------------------------------------------
                */
                Section::make('Class Schedule')
                    ->description('Recurring schedule configuration.')
                    ->schema([

                        Select::make('duration_type')
                            ->label('Program Duration Type')
                            ->required()
                            ->options([
                                'weeks' => 'Weeks',
                                'months' => 'Months',
                                'years' => 'Years',
                            ]),

                        TextInput::make('duration_value')
                            ->label('Duration Value')
                            ->numeric()
                            ->required()
                            ->default(1)
                            ->minValue(1)
                            ->placeholder('e.g. 3'),

                        Select::make('repeat_type')
                            ->label('Repeat Type')
                            ->required()
                            ->options([
                                'daily' => 'Daily',
                                'weekly' => 'Weekly',
                                'monthly' => 'Monthly',
                            ]),

                        CheckboxList::make('days_of_week')
                            ->label('Class Days')
                            ->columns(4)
                            ->options([
                                'mon' => 'Mon',
                                'tue' => 'Tue',
                                'wed' => 'Wed',
                                'thu' => 'Thu',
                                'fri' => 'Fri',
                                'sat' => 'Sat',
                                'sun' => 'Sun',
                            ])
                            ->visible(
                                fn($get) =>
                                $get('repeat_type') === 'weekly'
                            ),

                        DatePicker::make('start_date')
                            ->label('Program Start Date')
                            ->required()
                            ->native(false)
                            ->minDate(today()),

                        DatePicker::make('end_date')
                            ->label('Program End Date')
                            ->required()
                            ->native(false)
                            ->minDate(today()),

                        TimePicker::make('start_time')
                            ->label('Start Time')
                            ->required()
                            ->seconds(false)
                            ->native(false)
                            ->minutesStep(5)
                            ->displayFormat('h:i A'),

                        TimePicker::make('end_time')
                            ->label('End Time')
                            ->required()
                            ->seconds(false)
                            ->native(false)
                            ->minutesStep(5)
                            ->displayFormat('h:i A'),

                    ])
                    ->columns(2),

                /*
                |--------------------------------------------------------------------------
                | DESCRIPTION & LOCATION
                |--------------------------------------------------------------------------
                */
                Section::make('Additional Details')
                    ->schema([

                        TextInput::make('location')
                            ->placeholder('e.g. Studio A'),

                        Textarea::make('description')
                            ->rows(4)
                            ->columnSpanFull(),

                    ])
                    ->columns(1),

            ]);
    }
}
