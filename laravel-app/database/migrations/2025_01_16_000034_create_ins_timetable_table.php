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
        Schema::create('ins_timetable', function (Blueprint $table) {
            $table->id('id');
            $table->date('date');
            $table->foreignId('branch_id')->constrained('branch', 'branch_id');
            $table->foreignId('user_id')->constrained('users', 'user_id');
            $table->timestamps();
            
            $table->index(['branch_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ins_timetable');
    }
};

