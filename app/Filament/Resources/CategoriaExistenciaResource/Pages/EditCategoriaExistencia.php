<?php

namespace App\Filament\Resources\CategoriaExistenciaResource\Pages;

use App\Filament\Resources\CategoriaExistenciaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategoriaExistencia extends EditRecord
{
    protected static string $resource = CategoriaExistenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Redirige a la tabla
    }
}
