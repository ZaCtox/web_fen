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
        Schema::table('novedades', function (Blueprint $table) {
            // Campos para el sistema de roles
            $table->json('roles_visibles')->nullable()->comment('Roles que pueden ver esta novedad');
            $table->string('tipo_novedad')->default('manual')->comment('manual, automatica, sistema');
            $table->boolean('es_urgente')->default(false)->comment('Si es una novedad urgente');
            $table->timestamp('fecha_expiracion')->nullable()->comment('Fecha cuando expira la novedad');
            $table->string('icono')->nullable()->comment('Icono SVG personalizado');
            $table->string('color')->default('blue')->comment('Color de la novedad');
            $table->json('acciones')->nullable()->comment('Acciones disponibles (enlaces, botones)');
            
            // Índices para optimizar consultas
            $table->index(['tipo_novedad', 'visible_publico']);
            $table->index(['fecha_expiracion']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('novedades', function (Blueprint $table) {
            $table->dropIndex(['tipo_novedad', 'visible_publico']);
            $table->dropIndex(['fecha_expiracion']);
            
            $table->dropColumn([
                'roles_visibles',
                'tipo_novedad', 
                'es_urgente',
                'fecha_expiracion',
                'icono',
                'color',
                'acciones'
            ]);
        });
    }
};
