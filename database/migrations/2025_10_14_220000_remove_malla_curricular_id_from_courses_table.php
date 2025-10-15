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
        Schema::table('courses', function (Blueprint $table) {
            // Eliminar la relación con malla curricular
            $table->dropForeign(['malla_curricular_id']);
            $table->dropIndex(['malla_curricular_id']);
            $table->dropColumn('malla_curricular_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // Restaurar la relación con malla curricular
            $table->foreignId('malla_curricular_id')
                  ->nullable()
                  ->after('magister_id')
                  ->constrained('malla_curriculars')
                  ->onDelete('set null');
            
            $table->index('malla_curricular_id');
        });
    }
};

