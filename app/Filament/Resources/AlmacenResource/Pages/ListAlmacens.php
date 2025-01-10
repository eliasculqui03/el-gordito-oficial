<?php

namespace App\Filament\Resources\AlmacenResource\Pages;

use App\Filament\Resources\AlmacenResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAlmacens extends ListRecords
{
    protected static string $resource = AlmacenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
