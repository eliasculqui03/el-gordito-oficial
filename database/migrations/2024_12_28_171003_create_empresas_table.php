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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('tipo_actividad');
            $table->string('ruc');
            $table->string('nombre_comercial')->nullable();
            $table->string('numero_decreto')->nullable();
            $table->string('logo')->nullable();
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
            $table->string('direccion')->nullable();
            $table->string('moneda')->nullable();
            $table->text('mision')->nullable();
            $table->text('vision')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('facebook')->nullable();
            $table->string('youtube')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('nombre_gerente')->nullable();
            $table->string('dni_gerente')->nullable();
            $table->string('telefono_gerente')->nullable();
            $table->string('correo_gerente')->nullable();
            $table->string('direccion_gerente')->nullable();
            $table->string('fecha_ingreso_gerente')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
