<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->char('ubigeo', 8)->after('numero_decreto');
            $table->string('departamento')->after('direccion');
            $table->string('provincia')->after('departamento');
            $table->string('distrito')->after('provincia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn([
                'ubigeo',
                'departamento',
                'provincia',
                'distrito'
            ]);
        });
    }
};
