<?php

namespace App\Filament\Resources\IngresoAlmacenResource\Pages;

use App\Filament\Resources\IngresoAlmacenResource;
use App\Models\DetalleOrdenCompra;
use App\Models\Inventario;
use Doctrine\DBAL\Query\QueryException;
use Filament\Actions;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateIngresoAlmacen extends CreateRecord
{
    protected static string $resource = IngresoAlmacenResource::class;

    protected function afterCreate(): void
    {
        // Actualizar estado del detalle de orden de compra
        if ($this->record->detalle_orden_compra_id) {
            DetalleOrdenCompra::where('id', $this->record->detalle_orden_compra_id)
                ->update(['estado' => 'Recibida']);
        }

        // Crear o actualizar el inventario
        try {
            Inventario::updateOrCreate(
                [
                    'existencia_id' => $this->record->existencia_id,
                    'almacen_id' => $this->record->almacen_id,
                ],
                [
                    'stock' => DB::raw('stock + ' . $this->record->cantidad),
                ]
            );

            Notification::make()
                ->title('Ingreso creado exitosamente')
                ->body('Se ha actualizado el inventario y el estado del detalle de orden de compra.')
                ->success()
                ->send();
        } catch (QueryException $e) {
            Notification::make()
                ->title('Error al actualizar inventario')
                ->body('Hubo un problema al actualizar el inventario.')
                ->danger()
                ->send();
        }
    }
}
