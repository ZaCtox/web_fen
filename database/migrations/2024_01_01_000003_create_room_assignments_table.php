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
        // ⚠️ MIGRACIÓN DESHABILITADA TEMPORALMENTE
        // Esta tabla ya no se usa o tiene problemas de dependencias
        // Si necesitas usarla, asegúrate de que la tabla 'rooms' exista primero
        
        // Verificar que la tabla rooms exista antes de crear room_assignments
        if (!Schema::hasTable('rooms')) {
            return; // ⛔ No crear la tabla si 'rooms' no existe
        }

        // Solo crear si no existe
        if (Schema::hasTable('room_assignments')) {
            return; // ⛔ La tabla ya existe
        }

        Schema::create('room_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->foreignId('subject_id')->nullable()->constrained('courses')->onDelete('cascade');
            $table->integer('academic_year');
            $table->integer('trimester');
            $table->string('schedule');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_assignments');
    }
};

