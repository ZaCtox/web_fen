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
        Schema::dropIfExists('malla_curriculars');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('malla_curriculars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('magister_id')->constrained('magisters')->onDelete('cascade');
            $table->string('nombre');
            $table->string('codigo')->unique();
            $table->integer('año_inicio');
            $table->integer('año_fin')->nullable();
            $table->integer('anio_ingreso')->nullable();
            $table->boolean('activa')->default(true);
            $table->text('descripcion')->nullable();
            $table->timestamps();
            
            $table->index('anio_ingreso');
        });
    }
};


