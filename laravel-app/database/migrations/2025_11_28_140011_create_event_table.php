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
        Schema::create('event', function (Blueprint $table) {
            $table->id('event_id');
            $table->string('name');
            $table->date('from_date');
            $table->date('to_date');
            $table->string('venue')->nullable();
            $table->string('type')->nullable();
            $table->text('description')->nullable();
            $table->decimal('fees', 10, 2)->default(0);
            $table->date('fees_due_date')->nullable();
            $table->decimal('penalty', 10, 2)->default(0);
            $table->date('penalty_due_date')->nullable();
            $table->boolean('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event');
    }
};

