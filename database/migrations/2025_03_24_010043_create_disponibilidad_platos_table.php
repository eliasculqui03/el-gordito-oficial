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
        Schema::create('disponibilidad_platos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plato_id');
            $table->integer('cantidad');
            $table->timestamps();

            $table->foreign('plato_id')->references('id')->on('platos')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disponibilidad_platos');
    }
};
