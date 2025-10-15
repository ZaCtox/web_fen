<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * NOTA: Esta migración está vacía porque los usuarios NO necesitan
     * tener un año de ingreso asociado. El año de ingreso solo se usa
     * para los períodos académicos.
     */
    public function up(): void
    {
        // No se agrega ningún campo a users
        // Los usuarios simplemente ven todos los períodos disponibles
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No hay nada que revertir
    }
};

