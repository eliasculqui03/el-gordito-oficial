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
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->unsignedBigInteger('zona_id');
            $table->unsignedBigInteger('mesa_id');
            $table->enum('estado', ['Abierta', 'Procesando', 'Completada', 'Cancelada'])->default('Abierta');
            $table->enum('estado_pago', ['Pendiente', 'Pagada'])->default('Pendiente');
            $table->timestamps();

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
