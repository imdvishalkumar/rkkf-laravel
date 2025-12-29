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
        Schema::create('exam_attendance', function (Blueprint $table) {
            $table->id('exam_attendance_id');
            $table->foreignId('exam_id')->constrained('exam', 'exam_id');
            $table->foreignId('student_id')->constrained('students', 'student_id');
            $table->string('attend', 5);
            $table->foreignId('user_id')->constrained('users', 'user_id');
            $table->string('certificate_no', 50)->nullable();
            $table->timestamps();
            
            $table->index(['exam_id', 'student_id']);
            $table->index('certificate_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_attendance');
    }
};

