<?php

namespace App\Filament\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;

class MozosExistencias extends Page
{
    use HasPageShield;

    protected static ?string $navigationGroup = 'Panel de mozos';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Ped. Existencias';
    protected static ?string $title = 'Pedidos Existencias';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.mozos-existencias';

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }
}
