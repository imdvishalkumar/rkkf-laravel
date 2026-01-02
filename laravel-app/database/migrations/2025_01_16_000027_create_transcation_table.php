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
        Schema::create('transcation', function (Blueprint $table) {
            $table->id('transcation_id');
            $table->foreignId('student_id')->constrained('students', 'student_id');
            $table->string('order_id', 50);
            $table->integer('status');
            $table->string('type', 50);
            $table->integer('ref_id');
            $table->integer('amount');
            $table->date('date');
            $table->string('months', 20);
            $table->string('year', 5);
            $table->foreignId('coupon_id')->constrained('coupon', 'coupon_id');
            $table->timestamps();
            
            $table->index(['student_id', 'type']);
            $table->index('order_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transcation');
    }
};


