<?php

namespace App\Filament\Resources\CajaResource\Pages;

use App\Filament\Resources\CajaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCajas extends ManageRecords
{
    protected static string $resource = CajaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
