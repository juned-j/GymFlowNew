<?php

namespace App\Filament\Resources\Exercises\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ExerciseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tenant_id')
                    ->required()
                    ->numeric(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('muscle_group')
                    ->required(),
                TextInput::make('equipment'),
                Textarea::make('video_url')
                    ->columnSpanFull(),
            ]);
    }
}
