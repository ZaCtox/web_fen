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
        // 1. Agregar campos a clase_sesiones
        Schema::table('clase_sesiones', function (Blueprint $table) {
            $table->string('dia')->after('fecha')->nullable(); // Viernes/Sábado
            $table->time('hora_inicio')->after('dia')->nullable();
            $table->time('hora_fin')->after('hora_inicio')->nullable();
            $table->enum('modalidad', ['presencial', 'online', 'hibrida'])->after('hora_fin')->nullable();
            $table->foreignId('room_id')->after('modalidad')->nullable()->constrained()->onDelete('set null');
            $table->string('url_zoom')->after('room_id')->nullable();
            $table->integer('numero_sesion')->after('url_zoom')->nullable();
        });

        // 2. Simplificar tabla clases (eliminamos campos que ahora están en sesiones)
        Schema::table('clases', function (Blueprint $table) {
            // Estos campos ahora están en clase_sesiones
            $table->dropColumn(['dia', 'hora_inicio', 'hora_fin', 'modality']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurar campos en clases
        Schema::table('clases', function (Blueprint $table) {
            $table->string('dia')->nullable();
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fin')->nullable();
            $table->enum('modality', ['presencial', 'online', 'hibrida'])->nullable();
        });

        // Eliminar campos de clase_sesiones
        Schema::table('clase_sesiones', function (Blueprint $table) {
            $table->dropForeign(['room_id']);
            $table->dropColumn(['dia', 'hora_inicio', 'hora_fin', 'modalidad', 'room_id', 'url_zoom', 'numero_sesion']);
        });
    }
};

