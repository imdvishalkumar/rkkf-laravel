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
        Schema::create('fastrack', function (Blueprint $table) {
            $table->id('fastrack_id');
            $table->foreignId('student_id')->constrained('students', 'student_id');
            $table->foreignId('from_belt_id')->constrained('belt', 'belt_id');
            $table->foreignId('to_belt_id')->constrained('belt', 'belt_id');
            $table->date('from_date');
            $table->date('to_date');
            $table->integer('months_count');
            $table->integer('total_fees');
            $table->integer('total_hours');
            $table->timestamps();
            
            $table->index('student_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fastrack');
    }
};

