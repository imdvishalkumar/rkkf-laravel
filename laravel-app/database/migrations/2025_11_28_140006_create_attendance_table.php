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
        Schema::create('attendance', function (Blueprint $table) {
            $table->id('attendance_id');
            $table->foreignId('student_id')->constrained('students', 'student_id');
            $table->date('date');
            $table->boolean('attend')->default(0); // 0 = absent, 1 = present
            $table->foreignId('branch_id')->constrained('branch', 'branch_id');
            $table->foreignId('user_id')->nullable()->constrained('users', 'user_id');
            $table->boolean('is_additional')->default(0);
            $table->timestamps();
            
            // Unique constraint to prevent duplicate attendance for same student, date, and branch
            $table->unique(['student_id', 'date', 'branch_id'], 'unique_attendance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};

