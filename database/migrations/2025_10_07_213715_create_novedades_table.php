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
        // Solo crear la tabla si no existe
        if (!Schema::hasTable('novedades')) {
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
        } else {
            // Si la tabla ya existe, verificar y agregar columnas faltantes
            Schema::table('novedades', function (Blueprint $table) {
                if (!Schema::hasColumn('novedades', 'roles_visibles')) {
                    $table->json('roles_visibles')->nullable();
                }
                if (!Schema::hasColumn('novedades', 'tipo_novedad')) {
                    $table->string('tipo_novedad')->default('manual');
                }
                if (!Schema::hasColumn('novedades', 'es_urgente')) {
                    $table->boolean('es_urgente')->default(false);
                }
                if (!Schema::hasColumn('novedades', 'fecha_expiracion')) {
                    $table->datetime('fecha_expiracion')->nullable();
                }
                if (!Schema::hasColumn('novedades', 'icono')) {
                    $table->string('icono')->nullable();
                }
                if (!Schema::hasColumn('novedades', 'color')) {
                    $table->string('color')->default('blue');
                }
                if (!Schema::hasColumn('novedades', 'acciones')) {
                    $table->json('acciones')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('novedades');
    }
};
