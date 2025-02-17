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
        Schema::create('mesas', function (Blueprint $table) {
            $table->id();
            $table->integer('numero')->unique(); // Número de mesa
            $table->unsignedBigInteger('zona_id');
            $table->enum('estado', ['Ocupada', 'Libre', 'Inhabilitada'])->default('Libre');
            $table->timestamps();

            $table->foreign('zona_id')->references('id')->on('zonas')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesas');
    }
};
