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
            $table->string('firstname');
            $table->string('lastname');
            $table->integer('gender'); // 1 = Male, 2 = Female
            $table->string('email');
            $table->date('dob');
            $table->date('doj');
            $table->string('dadno')->nullable();
            $table->string('dadwp')->nullable();
            $table->string('momno')->nullable();
            $table->string('momwp')->nullable();
            $table->string('selfno');
            $table->string('selfwp')->nullable();
            $table->text('address')->nullable();
            $table->foreignId('branch_id')->constrained('branch', 'branch_id');
            $table->string('pincode')->nullable();
            $table->string('order_id')->default('0');
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('payment_id')->nullable();
            $table->boolean('payment_status')->default(0);
            $table->boolean('inserted_status')->default(0); // 0 = not inserted to students, 1 = inserted
            $table->boolean('direct_entry')->default(0);
            $table->timestamps();
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

