<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('emergencies', function (Blueprint $table) {
            // Nueva columna: fecha de expiración automática (máx. 48 horas)
            $table->dateTime('expires_at')->nullable()->after('active');

            // Nueva columna: referencia al usuario que creó la alerta
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->onDelete('cascade')
                ->after('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emergencies', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn(['expires_at', 'created_by']);
        });
    }
};
