<?php

namespace App\Filament\Resources\TipoExistenciaResource\Pages;

use App\Filament\Resources\TipoExistenciaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTipoExistencias extends ListRecords
{
    protected static string $resource = TipoExistenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
