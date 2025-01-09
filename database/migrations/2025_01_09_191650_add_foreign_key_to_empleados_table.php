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
        Schema::table('empleados', function (Blueprint $table) {

            $table->unsignedBigInteger('tipo_documento_id')->after('nombre');
            $table->foreign('tipo_documento_id')
                ->references('id')->on('tipo_documentos')
                ->onDelete('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->dropForeign(['tipo_documento_id']); // Elimina la relación foránea
            $table->dropColumn('tipo_documento_id');   // Elimina la columna
        });
    }
};
