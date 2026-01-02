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
        Schema::create('guide', function (Blueprint $table) {
            $table->id('id');
            $table->string('name', 100);
            $table->string('link', 512);
            // Foreign key will be added in migration 2025_01_16_000038
            $table->unsignedBigInteger('created_by');
            $table->integer('is_deleted')->default(0);
            $table->timestamps();
            
            $table->index('is_deleted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guide');
    }
};

