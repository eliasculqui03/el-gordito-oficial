<?php

namespace App\Filament\Resources\TipoExistenciaResource\Pages;

use App\Filament\Resources\TipoExistenciaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTipoExistencia extends CreateRecord
{
    protected static string $resource = TipoExistenciaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Redirige a la tabla
    }
}
