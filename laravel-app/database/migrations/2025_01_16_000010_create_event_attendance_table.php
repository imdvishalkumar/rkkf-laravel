<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('event_attendance', function (Blueprint $table) {
            $table->id('event_attendance_id');
            $table->foreignId('event_id')->constrained('event', 'event_id');
            $table->foreignId('student_id')->constrained('students', 'student_id');
            $table->string('attend', 50);
            // Foreign key will be added in migration 2025_01_16_000038
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            
            $table->index(['event_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_attendance');
    }
};

