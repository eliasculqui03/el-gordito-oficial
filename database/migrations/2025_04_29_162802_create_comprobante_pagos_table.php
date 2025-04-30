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
        Schema::create('comprobante_pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_comprobante_id')->constrained('tipo_comprobantes');
            $table->string('serie');
            $table->string('numero');
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->foreignId('comanda_id')->constrained('comandas');
            $table->string('moneda');
            $table->string('medio_pago');
            $table->string('hash_cpe')->nullable();
            $table->string('hash_cdr')->nullable();
            $table->longText('xml_cpe')->nullable();
            $table->longText('xml_cdr')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('caja_id')->constrained('cajas');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comprobante_pagos');
    }
};
