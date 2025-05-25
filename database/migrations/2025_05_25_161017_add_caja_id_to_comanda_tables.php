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
        Schema::table('comandas', function (Blueprint $table) {
            $table->unsignedBigInteger('caja_id')->after('mesa_id')->nullable();
            $table->foreign('caja_id')->references('id')->on('cajas')->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comandas', function (Blueprint $table) {
            $table->dropForeign(['caja_id']);

            // Eliminar las columnas
            $table->dropColumn('caja_id');
        });
    }
};
