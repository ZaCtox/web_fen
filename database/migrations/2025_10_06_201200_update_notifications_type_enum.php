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
        Schema::table('notifications', function (Blueprint $table) {
            // Cambiar el ENUM del campo type para incluir más tipos
            $table->enum('type', ['info', 'success', 'warning', 'error', 'status_change', 'test'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Revertir al ENUM original
            $table->enum('type', ['info', 'success', 'warning', 'error'])->change();
        });
    }
};