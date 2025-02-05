<?php

namespace App\Filament\Resources\InventarioResource\Pages;

use App\Filament\Resources\InventarioResource;
use App\Filament\Resources\InventarioResource\Widgets\GraficoStock;
use App\Filament\Resources\InventarioResource\Widgets\TotalExistencias;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageInventarios extends ManageRecords
{
    protected static string $resource = InventarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TotalExistencias::class,
            //GraficoStock::class,
        ];
    }
}
