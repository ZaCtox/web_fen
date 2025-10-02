<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('informes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('mime');
            $table->string('archivo'); // <-- Guardamos la RUTA del archivo, no el binario

            // Relaciones
            $table->unsignedBigInteger('user_id');     // quién lo subió
            $table->unsignedBigInteger('magister_id')->nullable(); // a qué magister va dirigido (null = todos)

            $table->timestamps();

            // Llaves foráneas
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('magister_id')->references('id')->on('magisters')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informes');
    }
};
