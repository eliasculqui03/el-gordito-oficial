<?php

namespace App\Filament\Resources\ExistenciaResource\Pages;

use App\Filament\Resources\ExistenciaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExistencia extends EditRecord
{
    protected static string $resource = ExistenciaResource::class;

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
