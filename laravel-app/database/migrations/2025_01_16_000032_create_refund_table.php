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
        Schema::create('refund', function (Blueprint $table) {
            $table->id('refund_id');
            $table->integer('invoice_id');
            $table->integer('amount');
            $table->string('cheque_no', 50);
            $table->string('description', 200);
            $table->timestamps();
            
            $table->index('invoice_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refund');
    }
};

