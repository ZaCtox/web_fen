<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPublicIdToIncidentsTable extends Migration
{
    public function up()
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->string('public_id')->nullable()->after('imagen');
        });
    }

    public function down()
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->dropColumn('public_id');
        });
    }
}
