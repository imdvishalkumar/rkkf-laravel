<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration adds foreign key constraints to all tables that reference users
     * after the users table has been created.
     */
    public function up(): void
    {
        // Only proceed if users table exists
        if (!Schema::hasTable('users')) {
            return;
        }

        // Add foreign key to sessions table
        if (Schema::hasTable('sessions')) {
            Schema::table('sessions', function (Blueprint $table) {
                if (!$this->foreignKeyExists('sessions', 'sessions_user_id_foreign')) {
                    $table->foreign('user_id')
                        ->references('user_id')
                        ->on('users')
                        ->onDelete('set null');
                }
            });
        }

        // Add foreign key to attendance table
        if (Schema::hasTable('attendance')) {
            Schema::table('attendance', function (Blueprint $table) {
                if (!$this->foreignKeyExists('attendance', 'attendance_user_id_foreign')) {
                    $table->foreign('user_id')
                        ->references('user_id')
                        ->on('users')
                        ->onDelete('restrict');
                }
            });
        }

        // Add foreign key to event_attendance table
        if (Schema::hasTable('event_attendance')) {
            Schema::table('event_attendance', function (Blueprint $table) {
                if (!$this->foreignKeyExists('event_attendance', 'event_attendance_user_id_foreign')) {
                    $table->foreign('user_id')
                        ->references('user_id')
                        ->on('users')
                        ->onDelete('restrict');
                }
            });
        }

        // Add foreign key to exam_attendance table
        if (Schema::hasTable('exam_attendance')) {
            Schema::table('exam_attendance', function (Blueprint $table) {
                if (!$this->foreignKeyExists('exam_attendance', 'exam_attendance_user_id_foreign')) {
                    $table->foreign('user_id')
                        ->references('user_id')
                        ->on('users')
                        ->onDelete('restrict');
                }
            });
        }

        // Add foreign key to fastrack_attendance table
        if (Schema::hasTable('fastrack_attendance')) {
            Schema::table('fastrack_attendance', function (Blueprint $table) {
                if (!$this->foreignKeyExists('fastrack_attendance', 'fastrack_attendance_user_id_foreign')) {
                    $table->foreign('user_id')
                        ->references('user_id')
                        ->on('users')
                        ->onDelete('restrict');
                }
            });
        }

        // Add foreign key to ins_timetable table
        if (Schema::hasTable('ins_timetable')) {
            Schema::table('ins_timetable', function (Blueprint $table) {
                if (!$this->foreignKeyExists('ins_timetable', 'ins_timetable_user_id_foreign')) {
                    $table->foreign('user_id')
                        ->references('user_id')
                        ->on('users')
                        ->onDelete('restrict');
                }
            });
        }

        // Add foreign key to guide table
        if (Schema::hasTable('guide')) {
            Schema::table('guide', function (Blueprint $table) {
                if (!$this->foreignKeyExists('guide', 'guide_created_by_foreign')) {
                    $table->foreign('created_by')
                        ->references('user_id')
                        ->on('users')
                        ->onDelete('restrict');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'sessions',
            'attendance',
            'event_attendance',
            'exam_attendance',
            'fastrack_attendance',
            'ins_timetable',
            'guide',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    $foreignKeyName = $tableName === 'guide' 
                        ? 'guide_created_by_foreign' 
                        : $tableName . '_user_id_foreign';
                    
                    try {
                        $table->dropForeign([$tableName === 'guide' ? 'created_by' : 'user_id']);
                    } catch (\Exception $e) {
                        // Foreign key might not exist, ignore
                    }
                });
            }
        }
    }

    /**
     * Check if a foreign key exists
     */
    private function foreignKeyExists(string $table, string $foreignKey): bool
    {
        $connection = Schema::getConnection();
        $database = $connection->getDatabaseName();
        
        $result = $connection->select(
            "SELECT CONSTRAINT_NAME 
             FROM information_schema.KEY_COLUMN_USAGE 
             WHERE TABLE_SCHEMA = ? 
             AND TABLE_NAME = ? 
             AND CONSTRAINT_NAME = ?",
            [$database, $table, $foreignKey]
        );
        
        return count($result) > 0;
    }
};

