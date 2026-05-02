<?php

namespace App\Filament\Platform\Resources\Countries\Pages;

use App\Filament\Platform\Resources\Countries\CountryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCountry extends CreateRecord
{
    protected static string $resource = CountryResource::class;
}
