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
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // null = visitante anónimo
            $table->string('page_type', 50); // 'calendario_publico', 'calendario_admin', 'dashboard', 'incidencias', etc.
            $table->string('url', 500); // URL completa
            $table->string('method', 10)->default('GET'); // GET, POST, etc.
            $table->string('ip_address', 45)->nullable(); // IPv4 o IPv6
            $table->string('user_agent', 500)->nullable(); // Navegador/dispositivo
            $table->string('session_id', 255)->nullable(); // Para contar sesiones únicas
            $table->timestamp('visited_at'); // Fecha y hora del acceso
            $table->index(['page_type', 'visited_at']); // Índice para búsquedas rápidas
            $table->index(['user_id', 'visited_at']);
            $table->index('visited_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
