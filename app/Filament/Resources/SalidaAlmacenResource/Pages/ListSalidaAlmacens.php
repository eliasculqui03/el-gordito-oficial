<?php

namespace App\Filament\Resources\SalidaAlmacenResource\Pages;

use App\Filament\Resources\SalidaAlmacenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSalidaAlmacens extends ListRecords
{
    protected static string $resource = SalidaAlmacenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
