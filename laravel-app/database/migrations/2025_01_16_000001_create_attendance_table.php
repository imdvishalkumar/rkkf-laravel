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
            $table->string('attend', 1); // P, A, L
            $table->foreignId('branch_id')->constrained('branch', 'branch_id');
            $table->foreignId('user_id')->constrained('users', 'user_id');
            $table->boolean('is_additional')->default(0);
            $table->timestamps();
            
            $table->index(['student_id', 'date']);
            $table->index(['branch_id', 'date']);
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

