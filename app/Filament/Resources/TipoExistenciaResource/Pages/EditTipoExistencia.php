<?php

namespace App\Filament\Resources\TipoExistenciaResource\Pages;

use App\Filament\Resources\TipoExistenciaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTipoExistencia extends EditRecord
{
    protected static string $resource = TipoExistenciaResource::class;

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
