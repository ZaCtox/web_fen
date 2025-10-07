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
        Schema::table('notifications', function (Blueprint $table) {
            // Agregar columnas que faltan
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('incident_id')->nullable()->constrained()->onDelete('cascade');
            $table->boolean('read')->default(false);
            
            // Agregar índices
            $table->index(['user_id', 'read']);
            $table->index(['incident_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['incident_id']);
            $table->dropIndex(['user_id', 'read']);
            $table->dropIndex(['incident_id']);
            $table->dropColumn(['user_id', 'incident_id', 'read']);
        });
    }
};