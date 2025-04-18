<?php

namespace App\Filament\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;

class ComandasPlatos extends Page
{
    use HasPageShield;

    protected static ?string $navigationGroup = 'C. Platos y Bebidas';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static string $view = 'filament.pages.comandas-platos';
}
