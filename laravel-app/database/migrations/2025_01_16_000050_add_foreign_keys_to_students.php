<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->foreign('belt_id')->references('belt_id')->on('belt');
            $table->foreign('branch_id')->references('branch_id')->on('branch');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['belt_id']);
            $table->dropForeign(['branch_id']);
        });
    }
};
