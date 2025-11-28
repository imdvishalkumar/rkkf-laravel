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
        Schema::create('exam', function (Blueprint $table) {
            $table->id('exam_id');
            $table->string('name');
            $table->date('date');
            $table->integer('sessions_count')->default(1);
            $table->decimal('fees', 10, 2)->default(0);
            $table->date('fess_due_date')->nullable(); // Fees due date
            $table->date('from_criteria')->nullable(); // From date for eligibility
            $table->date('to_criteria')->nullable(); // To date for eligibility
            $table->boolean('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam');
    }
};

