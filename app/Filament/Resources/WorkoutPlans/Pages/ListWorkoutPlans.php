<?php

namespace App\Filament\Resources\WorkoutPlans\Pages;

use App\Filament\Resources\WorkoutPlans\WorkoutPlanResource;
use App\Filament\Resources\Workouts\WorkoutResource;
use App\Filament\Resources\WorkoutExercises\WorkoutExerciseResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWorkoutPlans extends ListRecords
{
    protected static string $resource = WorkoutPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Button for Workouts
            Action::make('manageWorkouts')
                ->label('All Workouts')
                ->color('gray')
                ->icon('heroicon-o-calendar')
                ->url(fn(): string => WorkoutResource::getUrl('index')),

            // Button for Workout Exercises
            Action::make('manageExercises')
                ->label('All Prescribed Exercises')
                ->color('gray')
                ->icon('heroicon-o-beaker')
                ->url(fn(): string => WorkoutExerciseResource::getUrl('index')),

            // The default "New Workout Plan" button
            CreateAction::make()
                ->label('New Workout Plan'),
        ];
    }
}
