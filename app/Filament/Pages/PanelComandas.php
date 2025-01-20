<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class PanelComandas extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Panel de Comandas';

    protected static ?string $title = 'Panel de Comandas';

    protected static string $view = 'filament.pages.panel-comandas';
}
