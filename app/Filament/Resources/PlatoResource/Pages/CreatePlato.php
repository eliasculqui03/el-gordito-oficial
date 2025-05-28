<?php

namespace App\Filament\Resources\PlatoResource\Pages;

use App\Filament\Resources\PlatoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePlato extends CreateRecord
{
    protected static string $resource = PlatoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Redirige a la tabla
    }

    protected function handleRecordCreation(array $data): Model
    {
        // Separar los datos de precios_cajas del resto
        $preciosCajas = $data['precios_cajas'] ?? [];
        unset($data['precios_cajas']);

        // Crear el plato primero
        $plato = static::getModel()::create($data);

        // Luego sincronizar la relación many-to-many con la tabla pivot
        if (!empty($preciosCajas)) {
            $syncData = [];
            foreach ($preciosCajas as $precio) {
                $syncData[$precio['caja_id']] = [
                    'precio' => $precio['precio'],
                    'precio_llevar' => $precio['precio_llevar'],
                ];
            }
            $plato->cajas()->sync($syncData);
        }

        return $plato;
    }
}
