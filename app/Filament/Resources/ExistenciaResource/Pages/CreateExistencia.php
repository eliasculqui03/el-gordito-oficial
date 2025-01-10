<?php

namespace App\Filament\Resources\ExistenciaResource\Pages;

use App\Filament\Resources\ExistenciaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateExistencia extends CreateRecord
{
    protected static string $resource = ExistenciaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Redirige a la tabla
    }
}
