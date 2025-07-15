<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('room_usages', function (Blueprint $table) {
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fin')->nullable();
            $table->dropColumn('horario'); // Solo si lo tenías antes como string
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_usages', function (Blueprint $table) {
            //
        });
    }
};
