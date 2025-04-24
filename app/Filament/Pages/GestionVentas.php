<?php

namespace App\Filament\Pages;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;


class GestionVentas extends Page
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static bool $shouldMaximizeContent = true;

    protected static string $view = 'filament.pages.gestion-ventas';


    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('fullscreen')
                ->label('Pantalla completa')
                ->icon('heroicon-o-arrows-pointing-out')
                ->action('toggleFullScreen')
                ->color('info'),
        ];
    }

    public function toggleFullScreen(): void
    {
        $this->dispatch('toggle-fullscreen');
    }
}
