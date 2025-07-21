<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('events', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description')->nullable();
        $table->dateTime('start_time');
        $table->dateTime('end_time');
        $table->unsignedBigInteger('room_id')->nullable();
        $table->unsignedBigInteger('created_by');
        $table->string('type')->nullable(); // clase, reunión, etc.
        $table->string('status')->default('activo'); // activo/cancelado
        $table->timestamps();

        $table->foreign('room_id')->references('id')->on('rooms')->onDelete('set null');
        $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
