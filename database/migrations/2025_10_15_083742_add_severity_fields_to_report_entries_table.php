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
        Schema::table('report_entries', function (Blueprint $table) {
            // Campos para la bitácora de asistente de postgrado
            $table->string('hora')->nullable()->after('observation'); // Horario (ej: "08:30 – 08:50")
            $table->integer('escala')->nullable()->after('hora'); // Escala de severidad (1-10)
            $table->string('programa')->nullable()->after('escala'); // Programa de magister
            $table->string('area')->nullable()->after('programa'); // Área (ej: "TERRENO")
            $table->text('tarea')->nullable()->after('area'); // Tarea (opcional)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('report_entries', function (Blueprint $table) {
            $table->dropColumn(['hora', 'escala', 'programa', 'area', 'tarea']);
        });
    }
};
