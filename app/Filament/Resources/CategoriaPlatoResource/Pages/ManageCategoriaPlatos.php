<?php

namespace App\Filament\Resources\CategoriaPlatoResource\Pages;

use App\Filament\Resources\CategoriaPlatoResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCategoriaPlatos extends ManageRecords
{
    protected static string $resource = CategoriaPlatoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
