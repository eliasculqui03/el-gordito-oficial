<?php

namespace App\Filament\Resources\MovimientoCajaResource\Pages;

use App\Filament\Resources\MovimientoCajaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMovimientoCaja extends EditRecord
{
    protected static string $resource = MovimientoCajaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
