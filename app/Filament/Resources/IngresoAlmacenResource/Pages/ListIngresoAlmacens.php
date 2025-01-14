<?php

namespace App\Filament\Resources\IngresoAlmacenResource\Pages;

use App\Filament\Resources\IngresoAlmacenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIngresoAlmacens extends ListRecords
{
    protected static string $resource = IngresoAlmacenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
