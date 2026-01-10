<?php

use App\Models\User;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Branch;
use App\Services\AttendanceService;
use App\Repositories\Contracts\AttendanceRepositoryInterface;
use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Repositories\Contracts\BranchRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

$logFile = __DIR__ . '/verify.log';
file_put_contents($logFile, "Starting Verification...\n");

function logMsg($msg)
{
    global $logFile;
    file_put_contents($logFile, $msg, FILE_APPEND);
    echo $msg;
}

// 1. Setup Dependencies
$branchId = 1;
if (!Branch::find($branchId)) {
    logMsg("Creating dummy branch...\n");
    $branchId = DB::table('branch')->insertGetId([
        'name' => 'Test Branch',
        'address' => 'Test Address',
        'city' => 'Test City',
        'state' => 'Test State',
        'pincode' => '123456',
        'active' => 1
    ]);
}

logMsg("Using Branch ID: $branchId\n");

// 2. Create Student & User
$email = 'test_student_' . time() . '@example.com';
logMsg("Creating Student with email: $email\n");

try {
    DB::beginTransaction();

    $studentId = DB::table('students')->insertGetId([
        'firstname' => 'Test',
        'lastname' => 'Student',
        'gender' => 1,
        'email' => $email,
        'password' => 'password', // Plain text for legacy support
        'belt_id' => 1,
        'dob' => '2000-01-01',
        'doj' => '2024-01-01',
        'address' => 'Test Address',
        'branch_id' => $branchId,
        'pincode' => '123456',
        'active' => 1,
        'profile_img' => 'default.png'
    ]);

    $userId = DB::table('users')->insertGetId([
        'firstname' => 'Test',
        'lastname' => 'Student',
        'email' => $email,
        'password' => Hash::make('password'),
        'role' => 4, // Assuming 4 is Student role
        'created_at' => now(),
        'updated_at' => now()
    ]);

    logMsg("Student ID: $studentId, User ID: $userId\n");

    // 3. Insert Attendance Records
    $startDate = '2024-01-01';
    $endDate = '2024-01-31';

    // 5 Present
    for ($i = 1; $i <= 5; $i++) {
        Attendance::create([
            'student_id' => $studentId,
            'branch_id' => $branchId,
            'date' => "2024-01-0" . $i,
            'attend' => 'P',
            'user_id' => $userId,
            'is_additional' => 0
        ]);
    }
    // 3 Absent
    for ($i = 6; $i <= 8; $i++) {
        Attendance::create([
            'student_id' => $studentId,
            'branch_id' => $branchId,
            'date' => "2024-01-0" . $i,
            'attend' => 'A',
            'user_id' => $userId,
            'is_additional' => 0
        ]);
    }
    // 2 Leave
    for ($i = 9; $i <= 10; $i++) {
        Attendance::create([
            'student_id' => $studentId,
            'branch_id' => $branchId,
            'date' => "2024-01-" . sprintf("%02d", $i),
            'attend' => 'L',
            'user_id' => $userId,
            'is_additional' => 0
        ]);
    }

    logMsg("Attendance records inserted.\n");

    // 4. Test Service
    $service = app(AttendanceService::class);
    $result = $service->getStudentAttendanceOverview($studentId, $startDate, $endDate);

    logMsg("\n--------------------------------\n");
    logMsg("API Response Overview:\n");
    logMsg(print_r($result['overview'], true));
    logMsg("--------------------------------\n");

    // Verification
    $overview = $result['overview'];
    $passed = true;
    if ($overview['present'] != 5) {
        logMsg("FAIL: Present count mismatch (Expected 5, Got {$overview['present']})\n");
        $passed = false;
    }
    if ($overview['absent'] != 3) {
        logMsg("FAIL: Absent count mismatch (Expected 3, Got {$overview['absent']})\n");
        $passed = false;
    }
    if ($overview['leave'] != 2) {
        logMsg("FAIL: Leave count mismatch (Expected 2, Got {$overview['leave']})\n");
        $passed = false;
    }

    // Total 10 days
    // Percentage: (5 / 10) * 100 = 50%
    if ($overview['percentage'] != 50.0) {
        logMsg("FAIL: Percentage mismatch (Expected 50.0, Got {$overview['percentage']})\n");
        $passed = false;
    }

    if ($passed) {
        logMsg("\n✅ VERIFICATION PASSED!\n");
    } else {
        logMsg("\n❌ VERIFICATION FAILED!\n");
    }

} catch (\Exception $e) {
    logMsg("Error: " . $e->getMessage() . "\n");
    logMsg($e->getTraceAsString());
} finally {
    DB::rollBack();
    logMsg("\nCleaned up (Rolled back transaction).\n");
}
