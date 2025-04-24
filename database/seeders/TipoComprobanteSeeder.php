<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoComprobanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipoComprobantes = [
            [
                'codigo' => '01',
                'descripcion' => 'Factura',
                'estado' => true
            ],
            [
                'codigo' => '03',
                'descripcion' => 'Boleta de venta',
                'estado' => true
            ],
            [
                'codigo' => '07',
                'descripcion' => 'Nota de Crédito',
                'estado' => false
            ],
            [
                'codigo' => '08',
                'descripcion' => 'Nota de Débito',
                'estado' => false
            ],

            [
                'codigo' => '00',
                'descripcion' => 'Otros',
                'estado' => false
            ]
        ];

        foreach ($tipoComprobantes as $tc) {
            DB::table('tipo_comprobantes')->insert([
                'codigo' => $tc['codigo'],
                'descripcion' => $tc['descripcion'],
                'estado' => $tc['estado'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
