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
        Schema::create('malla_curriculars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('magister_id')->constrained('magisters')->onDelete('cascade');
            $table->string('nombre'); // ej: "Malla 2024-2026"
            $table->string('codigo')->unique(); // ej: "GSS-2024-V1"
            $table->integer('año_inicio');
            $table->integer('año_fin')->nullable();
            $table->boolean('activa')->default(true);
            $table->text('descripcion')->nullable();
            $table->timestamps();
            
            // Índices para mejorar búsquedas
            $table->index(['magister_id', 'activa']);
            $table->index('año_inicio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('malla_curriculars');
    }
};
