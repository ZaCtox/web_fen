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
            // Campos simples para coffee break
            $table->time('coffee_break_inicio')->nullable()->after('bloques_horarios');
            $table->time('coffee_break_fin')->nullable()->after('coffee_break_inicio');
            
            // Campos simples para lunch break
            $table->time('lunch_break_inicio')->nullable()->after('coffee_break_fin');
            $table->time('lunch_break_fin')->nullable()->after('lunch_break_inicio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clase_sesiones', function (Blueprint $table) {
            $table->dropColumn(['coffee_break_inicio', 'coffee_break_fin', 'lunch_break_inicio', 'lunch_break_fin']);
        });
    }
};
