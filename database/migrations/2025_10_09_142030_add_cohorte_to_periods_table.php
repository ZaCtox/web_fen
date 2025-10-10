<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('periods', function (Blueprint $table) {
            // Agregar campo cohorte para identificar el periodo académico completo
            $table->string('cohorte')->after('numero')->nullable()->index();
            // Ej: "2024-2025", "2025-2026"
        });

        // Actualizar cohortes existentes basándose en las fechas
        DB::statement("
            UPDATE periods 
            SET cohorte = CASE 
                WHEN YEAR(fecha_inicio) = 2024 OR (YEAR(fecha_inicio) = 2025 AND fecha_inicio < '2025-09-01') THEN '2024-2025'
                WHEN YEAR(fecha_inicio) = 2025 AND fecha_inicio >= '2025-09-01' OR YEAR(fecha_inicio) = 2026 THEN '2025-2026'
                ELSE CONCAT(YEAR(fecha_inicio), '-', YEAR(fecha_inicio) + 1)
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('periods', function (Blueprint $table) {
            $table->dropColumn('cohorte');
        });
    }
};
