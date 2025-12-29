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
        Schema::create('notification', function (Blueprint $table) {
            $table->id('id');
            $table->string('title', 50);
            $table->string('details', 2560);
            $table->foreignId('student_id')->constrained('students', 'student_id');
            $table->integer('viewed');
            $table->string('type', 50);
            $table->integer('sent');
            $table->timestamp('timestamp')->nullable();
            $table->timestamps();
            
            $table->index(['student_id', 'viewed']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification');
    }
};

