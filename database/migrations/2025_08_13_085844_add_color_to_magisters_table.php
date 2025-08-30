<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('magisters', function (Blueprint $table) {
            $table->string('color')->nullable()->after('nombre'); // puedes usar default si quieres
        });
    }

    public function down()
    {
        Schema::table('magisters', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }

};
