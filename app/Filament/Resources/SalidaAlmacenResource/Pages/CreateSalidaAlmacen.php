<?php

namespace App\Filament\Resources\SalidaAlmacenResource\Pages;

use App\Filament\Resources\SalidaAlmacenResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSalidaAlmacen extends CreateRecord
{
    protected static string $resource = SalidaAlmacenResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Redirige a la tabla
    }
}
