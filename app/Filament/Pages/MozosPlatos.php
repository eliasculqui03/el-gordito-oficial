<?php

namespace App\Filament\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;

class MozosPlatos extends Page
{
    use HasPageShield;

    protected static ?string $navigationGroup = 'C. Platos y Bebidas';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.mozos-platos';

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }
}
