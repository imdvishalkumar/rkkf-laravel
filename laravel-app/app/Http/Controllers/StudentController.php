<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Branch;
use App\Models\Belt;
use App\Models\Fee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Student::with(['branch', 'belt'])
            ->where('active', 1);

        // Apply filters if provided
        if ($request->has('branch_id') && $request->branch_id != 0) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->has('belt_id') && $request->belt_id != 0) {
            $query->where('belt_id', $request->belt_id);
        }

        if ($request->has('startdate') && $request->has('enddate')) {
            $query->whereBetween('doj', [$request->startdate, $request->enddate]);
        }

        $students = $query->get();
        $branches = Branch::all();
        $belts = Belt::all();

        return view('students.index', compact('students', 'branches', 'belts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branches = Branch::all();
        $belts = Belt::all();
        return view('students.create', compact('branches', 'belts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'gender' => 'required|in:1,2',
            'email' => 'required|email|unique:students,email|unique:users,email',
            'belt' => 'required|exists:belt,belt_id',
            'dmno' => 'nullable|string',
            'dwno' => 'nullable|string',
            'mmno' => 'nullable|string',
            'mwno' => 'nullable|string',
            'smno' => 'required|string',
            'swno' => 'nullable|string',
            'dob' => 'required|date',
            'doj' => 'required|date',
            'address' => 'nullable|string',
            'branch_id' => 'required|exists:branch,branch_id',
            'pincode' => 'nullable|string',
            'fees' => 'required|numeric|min:0',
            'months' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Create student
            $student = Student::create([
                'firstname' => $validated['firstname'],
                'lastname' => $validated['lastname'],
                'gender' => $validated['gender'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['smno']),
                'belt_id' => $validated['belt'],
                'dadno' => $validated['dmno'] ?? null,
                'dadwp' => $validated['dwno'] ?? null,
                'momno' => $validated['mmno'] ?? null,
                'momwp' => $validated['mwno'] ?? null,
                'selfno' => $validated['smno'],
                'selfwp' => $validated['swno'] ?? null,
                'dob' => $validated['dob'],
                'doj' => $validated['doj'],
                'address' => $validated['address'] ?? null,
                'branch_id' => $validated['branch_id'],
                'pincode' => $validated['pincode'] ?? null,
                'active' => 1,
            ]);

            // Create fees for selected months
            $feePerMonth = $validated['fees'] / count($validated['months']);
            $currentYear = date('Y');
            $currentDate = date('Y-m-d');

            foreach ($validated['months'] as $month) {
                Fee::create([
                    'student_id' => $student->student_id,
                    'months' => $month,
                    'year' => $currentYear,
                    'date' => $currentDate,
                    'amount' => $feePerMonth,
                    'coupon_id' => 1, // Default coupon
                    'additional' => 0,
                    'disabled' => 0,
                    'mode' => 'cash',
                ]);
            }

            DB::commit();
            return redirect()->route('students.index')
                ->with('success', 'Student added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error adding student: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        $student->load(['branch', 'belt', 'fees']);
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        $branches = Branch::all();
        $belts = Belt::all();
        return view('students.edit', compact('student', 'branches', 'belts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'gender' => 'required|in:1,2',
            'email' => 'required|email|unique:students,email,' . $student->student_id . ',student_id|unique:users,email',
            'belt_id' => 'required|exists:belt,belt_id',
            'branch_id' => 'required|exists:branch,branch_id',
            'dob' => 'required|date',
            'doj' => 'required|date',
        ]);

        $student->update($validated);

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }

    /**
     * Deactivate a student.
     */
    public function deactivate(Student $student)
    {
        $student->update(['active' => 0]);
        return redirect()->route('students.index')
            ->with('success', 'Student deactivated successfully.');
    }

    /**
     * Reset student password.
     */
    public function resetPassword(Student $student)
    {
        $password = $student->selfno;
        $student->update([
            'password' => Hash::make($password)
        ]);

        return redirect()->route('students.index')
            ->with('success', "Student password reset successfully to: {$password}");
    }
}
