<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Student;

$studentId = 6;
$student = Student::find($studentId);

if (!$student) {
    echo "Student 6 not found. Creating dummy...\n";
    // ... logic to create dummy if needed, but we just inserted data for student 6 so it should exist
    exit(1);
}

echo "Testing Student Model Accessor:\n";
echo "ID: " . $student->student_id . "\n";
echo "DOJ: " . $student->doj->format('Y-m-d') . "\n";
echo "Calculated GR No: " . $student->gr_no . "\n";

// Expected GR No
$year = $student->doj->format('Y');
$expected = "STU-{$year}-{$studentId}";

if ($student->gr_no === $expected) {
    echo "✅ Accessor Logic: PASS\n";
} else {
    echo "❌ Accessor Logic: FAIL (Expected $expected, Got {$student->gr_no})\n";
}

echo "\nTesting JSON Serialization (appends):\n";
$json = $student->toArray();
if (array_key_exists('gr_no', $json)) {
    echo "✅ JSON 'gr_no' key exists: PASS\n";
    echo "Value in JSON: " . $json['gr_no'] . "\n";
} else {
    echo "❌ JSON 'gr_no' key exists: FAIL\n";
}
