<?php

namespace App\Filament\Resources\InventarioResource\Widgets;

use App\Models\Inventario;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalExistencias extends BaseWidget
{
    protected function getStats(): array
    {
        $total = Inventario::count();
        $lowStock = Inventario::where('stock', '<=', 10)->count();
        $outOfStock = Inventario::where('stock', '=', 0)->count();
        return [
            Stat::make('Total de Existencias', $total)
                ->description('Total de existencias en inventario')
                ->icon('heroicon-o-cube')
                ->color('info'),

            Stat::make('Stock Bajo', $lowStock)
                ->description('Existencias con menos de 10 en stock')
                ->icon('heroicon-o-exclamation-triangle')
                ->color('warning'),

            Stat::make('Sin Stock', $outOfStock)
                ->description('Existencias agotadas')
                ->icon('heroicon-o-x-circle')
                ->color('danger'),
        ];
    }
}
