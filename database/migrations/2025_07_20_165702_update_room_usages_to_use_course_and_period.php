<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('room_usages', function (Blueprint $table) {
            // Solo eliminar columnas si existen
            if (Schema::hasColumn('room_usages', 'subject')) {
                $table->dropColumn('subject');
            }

            if (Schema::hasColumn('room_usages', 'magister')) {
                $table->dropColumn('magister');
            }

            // Agregar nuevas columnas si aún no existen
            if (!Schema::hasColumn('room_usages', 'period_id')) {
                $table->foreignId('period_id')->constrained()->onDelete('cascade');
            }

            if (!Schema::hasColumn('room_usages', 'course_id')) {
                $table->foreignId('course_id')->constrained()->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('room_usages', function (Blueprint $table) {
            // Eliminar nuevas columnas si existen
            if (Schema::hasColumn('room_usages', 'period_id')) {
                $table->dropForeign(['period_id']);
                $table->dropColumn('period_id');
            }

            if (Schema::hasColumn('room_usages', 'course_id')) {
                $table->dropForeign(['course_id']);
                $table->dropColumn('course_id');
            }

            // Restaurar columnas eliminadas
            if (!Schema::hasColumn('room_usages', 'subject')) {
                $table->string('subject')->nullable();
            }

            if (!Schema::hasColumn('room_usages', 'magister')) {
                $table->string('magister')->nullable();
            }
        });
    }
};
