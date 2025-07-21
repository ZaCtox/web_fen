<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('trimestres', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Ej: Trimestre 1
            $table->year('año');
            $table->unsignedTinyInteger('numero'); // 1, 2 o 3
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trimestres');
    }
};
