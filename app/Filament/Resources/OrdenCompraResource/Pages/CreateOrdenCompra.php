<?php

namespace App\Filament\Resources\OrdenCompraResource\Pages;

use App\Filament\Resources\OrdenCompraResource;
use App\Models\SolicitudCompra;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateOrdenCompra extends CreateRecord
{
    protected static string $resource = OrdenCompraResource::class;

    protected function afterCreate(): void
    {
        // Obtener todas las solicitudes de compra asociadas
        $solicitudesIds = $this->record->detalleOrdenCompra
            ->pluck('solicitud_compra_id')
            ->filter();

        if ($solicitudesIds->isNotEmpty()) {
            // Actualizar el estado de las solicitudes
            SolicitudCompra::whereIn('id', $solicitudesIds)
                ->update(['estado' => 'Pagada']);

            // Mostrar notificación
            Notification::make()
                ->title('Orden de compra creada')
                ->body('Las solicitudes de compra han sido actualizadas a estado pagado.')
                ->success()
                ->send();
        }
    }
}
