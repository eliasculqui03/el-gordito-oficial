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
        Schema::create('comanda_platos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comanda_id'); // Clave foránea hacia 'comandas'
            $table->unsignedBigInteger('plato_id'); // Clave foránea hacia 'platos'
            $table->integer('cantidad'); // Cantidad del plato solicitado
            $table->double('subtotal', 10, 2); // Subtotal de la cantidad * precio_unitario
            $table->enum('estado', ['Pendiente', 'Procesando', 'Entregando', 'Completado', 'Cancelada'])->default('Pendiente'); // Estado del registro
            $table->timestamps(); // Campos 'created_at' y 'updated_at'

            // Definir claves foráneas
            $table->foreign('comanda_id')->references('id')->on('comandas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('plato_id')->references('id')->on('platos')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comanda_platos');
    }
};
