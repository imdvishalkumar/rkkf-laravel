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
            $table->string('firstname', 50);
            $table->string('lastname', 50);
            $table->integer('gender'); // 1 = Male, 2 = Female
            $table->string('email', 320);
            $table->string('password', 100);
            $table->foreignId('belt_id')->constrained('belt', 'belt_id');
            $table->string('dadno', 10)->nullable();
            $table->string('dadwp', 10)->nullable();
            $table->string('momno', 10)->nullable();
            $table->string('momwp', 10)->nullable();
            $table->string('selfno', 10)->nullable();
            $table->string('selfwp', 10)->nullable();
            $table->date('dob');
            $table->date('doj');
            $table->string('address', 500);
            $table->foreignId('branch_id')->constrained('branch', 'branch_id');
            $table->string('pincode', 6);
            $table->string('std', 50)->default('N/A');
            $table->boolean('active')->default(1);
            $table->string('reset_link_token', 40)->nullable();
            $table->dateTime('exp_date')->nullable();
            $table->string('profile_img', 500)->default('');
            $table->boolean('call_flag')->default(0)->comment('0 = Not Call, 1 = Called');
            $table->timestamps();
            
            $table->index('email');
            $table->index(['branch_id', 'active']);
            $table->index('active');
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

