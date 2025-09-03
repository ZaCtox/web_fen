<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->integer('academic_year');
            $table->integer('trimester');
            $table->string('schedule'); // formato: "LUNES 08:00-10:00"
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['room_id', 'subject_id', 'academic_year', 'trimester']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_assignments');
    }
};
