<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('room_usages', function (Blueprint $table) {
            // 🔧 Eliminar primero la foreign key
            $table->dropForeign('room_usages_trimestre_id_foreign');

            // Luego eliminar columnas
            $table->dropColumn(['trimestre_id', 'subject', 'magister']);

            // Agregar las nuevas columnas y relaciones
            $table->foreignId('period_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('room_usages', function (Blueprint $table) {
            $table->dropForeign(['period_id']);
            $table->dropForeign(['course_id']);
            $table->dropColumn(['period_id', 'course_id']);

            $table->unsignedBigInteger('trimestre_id')->nullable();
            $table->string('subject')->nullable();
            $table->string('magister')->nullable();
        });
    }
};
