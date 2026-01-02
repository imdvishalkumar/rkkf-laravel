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
        Schema::create('leave_table', function (Blueprint $table) {
            $table->id('leave_id');
            $table->foreignId('student_id')->constrained('students', 'student_id');
            $table->date('from_date');
            $table->date('to_date');
            $table->string('reason', 500);
            $table->timestamps();
            
            $table->index('student_id');
            $table->index(['from_date', 'to_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_table');
    }
};


