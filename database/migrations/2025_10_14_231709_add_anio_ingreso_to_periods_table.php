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
        Schema::table('periods', function (Blueprint $table) {
            // Agregar la columna anio_ingreso si no existe
            if (!Schema::hasColumn('periods', 'anio_ingreso')) {
                $table->integer('anio_ingreso')->nullable()->index()->after('anio');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('periods', function (Blueprint $table) {
            // Eliminar la columna anio_ingreso si existe
            if (Schema::hasColumn('periods', 'anio_ingreso')) {
                $table->dropColumn('anio_ingreso');
            }
        });
    }
};
