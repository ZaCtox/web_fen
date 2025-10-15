<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('periods', function (Blueprint $table) {
            // Primero agregar la columna si no existe
            if (!Schema::hasColumn('periods', 'magister_id')) {
                $table->unsignedBigInteger('magister_id')->nullable()->after('id');
            }
            // Luego agregar la foreign key
            $table->foreign('magister_id')->references('id')->on('magisters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('periods', function (Blueprint $table) {
            $table->dropForeign(['magister_id']);
            // Eliminar la columna si la agregamos en up()
            if (Schema::hasColumn('periods', 'magister_id')) {
                $table->dropColumn('magister_id');
            }
        });
    }
};


