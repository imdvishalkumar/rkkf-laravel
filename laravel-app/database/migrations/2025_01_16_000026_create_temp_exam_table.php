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
        Schema::create('temp_exam', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('student_id')->constrained('students', 'student_id');
            $table->foreignId('belt_id')->constrained('belt', 'belt_id');
            $table->date('date');
            $table->string('certificate_no', 256);
            $table->timestamp('inserted_at')->useCurrent();
            $table->timestamps();
            
            $table->index('student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_exam');
    }
};

