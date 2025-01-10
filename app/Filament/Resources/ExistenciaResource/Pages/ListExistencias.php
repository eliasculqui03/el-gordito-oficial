<?php

namespace App\Filament\Resources\ExistenciaResource\Pages;

use App\Filament\Resources\ExistenciaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExistencias extends ListRecords
{
    protected static string $resource = ExistenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
