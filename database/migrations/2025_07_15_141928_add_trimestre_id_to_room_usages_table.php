<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('room_usages', function (Blueprint $table) {
            $table->foreignId('trimestre_id')->nullable()->constrained('trimestres')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('room_usages', function (Blueprint $table) {
            $table->dropForeign(['trimestre_id']);
            $table->dropColumn('trimestre_id');
        });
    }
};
