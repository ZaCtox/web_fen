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
        Schema::table('room_usages', function (Blueprint $table) {
            $table->dropColumn('year');
            $table->dropColumn('trimestre');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_usages', function (Blueprint $table) {
            //
        });
    }
};
