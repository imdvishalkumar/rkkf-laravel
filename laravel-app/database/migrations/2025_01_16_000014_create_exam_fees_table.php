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
        Schema::create('exam_fees', function (Blueprint $table) {
            $table->id('exam_fees_id');
            $table->foreignId('exam_id')->constrained('exam', 'exam_id');
            $table->foreignId('student_id')->constrained('students', 'student_id');
            $table->date('date');
            $table->string('mode', 11);
            $table->string('rp_order_id', 256);
            $table->integer('status');
            $table->integer('amount');
            $table->foreignId('exam_belt_id')->nullable()->constrained('belt', 'belt_id');
            $table->integer('up')->default(0);
            $table->integer('dump')->default(0);
            $table->timestamps();
            
            $table->index(['exam_id', 'student_id']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_fees');
    }
};

