<?php

namespace App\Filament\Resources\AreaExistenciaResource\Pages;

use App\Filament\Resources\AreaExistenciaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAreaExistencias extends ManageRecords
{
    protected static string $resource = AreaExistenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
