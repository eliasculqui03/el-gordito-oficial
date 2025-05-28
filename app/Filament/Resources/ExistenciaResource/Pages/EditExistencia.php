<?php

namespace App\Filament\Resources\ExistenciaResource\Pages;

use App\Filament\Resources\ExistenciaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditExistencia extends EditRecord
{
    protected static string $resource = ExistenciaResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Redirige a la tabla
    }



    // Cargar los datos existentes de la tabla pivot
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $existencia = $this->record->load('cajas');

        if ($existencia->cajas->isNotEmpty()) {
            $data['cajas'] = $existencia->cajas->map(function ($caja) {
                return [
                    'caja_id' => $caja->id,
                    'precio_venta' => $caja->pivot->precio_venta,
                ];
            })->toArray();
        } else {
            $data['cajas'] = [];
        }

        return $data;
    }

    // Manejar la actualización
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Separar los datos de cajas del resto
        $cajas = $data['cajas'] ?? [];
        unset($data['cajas']);

        // Actualizar la existencia
        $record->update($data);

        // Sincronizar la relación many-to-many
        if (!empty($cajas)) {
            $syncData = [];
            foreach ($cajas as $caja) {
                $syncData[$caja['caja_id']] = [
                    'precio_venta' => $caja['precio_venta'],
                ];
            }
            $record->cajas()->sync($syncData);
        } else {
            // Si no hay datos, desconectar todas las relaciones
            $record->cajas()->detach();
        }

        return $record;
    }
}
