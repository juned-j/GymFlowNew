<?php

namespace App\Filament\Resources\MembershipPlans\Pages;

use App\Filament\Resources\MembershipPlans\MembershipPlanResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\Action;

class EditMembershipPlan extends EditRecord
{
    protected static string $resource = MembershipPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->submit('save'),
        ];
    }

    public function submit(): void
    {
        $this->save();
    }
}
