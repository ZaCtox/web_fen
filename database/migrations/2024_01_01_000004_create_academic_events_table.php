<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->foreignId('room_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('magister_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('period_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('event_type', ['clase', 'examen', 'reunion', 'evento_especial', 'otro']);
            $table->string('color')->default('#3B82F6');
            $table->boolean('is_all_day')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_events');
    }
};
