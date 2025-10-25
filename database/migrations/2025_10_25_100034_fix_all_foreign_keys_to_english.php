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
        // 1. Tabla incidents - magister_id -> program_id
        if (Schema::hasTable('incidents') && Schema::hasColumn('incidents', 'magister_id')) {
            Schema::table('incidents', function (Blueprint $table) {
                $table->renameColumn('magister_id', 'program_id');
            });
        }

        // 2. Tabla events - magister_id -> program_id
        if (Schema::hasTable('events') && Schema::hasColumn('events', 'magister_id')) {
            Schema::table('events', function (Blueprint $table) {
                $table->renameColumn('magister_id', 'program_id');
            });
        }

        // 3. Tabla announcements (novedades) - magister_id -> program_id
        if (Schema::hasTable('announcements') && Schema::hasColumn('announcements', 'magister_id')) {
            Schema::table('announcements', function (Blueprint $table) {
                $table->renameColumn('magister_id', 'program_id');
            });
        }

        // 4. Tabla informes - magister_id -> program_id
        if (Schema::hasTable('informes') && Schema::hasColumn('informes', 'magister_id')) {
            Schema::table('informes', function (Blueprint $table) {
                $table->renameColumn('magister_id', 'program_id');
            });
        }

        // 5. Tabla classes - course_id -> module_id (verificar si existe)
        if (Schema::hasTable('classes') && Schema::hasColumn('classes', 'course_id')) {
            Schema::table('classes', function (Blueprint $table) {
                $table->renameColumn('course_id', 'module_id');
            });
        }

        // 6. Tabla class_sessions - clase_id -> class_id (verificar si existe)
        if (Schema::hasTable('class_sessions') && Schema::hasColumn('class_sessions', 'clase_id')) {
            Schema::table('class_sessions', function (Blueprint $table) {
                $table->renameColumn('clase_id', 'class_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir en orden inverso
        if (Schema::hasTable('class_sessions') && Schema::hasColumn('class_sessions', 'class_id')) {
            Schema::table('class_sessions', function (Blueprint $table) {
                $table->renameColumn('class_id', 'clase_id');
            });
        }

        if (Schema::hasTable('classes') && Schema::hasColumn('classes', 'module_id')) {
            Schema::table('classes', function (Blueprint $table) {
                $table->renameColumn('module_id', 'course_id');
            });
        }

        if (Schema::hasTable('informes') && Schema::hasColumn('informes', 'program_id')) {
            Schema::table('informes', function (Blueprint $table) {
                $table->renameColumn('program_id', 'magister_id');
            });
        }

        if (Schema::hasTable('announcements') && Schema::hasColumn('announcements', 'program_id')) {
            Schema::table('announcements', function (Blueprint $table) {
                $table->renameColumn('program_id', 'magister_id');
            });
        }

        if (Schema::hasTable('events') && Schema::hasColumn('events', 'program_id')) {
            Schema::table('events', function (Blueprint $table) {
                $table->renameColumn('program_id', 'magister_id');
            });
        }

        if (Schema::hasTable('incidents') && Schema::hasColumn('incidents', 'program_id')) {
            Schema::table('incidents', function (Blueprint $table) {
                $table->renameColumn('program_id', 'magister_id');
            });
        }
    }
};