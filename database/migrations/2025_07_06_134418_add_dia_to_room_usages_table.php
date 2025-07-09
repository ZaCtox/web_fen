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
        Schema::table('room_usages', function (Blueprint $table) {
            $table->enum('dia', ['Viernes', 'Sábado'])->nullable()->after('subject');
        });
    }

    public function down()
    {
        Schema::table('room_usages', function (Blueprint $table) {
            $table->dropColumn('dia');
        });
    }


};
