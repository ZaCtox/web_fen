<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id(); // id tipo unsignedBigInteger
            $table->string('name'); // nombre de la sala
            $table->string('location')->nullable(); // ubicación opcional
            $table->integer('capacity')->nullable(); // capacidad opcional
            $table->text('description')->nullable(); // descripción
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
