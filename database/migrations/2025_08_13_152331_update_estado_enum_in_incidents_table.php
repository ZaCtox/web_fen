<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Cambiar la definición ENUM del campo 'estado'
        DB::statement("ALTER TABLE incidents MODIFY estado ENUM('pendiente', 'en_revision', 'resuelta', 'no_resuelta') DEFAULT 'pendiente'");
    }

    public function down(): void
    {
        // Revertir a los valores originales
        DB::statement("ALTER TABLE incidents MODIFY estado ENUM('pendiente', 'resuelta','no_resuelta') DEFAULT 'pendiente'");
    }
};
