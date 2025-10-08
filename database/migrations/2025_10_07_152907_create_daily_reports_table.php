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
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // "Reporte Jueves 29 de Octubre 2025"
            $table->date('report_date'); // 2025-10-29
            $table->text('summary')->nullable(); // Resumen general del día
            $table->unsignedBigInteger('user_id'); // Quien creó el reporte
            $table->string('pdf_path')->nullable(); // PDF generado
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['report_date', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};