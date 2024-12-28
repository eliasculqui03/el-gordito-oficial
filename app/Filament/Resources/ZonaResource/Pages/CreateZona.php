<?php

namespace App\Filament\Resources\ZonaResource\Pages;

use App\Filament\Resources\ZonaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateZona extends CreateRecord
{
    protected static string $resource = ZonaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Redirige a la tabla
    }
}
