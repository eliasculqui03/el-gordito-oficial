<?php

namespace App\Filament\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;

class MozosPlatos extends Page
{
    use HasPageShield;

    protected static ?string $navigationGroup = 'Panel de mozos';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Ped. Platos';
    protected static ?string $title = 'Pedidos Platos';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.mozos-platos';

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }
}
