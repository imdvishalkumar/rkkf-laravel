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
        Schema::create('belt', function (Blueprint $table) {
            $table->id('belt_id');
            $table->string('name', 20);
            $table->integer('code');
            $table->integer('priority');
            $table->integer('exam_fees');
            $table->timestamps();
            
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('belt');
    }
};

