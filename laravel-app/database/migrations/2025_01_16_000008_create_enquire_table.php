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
        Schema::create('enquire', function (Blueprint $table) {
            $table->id('enquire_id');
            $table->string('firstname', 50);
            $table->string('lastname', 50);
            $table->integer('gender'); // 1 = Male, 2 = Female
            $table->string('email', 320);
            $table->date('dob');
            $table->date('doj');
            $table->string('dadno', 10)->nullable();
            $table->string('dadwp', 10)->nullable();
            $table->string('momno', 10)->nullable();
            $table->string('momwp', 10)->nullable();
            $table->string('selfno', 10)->nullable();
            $table->string('selfwp', 10)->nullable();
            $table->string('address', 500);
            $table->foreignId('branch_id')->constrained('branch', 'branch_id');
            $table->string('pincode', 6);
            $table->string('order_id', 50)->nullable();
            $table->integer('amount')->default(0);
            $table->string('payment_id', 100)->nullable();
            $table->integer('payment_status')->default(0);
            $table->integer('inserted_status')->default(0);
            $table->boolean('direct_entry')->default(0);
            $table->timestamps();
            
            $table->index('email');
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enquire');
    }
};


