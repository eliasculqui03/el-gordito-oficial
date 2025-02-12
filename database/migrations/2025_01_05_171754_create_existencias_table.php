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
        Schema::create('existencias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->unsignedBigInteger('tipo_existencia_id');
            $table->foreign('tipo_existencia_id')->references('id')->on('tipo_existencias')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('categoria_existencia_id');
            $table->foreign('categoria_existencia_id')->references('id')->on('categoria_existencias')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('unidad_medida_id');
            $table->foreign('unidad_medida_id')->references('id')->on('unidad_medidas')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->double('precio_compra');
            $table->double('precio_venta');

            $table->text('descripcion')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('existencias');
    }
};
