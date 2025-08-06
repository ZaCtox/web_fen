<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->boolean('calefaccion')->default(false);
            $table->boolean('energia_electrica')->default(false);
            $table->boolean('existe_aseo')->default(false);
            $table->boolean('plumones')->default(false);
            $table->boolean('borrador')->default(false);
            $table->boolean('pizarra_limpia')->default(false);
            $table->boolean('computador_funcional')->default(false);
            $table->boolean('cables_computador')->default(false);
            $table->boolean('control_remoto_camara')->default(false);
            $table->boolean('televisor_funcional')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn([
                'calefaccion',
                'energia_electrica',
                'existe_aseo',
                'plumones',
                'borrador',
                'pizarra_limpia',
                'computador_funcional',
                'cables_computador',
                'control_remoto_camara',
                'televisor_funcional',
            ]);
        });
    }
};
