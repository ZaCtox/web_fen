<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // 🔽 Eliminar de courses
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('encargado');
        });

        // 🔼 Agregar a clases
        Schema::table('clases', function (Blueprint $table) {
            $table->string('encargado')->nullable()->after('course_id'); // o después de 'modality'
        });
    }

    public function down(): void {
        // 🔁 Restaurar en courses
        Schema::table('courses', function (Blueprint $table) {
            $table->string('encargado')->nullable();
        });

        // 🔁 Quitar de clases
        Schema::table('clases', function (Blueprint $table) {
            $table->dropColumn('encargado');
        });
    }
};
