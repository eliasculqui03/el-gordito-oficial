<?php

namespace App\Filament\Resources\MovimientoCajaResource\Pages;

use App\Filament\Resources\MovimientoCajaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMovimientoCajas extends ListRecords
{
    protected static string $resource = MovimientoCajaResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
