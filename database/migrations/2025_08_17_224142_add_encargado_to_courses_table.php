<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('encargado')->nullable()->after('nombre'); // ajusta 'after' a tu orden
        });
    }

    public function down(): void {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('encargado');
        });
    }
};
