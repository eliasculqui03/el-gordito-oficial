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
        Schema::create('ingreso_almacens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detalle_orden_compra_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('existencia_id');
            $table->integer('cantidad');
            $table->unsignedBigInteger('almacen_id');

            // Foreign keys
            $table->foreign('detalle_orden_compra_id')->references('id')->on('detalle_orden_compras')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('existencia_id')->references('id')->on('existencias')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('almacen_id')->references('id')->on('almacens')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingreso_almacens');
    }
};
