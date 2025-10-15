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
        Schema::create('periods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('magister_id')->nullable(); // Se agregará foreign key después
            $table->integer('numero'); // 1, 2 o 3
            $table->integer('anio'); // 1 o 2 (año académico)
            $table->integer('anio_ingreso')->nullable()->index(); // 2024, 2025, 2026 (año de ingreso de la cohorte)
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periods');
    }
};
