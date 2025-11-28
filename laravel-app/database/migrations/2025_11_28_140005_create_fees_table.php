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
            $table->integer('months'); // Month number (1-12)
            $table->integer('year');
            $table->date('date');
            $table->decimal('amount', 10, 2);
            $table->foreignId('coupon_id')->default(1)->constrained('coupon', 'coupon_id');
            $table->boolean('additional')->default(0);
            $table->boolean('disabled')->default(0);
            $table->string('mode')->default('cash'); // cash, online
            $table->text('remarks')->nullable();
            $table->timestamps();
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

