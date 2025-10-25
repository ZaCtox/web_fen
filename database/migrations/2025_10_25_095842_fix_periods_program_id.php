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
        // Renombrar magister_id a program_id en la tabla periods
        if (Schema::hasTable('periods') && Schema::hasColumn('periods', 'magister_id')) {
            Schema::table('periods', function (Blueprint $table) {
                $table->renameColumn('magister_id', 'program_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir program_id a magister_id en la tabla periods
        if (Schema::hasTable('periods') && Schema::hasColumn('periods', 'program_id')) {
            Schema::table('periods', function (Blueprint $table) {
                $table->renameColumn('program_id', 'magister_id');
            });
        }
    }
};