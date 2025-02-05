<?php

namespace App\Filament\Widgets;

use App\Models\Inventario;
use Filament\Widgets\ChartWidget;

class GraficoStock extends ChartWidget
{
    protected static ?string $heading = 'Gráfico de barras ';

    protected function getData(): array
    {
        $productos = Inventario::with('existencia')  // Cargamos la relación
            ->join('existencias', 'existencias.id', '=', 'inventarios.existencia_id')
            ->select(['existencias.nombre', 'inventarios.stock'])
            ->orderBy('inventarios.stock', 'desc')
            ->limit(10)
            ->get();
        return [
            'datasets' => [
                [
                    'label' => 'Stock Disponible',
                    'data' => $productos->pluck('stock')->toArray(),
                    'backgroundColor' => [
                        '#36A2EB',
                        '#FF6384',
                        '#4BC0C0',
                        '#FF9F40',
                        '#9966FF',
                        '#FFCD56',
                    ],
                ],
            ],
            'labels' => $productos->pluck('nombre')->toArray(),  // Accedemos al nombre a través de la relación
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Cantidad en Stock'
                    ],
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Productos'
                    ],
                ],
            ],
        ];
    }
}
