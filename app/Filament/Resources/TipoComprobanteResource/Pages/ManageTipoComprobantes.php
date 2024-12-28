<?php

namespace App\Filament\Resources\TipoComprobanteResource\Pages;

use App\Filament\Resources\TipoComprobanteResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTipoComprobantes extends ManageRecords
{
    protected static string $resource = TipoComprobanteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
