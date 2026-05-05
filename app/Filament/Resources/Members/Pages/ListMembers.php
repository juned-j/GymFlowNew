<?php

namespace App\Filament\Resources\Members\Pages;

use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\Members\MemberResource;
use Illuminate\Support\Facades\Log;
use App\Models\Member;

class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;
protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
    
}