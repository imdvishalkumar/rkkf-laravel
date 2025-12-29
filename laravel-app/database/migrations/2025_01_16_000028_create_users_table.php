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
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('firstname', 50);
            $table->string('lastname', 50);
            $table->string('mobile', 15);
            $table->string('email', 100);
            $table->string('password', 16);
            $table->integer('role'); // 1 = Admin, 2 = Instructor
            $table->timestamps();
            
            $table->index('email');
            $table->index('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

