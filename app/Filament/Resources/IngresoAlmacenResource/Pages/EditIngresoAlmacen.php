<?php

namespace App\Filament\Resources\IngresoAlmacenResource\Pages;

use App\Filament\Resources\IngresoAlmacenResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIngresoAlmacen extends EditRecord
{
    protected static string $resource = IngresoAlmacenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
