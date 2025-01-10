<?php

namespace App\Filament\Resources\SolicitudCompraResource\Pages;

use App\Filament\Resources\SolicitudCompraResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSolicitudCompra extends CreateRecord
{
    protected static string $resource = SolicitudCompraResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Redirige a la tabla
    }
}
