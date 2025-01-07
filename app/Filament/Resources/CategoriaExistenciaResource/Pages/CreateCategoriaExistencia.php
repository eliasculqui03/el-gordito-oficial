<?php

namespace App\Filament\Resources\CategoriaExistenciaResource\Pages;

use App\Filament\Resources\CategoriaExistenciaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCategoriaExistencia extends CreateRecord
{
    protected static string $resource = CategoriaExistenciaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Redirige a la tabla
    }
}
