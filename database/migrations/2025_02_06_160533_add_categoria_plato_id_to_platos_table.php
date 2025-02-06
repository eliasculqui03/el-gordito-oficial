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
        Schema::table('platos', function (Blueprint $table) {
            $table->unsignedBigInteger('categoria_plato_id')->after('nombre');
            $table->foreign('categoria_plato_id')->references('id')->on('categoria_platos')
                ->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('platos', function (Blueprint $table) {
            $table->dropForeign(['categoria_plato_id']);
            $table->dropColumn('categoria_plato_id');
        });
    }
};
