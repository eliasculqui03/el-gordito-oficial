<?php

namespace App\Filament\Resources\UnidadMedidaResource\Pages;

use App\Filament\Resources\UnidadMedidaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageUnidadMedidas extends ManageRecords
{
    protected static string $resource = UnidadMedidaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
