<?php

namespace App\Filament\Resources\SesionCajaResource\Pages;

use App\Filament\Resources\SesionCajaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSesionCajas extends ListRecords
{
    protected static string $resource = SesionCajaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
