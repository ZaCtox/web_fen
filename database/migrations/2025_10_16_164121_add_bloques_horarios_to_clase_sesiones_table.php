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
        Schema::table('clase_sesiones', function (Blueprint $table) {
            // Campo JSON para almacenar bloques horarios con breaks
            // Estructura: [{"tipo": "clase", "inicio": "09:00", "fin": "10:30"}, {"tipo": "coffee_break", "inicio": "10:30", "fin": "11:00"}, ...]
            $table->json('bloques_horarios')->nullable()->after('hora_fin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clase_sesiones', function (Blueprint $table) {
            $table->dropColumn('bloques_horarios');
        });
    }
};
