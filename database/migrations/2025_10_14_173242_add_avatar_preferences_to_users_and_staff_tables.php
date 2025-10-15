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
        // Agregar campos a la tabla users
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_color')->nullable()->after('public_id'); // Color personalizado
        });

        // Agregar campos a la tabla staff
        Schema::table('staff', function (Blueprint $table) {
            $table->string('avatar_color')->nullable()->after('public_id'); // Color personalizado
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar_color']);
        });

        Schema::table('staff', function (Blueprint $table) {
            $table->dropColumn(['avatar_color']);
        });
    }
};
