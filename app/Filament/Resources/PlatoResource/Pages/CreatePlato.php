<?php

namespace App\Filament\Resources\PlatoResource\Pages;

use App\Filament\Resources\PlatoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePlato extends CreateRecord
{
    protected static string $resource = PlatoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Redirige a la tabla
    }
}
