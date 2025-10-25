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
        // 1. Renombrar tabla 'courses' a 'modules'
        if (Schema::hasTable('courses')) {
            Schema::rename('courses', 'modules');
        }

        // 2. Renombrar columnas en tabla 'modules'
        if (Schema::hasTable('modules')) {
            Schema::table('modules', function (Blueprint $table) {
                if (Schema::hasColumn('modules', 'nombre')) {
                    $table->renameColumn('nombre', 'name');
                }
                if (Schema::hasColumn('modules', 'programa')) {
                    $table->renameColumn('programa', 'program');
                }
                if (Schema::hasColumn('modules', 'magister_id')) {
                    $table->renameColumn('magister_id', 'program_id');
                }
            });
        }

        // 3. Actualizar foreign keys en otras tablas
        if (Schema::hasTable('classes')) {
            Schema::table('classes', function (Blueprint $table) {
                if (Schema::hasColumn('classes', 'course_id')) {
                    $table->renameColumn('course_id', 'module_id');
                }
            });
        }

        if (Schema::hasTable('class_sessions')) {
            Schema::table('class_sessions', function (Blueprint $table) {
                if (Schema::hasColumn('class_sessions', 'clase_id')) {
                    $table->renameColumn('clase_id', 'class_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir foreign keys
        if (Schema::hasTable('class_sessions')) {
            Schema::table('class_sessions', function (Blueprint $table) {
                if (Schema::hasColumn('class_sessions', 'class_id')) {
                    $table->renameColumn('class_id', 'clase_id');
                }
            });
        }

        if (Schema::hasTable('classes')) {
            Schema::table('classes', function (Blueprint $table) {
                if (Schema::hasColumn('classes', 'module_id')) {
                    $table->renameColumn('module_id', 'course_id');
                }
            });
        }

        // Revertir columnas en tabla 'modules'
        if (Schema::hasTable('modules')) {
            Schema::table('modules', function (Blueprint $table) {
                if (Schema::hasColumn('modules', 'name')) {
                    $table->renameColumn('name', 'nombre');
                }
                if (Schema::hasColumn('modules', 'program')) {
                    $table->renameColumn('program', 'programa');
                }
                if (Schema::hasColumn('modules', 'program_id')) {
                    $table->renameColumn('program_id', 'magister_id');
                }
            });
        }

        // Revertir nombre de tabla
        if (Schema::hasTable('modules')) {
            Schema::rename('modules', 'courses');
        }
    }
};
