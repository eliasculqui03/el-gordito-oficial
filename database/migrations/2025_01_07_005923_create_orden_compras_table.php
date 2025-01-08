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
        Schema::create('orden_compras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('metodo_pago');
            $table->string('factura');
            $table->string('foto')->nullable();
            $table->double('igv', 5, 2); // Almacenado como porcentaje (ej. 18.00 representa 18%)
            $table->double('total', 10, 2);
            $table->timestamps();
        });

        Schema::create('detalle_orden_compras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orden_compra_id');
            $table->foreign('orden_compra_id')
                ->references('id')
                ->on('orden_compras')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('pedido_compra_id')->nullable();
            $table->foreign('pedido_compra_id')
                ->references('id')
                ->on('pedido_compras')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('existencia_id');
            $table->foreign('existencia_id')
                ->references('id')
                ->on('existencias')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('cantidad');
            $table->double('subtotal', 10, 2);
            $table->enum('estado', ['Pendiente', 'Procesada', 'Rechazada', 'Cancelada'])->default('Procesada');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_orden_compras');
        Schema::dropIfExists('orden_compras');
    }
};
