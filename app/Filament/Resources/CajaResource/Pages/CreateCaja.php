<?php

namespace App\Filament\Resources\CajaResource\Pages;

use App\Filament\Resources\CajaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCaja extends CreateRecord
{
    protected static string $resource = CajaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Redirige a la tabla
    }
}
