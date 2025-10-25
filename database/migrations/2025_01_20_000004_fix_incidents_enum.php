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
        if (Schema::hasTable('incidents') && Schema::hasColumn('incidents', 'estado')) {
            // Primero, eliminar el valor por defecto
            DB::statement("ALTER TABLE incidents ALTER COLUMN estado DROP DEFAULT");
            
            // Luego renombrar la columna
            Schema::table('incidents', function (Blueprint $table) {
                $table->renameColumn('estado', 'status');
            });
            
            // Finalmente, agregar el valor por defecto a la nueva columna
            DB::statement("ALTER TABLE incidents ALTER COLUMN status SET DEFAULT 'pendiente'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('incidents') && Schema::hasColumn('incidents', 'status')) {
            Schema::table('incidents', function (Blueprint $table) {
                $table->renameColumn('status', 'estado');
            });
        }
    }
};
