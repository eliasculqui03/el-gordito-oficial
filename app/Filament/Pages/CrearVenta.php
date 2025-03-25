<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class CrearVenta extends Page
{
    protected static ?string $navigationGroup = 'Ventas';
    //protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.crear-venta';
}
