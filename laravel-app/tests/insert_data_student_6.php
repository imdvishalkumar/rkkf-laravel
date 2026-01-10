<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\User;

$logFile = __DIR__ . '/insert_log.txt';
file_put_contents($logFile, "Starting insertion...\n");

function logMsg($msg)
{
    global $logFile;
    file_put_contents($logFile, $msg, FILE_APPEND);
    echo $msg;
}

try {
    $studentId = 6;
    $branchId = 1;
    $userId = 1;

    logMsg("Checking existence of Student $studentId...\n");
    if (!Student::find($studentId)) {
        logMsg("ERROR: Student $studentId does not exist!\n");
        // Create dummy if needed? For now just exit
        exit(1);
    }
    logMsg("Student exists.\n");

    logMsg("Checking existence of User $userId...\n");
    if (!User::find($userId)) {
        logMsg("WARNING: User $userId does not exist. Creating dummy user...\n");
        $userId = DB::table('users')->insertGetId([
            'firstname' => 'Admin',
            'lastname' => 'User',
            'email' => 'admin_test@example.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'role' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        logMsg("Created dummy User $userId.\n");
    } else {
        logMsg("User exists.\n");
    }

    logMsg("Inserting test attendance for Student $studentId, Branch $branchId...\n");

    // Clear existing records for this month
    DB::table('attendance')
        ->where('student_id', $studentId)
        ->whereMonth('date', date('m'))
        ->whereYear('date', date('Y'))
        ->delete();

    $records = [
        ['date' => date('Y-m-01'), 'attend' => 'P'],
        ['date' => date('Y-m-02'), 'attend' => 'P'],
        ['date' => date('Y-m-03'), 'attend' => 'A'],
        ['date' => date('Y-m-04'), 'attend' => 'P'],
        ['date' => date('Y-m-05'), 'attend' => 'L'],
        ['date' => date('Y-m-06'), 'attend' => 'P'],
        ['date' => date('Y-m-09'), 'attend' => 'A'],
        ['date' => date('Y-m-10'), 'attend' => 'P'],
    ];

    foreach ($records as $record) {
        logMsg("Inserting {$record['attend']} for {$record['date']}... ");
        Attendance::create([
            'student_id' => $studentId,
            'branch_id' => $branchId,
            'date' => $record['date'],
            'attend' => $record['attend'],
            'user_id' => $userId,
            'is_additional' => 0
        ]);
        logMsg("OK\n");
    }

    logMsg("✅ Test data inserted successfully.\n");

} catch (\Exception $e) {
    logMsg("\nERROR: " . $e->getMessage() . "\n");
    logMsg($e->getTraceAsString() . "\n");
}
