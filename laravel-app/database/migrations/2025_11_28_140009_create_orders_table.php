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
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->foreignId('student_id')->constrained('students', 'student_id');
            $table->string('name_var'); // Product variation name
            $table->integer('qty');
            $table->decimal('p_price', 10, 2); // Product price
            $table->date('date');
            $table->boolean('status')->default(0); // 0 = pending, 1 = success
            $table->string('rp_order_id')->nullable(); // RazorPay order ID
            $table->string('counter')->nullable(); // Order counter/number
            $table->boolean('flag_delivered')->default(0);
            $table->boolean('viewed')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

