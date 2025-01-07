<?php

namespace App\Filament\Resources\UnidadMedidaResource\Pages;

use App\Filament\Resources\UnidadMedidaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUnidadMedida extends EditRecord
{
    protected static string $resource = UnidadMedidaResource::class;

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
