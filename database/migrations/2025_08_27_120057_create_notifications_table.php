<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');           // Título de la notificación
            $table->text('message');           // Mensaje detallado
            $table->enum('type', ['info', 'success', 'warning', 'error'])->default('info'); // Tipo de notificación
            $table->boolean('is_active')->default(true); // Si se muestra o no
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
