<?php

namespace App\Filament\Resources\ExistenciaResource\Pages;

use App\Filament\Resources\ExistenciaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateExistencia extends CreateRecord
{
    protected static string $resource = ExistenciaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Redirige a la tabla
    }

    protected function handleRecordCreation(array $data): Model
    {
        // Separar los datos de cajas del resto
        $cajas = $data['cajas'] ?? [];
        unset($data['cajas']);

        // Crear la existencia primero
        $existencia = static::getModel()::create($data);

        // Luego sincronizar la relación many-to-many con la tabla pivot
        if (!empty($cajas)) {
            $syncData = [];
            foreach ($cajas as $caja) {
                $syncData[$caja['caja_id']] = [
                    'precio_venta' => $caja['precio_venta'],
                ];
            }
            $existencia->cajas()->sync($syncData);
        }

        return $existencia;
    }
}
