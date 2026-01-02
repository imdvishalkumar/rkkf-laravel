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
            $table->string('name', 20);
            $table->date('date');
            $table->integer('sessions_count');
            $table->integer('fees');
            $table->date('fess_due_date');
            $table->string('location', 100);
            $table->date('to_criteria')->nullable();
            $table->date('from_criteria')->nullable();
            $table->boolean('isPublished')->default(0);
            $table->timestamps();
            
            $table->index('date');
            $table->index('isPublished');
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


