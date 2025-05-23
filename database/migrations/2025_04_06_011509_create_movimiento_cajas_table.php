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
        Schema::create('movimiento_cajas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('sesion_caja_id')->constrained('sesion_cajas');
            $table->enum('tipo_transaccion', ['Ingreso', 'Egreso']);
            $table->enum('motivo', ['Venta', 'Transferencia', 'Ajuste', 'Retiro']);
            $table->decimal('monto', 10, 2);
            $table->string('descripcion', 255)->nullable();

            $table->unsignedBigInteger('caja_id')->nullable();
            $table->foreign('caja_id')->references('id')->on('cajas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimiento_cajas');
    }
};
