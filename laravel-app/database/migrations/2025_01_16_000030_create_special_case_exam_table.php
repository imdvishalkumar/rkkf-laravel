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
        Schema::create('special_case_exam', function (Blueprint $table) {
            $table->id('special_id');
            $table->foreignId('student_id')->constrained('students', 'student_id');
            $table->foreignId('exam_id')->constrained('exam', 'exam_id');
            $table->boolean('eligible')->default(1);
            $table->timestamps();
            
            $table->index(['student_id', 'exam_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('special_case_exam');
    }
};

