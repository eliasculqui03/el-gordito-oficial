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
        Schema::create('comandas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id')->nullable(); // Clave foránea hacia la tabla 'clientes'
            $table->unsignedBigInteger('zona_id'); // Clave foránea hacia la tabla 'zonas'
            $table->unsignedBigInteger('mesa_id'); // Clave foránea hacia la tabla 'mesas'
            $table->enum('estado', ['Abierta', 'Cerrada', 'Cancelada'])->default('Abierta'); // Estados de la comanda
            $table->timestamps(); // Incluye created_at y updated_at

            // Definir claves foráneas
            $table->foreign('cliente_id')->references('id')->on('clientes')->nullOnDelete()->onUpdate('cascade');
            $table->foreign('zona_id')->references('id')->on('zonas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('mesa_id')->references('id')->on('mesas')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comandas');
    }
};
