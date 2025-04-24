<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MesaSeeder extends Seeder
{
    public function run()
    {
        // Verificar si la tabla zonas tiene registros
        if (DB::table('zonas')->count() < 8) {
            $this->command->error('Primero debe crear al menos 8 zonas!');
            return;
        }

        $mesas = [];

        // Generar mesas para zonas 1-2 (15 c/u)
        foreach (range(1, 2) as $zonaId) {
            foreach (range(1, 15) as $i) {
                $mesas[] = $this->crearMesa($zonaId, count($mesas) + 1);
            }
        }

        // Generar mesas para zonas 3-8 (10 c/u)
        foreach (range(3, 8) as $zonaId) {
            foreach (range(1, 10) as $i) {
                $mesas[] = $this->crearMesa($zonaId, count($mesas) + 1);
            }
        }

        // Insertar en lotes de 50
        foreach (array_chunk($mesas, 50) as $chunk) {
            DB::table('mesas')->insert($chunk);
        }

        $this->command->info('Mesas creadas exitosamente!');
    }

    protected function crearMesa($zonaId, $numero)
    {
        return [
            'numero' => $numero,
            'zona_id' => $zonaId,
            'estado' => 'Libre',
            'capacidad' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
