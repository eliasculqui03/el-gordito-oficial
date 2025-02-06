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
        Schema::table('categoria_existencias', function (Blueprint $table) {
            $table->unsignedBigInteger('tipo_existencia_id')->after('nombre');
            $table->foreign('tipo_existencia_id')->references('id')->on('tipo_existencias')
                ->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categoria_existencias', function (Blueprint $table) {
            //
            $table->dropForeign(['tipo_existencia_id']);
            $table->dropColumn('tipo_existencia_id');
        });
    }
};
