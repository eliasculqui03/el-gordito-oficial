<?php

namespace App\Filament\Resources\PlatoResource\Pages;

use App\Filament\Resources\PlatoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlato extends EditRecord
{
    protected static string $resource = PlatoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Redirige a la tabla
    }
}
