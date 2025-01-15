<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class PanelMesas extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Panel de Mesas';

    protected static ?string $title = 'Panel de Mesas';

    protected static string $view = 'filament.pages.panel-mesas';
}
