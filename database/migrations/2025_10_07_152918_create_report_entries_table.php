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
        Schema::create('report_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('daily_report_id');
            $table->string('location_type'); // Sala, Baño, Pasillo, Laboratorio, Oficina, Otro
            $table->unsignedBigInteger('room_id')->nullable(); // Si es sala específica
            $table->string('location_detail')->nullable(); // Detalle si no es sala
            $table->text('observation'); // Lo que observó
            $table->string('photo_url')->nullable(); // Foto de la observación
            $table->integer('order')->default(0); // Orden de las entradas
            $table->timestamps();

            $table->foreign('daily_report_id')->references('id')->on('daily_reports')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('set null');
            $table->index(['daily_report_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_entries');
    }
};