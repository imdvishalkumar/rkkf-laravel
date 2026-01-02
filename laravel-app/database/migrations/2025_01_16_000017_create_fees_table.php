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
        Schema::create('fees', function (Blueprint $table) {
            $table->id('fee_id');
            $table->foreignId('student_id')->constrained('students', 'student_id');
            $table->integer('months');
            $table->integer('year');
            $table->date('date');
            $table->integer('amount');
            $table->foreignId('coupon_id')->default(1)->constrained('coupon', 'coupon_id');
            $table->boolean('additional')->default(0);
            $table->boolean('disabled')->default(0);
            $table->string('mode', 50);
            $table->string('remarks', 512)->nullable();
            $table->integer('up')->default(0);
            $table->integer('dump')->default(0);
            $table->string('new_remarks', 512)->nullable();
            $table->boolean('call_flag')->default(0)->comment('0 = Not Call, 1 = Called');
            $table->timestamps();
            
            $table->index(['student_id', 'year', 'months']);
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fees');
    }
};


