<?php

namespace App\Filament\Resources\UnidadMedidaResource\Pages;

use App\Filament\Resources\UnidadMedidaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUnidadMedida extends CreateRecord
{
    protected static string $resource = UnidadMedidaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Redirige a la tabla
    }
}
