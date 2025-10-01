<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Primero actualizamos los datos existentes para evitar conflictos
        DB::statement("UPDATE clases SET modality = 'híbrida' WHERE modality = 'hibrida'");

        // Luego modificamos la definición del ENUM
        DB::statement("ALTER TABLE clases 
            MODIFY modality ENUM('presencial', 'online', 'híbrida') 
            NOT NULL DEFAULT 'presencial'");
    }

    public function down(): void
    {
        // Revertimos los datos a 'hibrida'
        DB::statement("UPDATE clases SET modality = 'hibrida' WHERE modality = 'híbrida'");

        // Restauramos la definición original del ENUM
        DB::statement("ALTER TABLE clases 
            MODIFY modality ENUM('presencial', 'online', 'hibrida') 
            NOT NULL DEFAULT 'presencial'");
    }
};
