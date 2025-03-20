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
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipo_comprobante_id');
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('comanda_id');
            $table->string('metodo_pago');
            $table->double('subtotal');
            $table->double('igv');
            $table->double('total');
            $table->unsignedBigInteger('user_id');
            $table->text('observaciones')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('tipo_comprobante_id')->references('id')->on('tipo_comprobantes')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('cliente_id')->references('id')->on('clientes')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('comanda_id')->references('id')->on('comandas')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
