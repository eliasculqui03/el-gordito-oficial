<?php

namespace App\Filament\Resources\SesionCajaResource\Pages;

use App\Filament\Resources\SesionCajaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSesionCaja extends EditRecord
{
    protected static string $resource = SesionCajaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
