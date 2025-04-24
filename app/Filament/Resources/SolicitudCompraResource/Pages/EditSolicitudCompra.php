<?php

namespace App\Filament\Resources\SolicitudCompraResource\Pages;

use App\Filament\Resources\SolicitudCompraResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSolicitudCompra extends EditRecord
{
    protected static string $resource = SolicitudCompraResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Redirige a la tabla
    }
}
