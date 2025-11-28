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
        // Notification table
        Schema::create('notification', function (Blueprint $table) {
            $table->id('notification_id');
            $table->string('title');
            $table->text('details');
            $table->foreignId('student_id')->nullable()->constrained('students', 'student_id');
            $table->boolean('viewed')->default(0);
            $table->string('type')->nullable(); // exam, event, custom
            $table->boolean('sent')->default(0);
            $table->timestamp('timestamp');
            $table->timestamps();
        });

        // Exam fees table
        Schema::create('exam_fees', function (Blueprint $table) {
            $table->id('exam_fees_id');
            $table->foreignId('exam_id')->constrained('exam', 'exam_id');
            $table->foreignId('student_id')->constrained('students', 'student_id');
            $table->date('date');
            $table->string('mode')->default('manual'); // manual, online
            $table->string('rp_order_id')->nullable();
            $table->boolean('status')->default(0);
            $table->decimal('amount', 10, 2);
            $table->foreignId('exam_belt_id')->nullable()->constrained('belt', 'belt_id');
            $table->timestamps();
        });

        // Exam attendance table
        Schema::create('exam_attendance', function (Blueprint $table) {
            $table->id('exam_attendance_id');
            $table->foreignId('exam_id')->constrained('exam', 'exam_id');
            $table->foreignId('student_id')->constrained('students', 'student_id');
            $table->boolean('attend')->default(0);
            $table->foreignId('user_id')->nullable()->constrained('users', 'user_id');
            $table->string('certificate_no')->nullable();
            $table->timestamps();
        });

        // Special case exam table
        Schema::create('special_case_exam', function (Blueprint $table) {
            $table->id('special_case_exam_id');
            $table->foreignId('student_id')->constrained('students', 'student_id');
            $table->foreignId('exam_id')->constrained('exam', 'exam_id');
            $table->timestamps();
        });

        // Fastrack table
        Schema::create('fastrack', function (Blueprint $table) {
            $table->id('fastrack_id');
            $table->foreignId('student_id')->constrained('students', 'student_id');
            $table->foreignId('from_belt_id')->constrained('belt', 'belt_id');
            $table->foreignId('to_belt_id')->constrained('belt', 'belt_id');
            $table->date('from_date');
            $table->date('to_date');
            $table->integer('months_count');
            $table->decimal('total_fees', 10, 2);
            $table->integer('total_hours');
            $table->timestamps();
        });

        // Refund table
        Schema::create('refund', function (Blueprint $table) {
            $table->id('refund_id');
            $table->string('invoice_id');
            $table->decimal('amount', 10, 2);
            $table->string('cheque_no')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Team table
        Schema::create('team', function (Blueprint $table) {
            $table->id('team_id');
            $table->string('name');
            $table->string('post');
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // Instructor timetable table
        Schema::create('ins_timetable', function (Blueprint $table) {
            $table->id('id');
            $table->date('date');
            $table->foreignId('branch_id')->constrained('branch', 'branch_id');
            $table->foreignId('user_id')->constrained('users', 'user_id');
            $table->timestamps();
        });

        // Post table (for news feed)
        Schema::create('post', function (Blueprint $table) {
            $table->id('id');
            $table->string('title');
            $table->text('description');
            $table->timestamp('created');
            $table->boolean('is_deleted')->default(0);
            $table->timestamps();
        });

        // Media table (for post images/videos)
        Schema::create('media', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('post_id')->constrained('post', 'id');
            $table->string('path');
            $table->string('type'); // image, video
            $table->timestamps();
        });

        // Guide table
        Schema::create('guide', function (Blueprint $table) {
            $table->id('id');
            $table->string('name');
            $table->string('link');
            $table->foreignId('created_by')->nullable()->constrained('users', 'user_id');
            $table->boolean('is_deleted')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guide');
        Schema::dropIfExists('media');
        Schema::dropIfExists('post');
        Schema::dropIfExists('ins_timetable');
        Schema::dropIfExists('team');
        Schema::dropIfExists('refund');
        Schema::dropIfExists('fastrack');
        Schema::dropIfExists('special_case_exam');
        Schema::dropIfExists('exam_attendance');
        Schema::dropIfExists('exam_fees');
        Schema::dropIfExists('notification');
    }
};

