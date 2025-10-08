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
        Schema::table('bitacoras', function (Blueprint $table) {
            // Agregar título del reporte
            $table->string('titulo')->after('id');
            
            // Agregar relación con usuario que creó el reporte
            $table->unsignedBigInteger('user_id')->after('titulo');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Hacer lugar nullable ya que ahora tenemos título
            $table->string('lugar')->nullable()->change();
            
            // Hacer descripción requerida
            $table->text('descripcion')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bitacoras', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['titulo', 'user_id']);
            $table->string('lugar')->nullable(false)->change();
            $table->text('descripcion')->nullable()->change();
        });
    }
};
