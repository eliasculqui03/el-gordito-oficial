<?php

namespace App\Filament\Resources\PlatoResource\Pages;

use App\Filament\Resources\PlatoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPlato extends EditRecord
{
    protected static string $resource = PlatoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index'); // Redirige a la tabla
    }

    // Cargar los datos existentes de la tabla pivot
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $plato = $this->record->load('cajas');

        if ($plato->cajas->isNotEmpty()) {
            $data['precios_cajas'] = $plato->cajas->map(function ($caja) {
                return [
                    'caja_id' => $caja->id,
                    'precio' => $caja->pivot->precio,
                    'precio_llevar' => $caja->pivot->precio_llevar,
                ];
            })->toArray();
        } else {
            $data['precios_cajas'] = [];
        }

        return $data;
    }

    // Manejar la actualización
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Separar los datos de precios_cajas del resto
        $preciosCajas = $data['precios_cajas'] ?? [];
        unset($data['precios_cajas']);

        // Actualizar el plato
        $record->update($data);

        // Sincronizar la relación many-to-many
        if (!empty($preciosCajas)) {
            $syncData = [];
            foreach ($preciosCajas as $precio) {
                $syncData[$precio['caja_id']] = [
                    'precio' => $precio['precio'],
                    'precio_llevar' => $precio['precio_llevar'],
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
