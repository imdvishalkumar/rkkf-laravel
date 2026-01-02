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
            $table->integer('counter');
            $table->foreignId('student_id')->constrained('students', 'student_id');
            $table->foreignId('product_id')->constrained('products', 'product_id');
            $table->integer('qty');
            $table->float('p_price');
            $table->string('rp_order_id', 256);
            $table->integer('status');
            $table->date('date');
            $table->string('name_var', 100);
            $table->foreignId('variation_id')->nullable()->constrained('variation', 'id');
            $table->integer('flag')->comment('successApi or webhook');
            $table->boolean('flag_delivered')->default(0);
            $table->boolean('viewed')->default(0);
            $table->timestamps();
            
            $table->index(['student_id', 'status']);
            $table->index('rp_order_id');
            $table->index('date');
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


