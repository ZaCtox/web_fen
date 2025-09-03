<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_satisfaction', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('rating'); // 1-5 estrellas
            $table->text('comment')->nullable();
            $table->string('category'); // 'general', 'salas', 'incidencias', 'eventos'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_satisfaction');
    }
};
