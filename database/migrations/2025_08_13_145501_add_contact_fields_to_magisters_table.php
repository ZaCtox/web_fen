<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('magisters', function (Blueprint $table) {
            $table->string('encargado')->nullable()->after('nombre');
            $table->string('telefono')->nullable()->after('encargado');
            $table->string('correo')->nullable()->after('telefono');
        });
    }

    public function down(): void
    {
        Schema::table('magisters', function (Blueprint $table) {
            $table->dropColumn(['encargado', 'telefono', 'correo']);
        });
    }
};
