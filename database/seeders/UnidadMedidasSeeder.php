<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnidadMedidasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unidadMedidas = [
            ['codigo' => '4ABO', 'descripcion' => 'BOBINAS', 'simbolo' => 'BOB'],
            ['codigo' => 'BJ', 'descripcion' => 'BALDE', 'simbolo' => 'BLD'],
            ['codigo' => 'BLL', 'descripcion' => 'BARRILES', 'simbolo' => 'BRL'],
            ['codigo' => 'BG', 'descripcion' => 'BOLSA', 'simbolo' => 'BSA'],
            ['codigo' => 'BO', 'descripcion' => 'BOTELLAS', 'simbolo' => 'BOT'],
            ['codigo' => 'BX', 'descripcion' => 'CAJA', 'simbolo' => 'CJA'],
            ['codigo' => 'CT', 'descripcion' => 'CARTONES', 'simbolo' => 'CTN'],
            ['codigo' => 'CMK', 'descripcion' => 'CENTIMETRO CUADRADO', 'simbolo' => 'cm²'],
            ['codigo' => 'CMQ', 'descripcion' => 'CENTIMETRO CUBICO', 'simbolo' => 'cm³'],
            ['codigo' => 'CMT', 'descripcion' => 'CENTIMETRO LINEAL', 'simbolo' => 'cm'],
            ['codigo' => 'CEN', 'descripcion' => 'CIENTO DE UNIDADES', 'simbolo' => '100u'],
            ['codigo' => 'CY', 'descripcion' => 'CILINDRO', 'simbolo' => 'CIL'],
            ['codigo' => 'CJ', 'descripcion' => 'CONOS', 'simbolo' => 'CON'],
            ['codigo' => 'DZN', 'descripcion' => 'DOCENA', 'simbolo' => 'DOC'],
            ['codigo' => 'DZP', 'descripcion' => 'DOCENA POR 10**6', 'simbolo' => 'DOC⁶'],
            ['codigo' => 'BE', 'descripcion' => 'FARDO', 'simbolo' => 'FRD'],
            ['codigo' => 'GLI', 'descripcion' => 'GALON INGLES (4,545956L)', 'simbolo' => 'gal.i'],
            ['codigo' => 'GRM', 'descripcion' => 'GRAMO', 'simbolo' => 'g'],
            ['codigo' => 'GRO', 'descripcion' => 'GRUESA', 'simbolo' => 'GRS'],
            ['codigo' => 'HLT', 'descripcion' => 'HECTOLITRO', 'simbolo' => 'hl'],
            ['codigo' => 'LEF', 'descripcion' => 'HOJA', 'simbolo' => 'HJA'],
            ['codigo' => 'SET', 'descripcion' => 'JUEGO', 'simbolo' => 'JGO'],
            ['codigo' => 'KGM', 'descripcion' => 'KILOGRAMO', 'simbolo' => 'kg'],
            ['codigo' => 'KTM', 'descripcion' => 'KILOMETRO', 'simbolo' => 'km'],
            ['codigo' => 'KWH', 'descripcion' => 'KILOVATIO HORA', 'simbolo' => 'kWh'],
            ['codigo' => 'KT', 'descripcion' => 'KIT', 'simbolo' => 'KIT'],
            ['codigo' => 'CA', 'descripcion' => 'LATAS', 'simbolo' => 'LAT'],
            ['codigo' => 'LBR', 'descripcion' => 'LIBRAS', 'simbolo' => 'lb'],
            ['codigo' => 'LTR', 'descripcion' => 'LITRO', 'simbolo' => 'L'],
            ['codigo' => 'MWH', 'descripcion' => 'MEGAWATT HORA', 'simbolo' => 'MWh'],
            ['codigo' => 'MTR', 'descripcion' => 'METRO', 'simbolo' => 'm'],
            ['codigo' => 'MTK', 'descripcion' => 'METRO CUADRADO', 'simbolo' => 'm²'],
            ['codigo' => 'MTQ', 'descripcion' => 'METRO CUBICO', 'simbolo' => 'm³'],
            ['codigo' => 'MGM', 'descripcion' => 'MILIGRAMOS', 'simbolo' => 'mg'],
            ['codigo' => 'MLT', 'descripcion' => 'MILILITRO', 'simbolo' => 'ml'],
            ['codigo' => 'MMT', 'descripcion' => 'MILIMETRO', 'simbolo' => 'mm'],
            ['codigo' => 'MMK', 'descripcion' => 'MILIMETRO CUADRADO', 'simbolo' => 'mm²'],
            ['codigo' => 'MMQ', 'descripcion' => 'MILIMETRO CUBICO', 'simbolo' => 'mm³'],
            ['codigo' => 'MIL', 'descripcion' => 'MILLARES', 'simbolo' => '1000u'],
            ['codigo' => 'UM', 'descripcion' => 'MILLON DE UNIDADES', 'simbolo' => '10⁶u'],
            ['codigo' => 'ONZ', 'descripcion' => 'ONZAS', 'simbolo' => 'oz'],
            ['codigo' => 'PF', 'descripcion' => 'PALETAS', 'simbolo' => 'PAL'],
            ['codigo' => 'PK', 'descripcion' => 'PAQUETE', 'simbolo' => 'PQT'],
            ['codigo' => 'PR', 'descripcion' => 'PAR', 'simbolo' => 'PAR'],
            ['codigo' => 'FOT', 'descripcion' => 'PIES', 'simbolo' => 'ft'],
            ['codigo' => 'FTK', 'descripcion' => 'PIES CUADRADOS', 'simbolo' => 'ft²'],
            ['codigo' => 'FTQ', 'descripcion' => 'PIES CUBICOS', 'simbolo' => 'ft³'],
            ['codigo' => 'C62', 'descripcion' => 'PIEZAS', 'simbolo' => 'PZA'],
            ['codigo' => 'PG', 'descripcion' => 'PLACAS', 'simbolo' => 'PLC'],
            ['codigo' => 'ST', 'descripcion' => 'PLIEGO', 'simbolo' => 'PLG'],
            ['codigo' => 'INH', 'descripcion' => 'PULGADAS', 'simbolo' => 'in'],
            ['codigo' => 'RM', 'descripcion' => 'RESMA', 'simbolo' => 'RSM'],
            ['codigo' => 'DR', 'descripcion' => 'TAMBOR', 'simbolo' => 'TBR'],
            ['codigo' => 'STN', 'descripcion' => 'TONELADA CORTA', 'simbolo' => 't.c'],
            ['codigo' => 'LTN', 'descripcion' => 'TONELADA LARGA', 'simbolo' => 't.l'],
            ['codigo' => 'TNE', 'descripcion' => 'TONELADAS', 'simbolo' => 't'],
            ['codigo' => 'TU', 'descripcion' => 'TUBOS', 'simbolo' => 'TBO'],
            ['codigo' => 'NIU', 'descripcion' => 'UNIDAD (BIENES)', 'simbolo' => 'u'],
            ['codigo' => 'ZZ', 'descripcion' => 'UNIDAD (SERVICIOS)', 'simbolo' => 'serv'],
            ['codigo' => 'GLL', 'descripcion' => 'US GALON (3,7843 L)', 'simbolo' => 'gal'],
            ['codigo' => 'YRD', 'descripcion' => 'YARDA', 'simbolo' => 'yd'],
            ['codigo' => 'YDK', 'descripcion' => 'YARDA CUADRADA', 'simbolo' => 'yd²']
        ];

        foreach ($unidadMedidas as $um) {
            DB::table('unidad_medidas')->insert([
                'codigo' => $um['codigo'],
                'descripcion' => $um['descripcion'],
                'simbolo' => $um['simbolo'],
                'estado' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
