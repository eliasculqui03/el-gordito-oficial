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
        Schema::table('existencias', function (Blueprint $table) {

            $table->unsignedBigInteger('area_existencia_id')->after('precio_venta'); // Agrega la columna después del ID
            $table->foreign('area_existencia_id')->references('id')->on('area_existencias')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('existencias', function (Blueprint $table) {
            $table->dropForeign(['area_existencia_id']);
            $table->dropColumn('area_existencia_id');
        });
    }
};
