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
        Schema::create('event_fees', function (Blueprint $table) {
            $table->id('event_fees_id');
            $table->foreignId('event_id')->constrained('event', 'event_id');
            $table->foreignId('student_id')->constrained('students', 'student_id');
            $table->date('date');
            $table->string('mode', 20);
            $table->string('rp_order_id', 256)->nullable();
            $table->integer('status');
            $table->integer('amount');
            $table->timestamps();
            
            $table->index(['event_id', 'student_id']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_fees');
    }
};


