<?php

namespace App\Filament\Resources\TipoExistenciaResource\Pages;

use App\Filament\Resources\TipoExistenciaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTipoExistencias extends ManageRecords
{
    protected static string $resource = TipoExistenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
