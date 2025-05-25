<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;

class EditarComanda extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.editar-comanda';

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }
}
