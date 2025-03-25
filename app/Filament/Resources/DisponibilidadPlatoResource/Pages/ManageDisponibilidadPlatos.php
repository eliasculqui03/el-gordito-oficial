<?php

namespace App\Filament\Resources\DisponibilidadPlatoResource\Pages;

use App\Filament\Resources\DisponibilidadPlatoResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageDisponibilidadPlatos extends ManageRecords
{
    protected static string $resource = DisponibilidadPlatoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
