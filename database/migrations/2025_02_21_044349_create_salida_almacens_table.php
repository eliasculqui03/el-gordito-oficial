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
        Schema::create('salida_almacens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comanda_existencia_id')->nullable();
            $table->foreign('comanda_existencia_id')->references('id')->on('comanda_existencias')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('existencia_id');
            $table->foreign('existencia_id')->references('id')->on('existencias')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('almacen_id');
            $table->foreign('almacen_id')->references('id')->on('almacens')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->integer('cantidad');
            $table->string('nota')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salida_almacens');
    }
};
