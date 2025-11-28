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
        Schema::create('students', function (Blueprint $table) {
            $table->id('student_id');
            $table->string('firstname');
            $table->string('lastname');
            $table->integer('gender'); // 1 = Male, 2 = Female
            $table->string('email')->unique();
            $table->string('password');
            $table->foreignId('belt_id')->constrained('belt', 'belt_id');
            $table->string('dadno')->nullable(); // Dad mobile number
            $table->string('dadwp')->nullable(); // Dad WhatsApp
            $table->string('momno')->nullable(); // Mom mobile number
            $table->string('momwp')->nullable(); // Mom WhatsApp
            $table->string('selfno'); // Self mobile number
            $table->string('selfwp')->nullable(); // Self WhatsApp
            $table->date('dob'); // Date of birth
            $table->date('doj'); // Date of joining
            $table->text('address')->nullable();
            $table->foreignId('branch_id')->constrained('branch', 'branch_id');
            $table->string('pincode')->nullable();
            $table->boolean('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};

