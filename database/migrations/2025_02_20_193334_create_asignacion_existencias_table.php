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
        Schema::create('asignacion_existencias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comanda_existencia_id');
            $table->foreign('comanda_existencia_id')->references('id')->on('comanda_existencias')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')
                ->onDelete('cascade');
            $table->enum('estado', ['Asignado', 'Completado']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignacion_existencias');
    }
};
