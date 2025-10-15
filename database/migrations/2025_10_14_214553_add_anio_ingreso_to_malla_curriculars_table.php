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
        Schema::table('malla_curriculars', function (Blueprint $table) {
            // Agregar campo anio_ingreso para conectar con los períodos
            $table->integer('anio_ingreso')->nullable()->after('año_fin');
            
            // Índice para mejorar búsquedas
            $table->index('anio_ingreso');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('malla_curriculars', function (Blueprint $table) {
            $table->dropIndex(['anio_ingreso']);
            $table->dropColumn('anio_ingreso');
        });
    }
};
