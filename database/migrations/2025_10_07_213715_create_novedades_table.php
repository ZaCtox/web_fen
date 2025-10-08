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
        Schema::create('novedades', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('contenido');
            $table->string('tipo')->nullable(); // Para compatibilidad
            $table->string('imagen')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('magister_id')->nullable()->constrained()->onDelete('set null');
            $table->boolean('visible_publico')->default(true);
            $table->json('roles_visibles')->nullable();
            $table->string('tipo_novedad');
            $table->boolean('es_urgente')->default(false);
            $table->datetime('fecha_expiracion')->nullable();
            $table->string('icono')->nullable();
            $table->string('color')->default('blue');
            $table->json('acciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('novedades');
    }
};
