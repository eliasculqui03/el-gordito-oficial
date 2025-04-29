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
            $table->unsignedBigInteger('comanda_id');
            $table->unsignedBigInteger('plato_id');
            $table->double('precio_unitario', 10, 2);
            $table->integer('cantidad');
            $table->double('subtotal', 10, 2);
            $table->boolean('llevar')->default(false);
            $table->enum('estado', ['Pendiente', 'Procesando', 'Listo', 'Entregando', 'Completado', 'Cancelado'])->default('Pendiente');
            $table->timestamps();

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
