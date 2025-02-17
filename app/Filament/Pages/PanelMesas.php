<?php

namespace App\Filament\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;

class PanelMesas extends Page
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationLabel = 'Panel de Mesas';

    protected static ?string $title = 'Panel de Mesas';

    protected static string $view = 'filament.pages.panel-mesas';
}
