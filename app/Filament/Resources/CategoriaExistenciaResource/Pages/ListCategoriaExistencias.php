<?php

namespace App\Filament\Resources\CategoriaExistenciaResource\Pages;

use App\Filament\Resources\CategoriaExistenciaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategoriaExistencias extends ListRecords
{
    protected static string $resource = CategoriaExistenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
