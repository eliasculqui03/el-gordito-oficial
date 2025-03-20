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
        Schema::create('venta_platos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('venta_id');
            $table->unsignedBigInteger('plato_id');
            $table->integer('cantidad');
            $table->double('precio_unitario');
            $table->double('subtotal');
            $table->timestamps();

            $table->foreign('venta_id')->references('id')->on('ventas')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('plato_id')->references('id')->on('platos')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venta_platos');
    }
};
