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
            $table->unsignedBigInteger('comanda_id');
            $table->unsignedBigInteger('existencia_id');
            $table->integer('cantidad');
            $table->double('subtotal', 10, 2);
            $table->boolean('helado')->default(false);
            $table->enum('estado', ['Pendiente', 'Procesando', 'Listo', 'Entregando', 'Completado', 'Cancelado'])->default('Pendiente');
            $table->timestamps();

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
