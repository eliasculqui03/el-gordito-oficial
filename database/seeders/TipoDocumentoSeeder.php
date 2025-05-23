<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoDocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $tipoDocumentos = [
            [
                'tipo' => '1',
                'descripcion_larga' => 'LIBRETA ELECTORAL O DNI',
                'descripcion_corta' => 'L.E / DNI',
                'estado' => true
            ],
            [
                'tipo' => '04',
                'descripcion_larga' => 'CARNET DE EXTRANJERIA',
                'descripcion_corta' => 'CARNET EXT.',
                'estado' => true
            ],
            [
                'tipo' => '6',
                'descripcion_larga' => 'REG. UNICO DE CONTRIBUYENTES',
                'descripcion_corta' => 'RUC',
                'estado' => true
            ],
            [
                'tipo' => '07',
                'descripcion_larga' => 'PASAPORTE',
                'descripcion_corta' => 'PASAPORTE',
                'estado' => true
            ],
            [
                'tipo' => '11',
                'descripcion_larga' => 'PART. DE NACIMIENTO-IDENTIDAD',
                'descripcion_corta' => 'P. NAC.',
                'estado' => true
            ],
            [
                'tipo' => '00',
                'descripcion_larga' => 'OTROS',
                'descripcion_corta' => 'OTROS',
                'estado' => true
            ]
        ];

        foreach ($tipoDocumentos as $td) {
            DB::table('tipo_documentos')->insert([
                'tipo' => $td['tipo'],
                'descripcion_larga' => $td['descripcion_larga'],
                'descripcion_corta' => $td['descripcion_corta'],
                'estado' => $td['estado'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
