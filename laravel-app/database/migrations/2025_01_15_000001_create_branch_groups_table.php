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
        Schema::create('branch_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->json('branch_ids'); // Array of branch IDs
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Create pivot table for many-to-many relationship
        Schema::create('branch_group_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_group_id');
            $table->unsignedBigInteger('branch_id');
            $table->timestamps();

            $table->foreign('branch_group_id')
                  ->references('id')
                  ->on('branch_groups')
                  ->onDelete('cascade');

            $table->foreign('branch_id')
                  ->references('branch_id')
                  ->on('branch')
                  ->onDelete('cascade');

            $table->unique(['branch_group_id', 'branch_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_group_members');
        Schema::dropIfExists('branch_groups');
    }
};


