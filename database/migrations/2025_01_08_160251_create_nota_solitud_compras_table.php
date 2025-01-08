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
        Schema::create('nota_solitud_compras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('solicitud_compra_id');
            $table->foreign('solicitud_compra_id')
                ->references('id')
                ->on('solicitud_compras')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->text('nota');
            $table->boolean('estado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nota_solitud_compras');
    }
};
