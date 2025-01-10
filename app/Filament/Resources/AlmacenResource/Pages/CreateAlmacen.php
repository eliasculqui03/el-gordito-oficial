<?php

namespace App\Filament\Resources\AlmacenResource\Pages;

use App\Filament\Resources\AlmacenResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAlmacen extends CreateRecord
{
    protected static string $resource = AlmacenResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Redirige a la tabla
    }
}
