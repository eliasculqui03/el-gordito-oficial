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
        Schema::create('comanda_existencias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comanda_id'); // Clave foránea para 'comandas'
            $table->unsignedBigInteger('existencia_id'); // Clave foránea para 'existencias'
            $table->integer('cantidad'); // Cantidad del producto
            $table->double('subtotal', 10, 2); // Subtotal con 2 decimales
            $table->enum('estado', ['Pendiente', 'Entregando', 'Completada', 'Cancelada'])->default('Pendiente'); // Estado con valores definidos
            $table->timestamps(); // 'created_at' y 'updated_at'

            // Definir claves foráneas
            $table->foreign('comanda_id')->references('id')->on('comandas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('existencia_id')->references('id')->on('existencias')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comanda_existencias');
    }
};
