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
        Schema::create('bitacoras', function (Blueprint $table) {
            $table->id();
            
            // Relación con salas (opcional)
            $table->unsignedBigInteger('room_id')->nullable();
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('set null');
            
            // Lugar general: sala, baño, pasillo, otro
            $table->string('lugar');
            
            // Detalle si corresponde (ej: "baño primer piso", "pasillo norte", "baño asistentes")
            $table->string('detalle_ubicacion')->nullable();

            // Info adicional
            $table->text('descripcion')->nullable();
            $table->string('foto_url')->nullable(); // Cloudinary
            $table->string('pdf_path')->nullable(); // PDF almacenado

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacoras');
    }
};
