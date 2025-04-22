<?php

namespace App\Filament\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;

class ComandasExistencias extends Page
{
    use HasPageShield;

    protected static ?string $navigationGroup = 'C. Existencias';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static string $view = 'filament.pages.comandas-existencias';

    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::MaxContent;
    }
}
