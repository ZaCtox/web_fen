<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Desactiva FKs por si hay restos de llaves
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Si aún hay columnas que apuntaban a estas tablas, suéltalas primero (defensivo)
        if (Schema::hasTable('courses') && Schema::hasColumn('courses', 'trimestre_id')) {
            Schema::table('courses', function (Blueprint $table) {
                // Nombre del constraint puede variar; dropConstrainedForeignId lo maneja
                $table->dropConstrainedForeignId('trimestre_id');
            });
        }

        // Elimina tablas legacy si existen
        Schema::dropIfExists('room_usages');
        Schema::dropIfExists('trimestres');

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        // (Opcional) recrea lo mínimo, por si haces rollback.
        Schema::create('trimestres', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable();
            $table->timestamps();
        });

        Schema::create('room_usages', function (Blueprint $table) {
            $table->id();
            // columnas mínimas para no romper (ajusta si necesitas)
            $table->foreignId('room_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('period_id')->nullable()->constrained()->nullOnDelete();
            $table->string('dia')->nullable();
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fin')->nullable();
            $table->timestamps();
        });
    }
};
