<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'magister')) {
                $table->dropColumn('magister'); // Eliminar columna antigua
            }
            $table->foreignId('magister_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    public function down(): void {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['magister_id']);
            $table->dropColumn('magister_id');
            $table->string('magister')->nullable(); // Restaurar si se hace rollback
        });
    }
};
