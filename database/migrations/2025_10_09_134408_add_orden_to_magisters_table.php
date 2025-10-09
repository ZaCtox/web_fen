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
        Schema::table('magisters', function (Blueprint $table) {
            $table->integer('orden')->default(999)->after('color');
        });

        // Actualizar el orden de los magísteres existentes
        DB::statement("UPDATE magisters SET orden = CASE 
            WHEN nombre = 'Gestión de Sistemas de Salud' THEN 1 
            WHEN nombre = 'Economía' THEN 2 
            WHEN nombre = 'Dirección y Planificación Tributaria' THEN 3 
            WHEN nombre = 'Gestión y Políticas Públicas' THEN 4 
            ELSE 999 
            END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('magisters', function (Blueprint $table) {
            $table->dropColumn('orden');
        });
    }
};
