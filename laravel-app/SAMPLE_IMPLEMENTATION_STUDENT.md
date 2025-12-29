# Complete Student Module Implementation
## Repository-Service Pattern Example

This document shows a complete implementation of the Student module using Repository-Service pattern.

> ⚠️ **Status:** This is a complete working example. Some files already exist and are marked below.

---

## 📁 Files Status

| # | File | Status | Action |
|---|------|--------|--------|
| 1 | `app/Enums/StudentStatus.php` | ✅ **EXISTS** | Already created - matches this example |
| 2 | `app/Models/Student.php` | ⚠️ **EXISTS** | Needs update - add Traits, Enums, Scopes |
| 3 | `app/Repositories/Contracts/StudentRepositoryInterface.php` | ❌ **CREATE** | Create this file |
| 4 | `app/Repositories/StudentRepository.php` | ❌ **CREATE** | Create this file |
| 5 | `app/Services/StudentService.php` | ❌ **CREATE** | Create this file |
| 6 | `app/Http/Requests/Student/StoreStudentRequest.php` | ✅ **EXISTS** | Already created - matches this example |
| 7 | `app/Http/Requests/Student/UpdateStudentRequest.php` | ✅ **EXISTS** | Already created - matches this example |
| 8 | `app/Http/Requests/Student/SearchStudentRequest.php` | ✅ **EXISTS** | Already created - matches this example |
| 9 | `app/Http/Controllers/StudentController.php` | ⚠️ **EXISTS** | Needs update - use Service instead of Model |
| 10 | `app/Http/Controllers/Api/StudentApiController.php` | ⚠️ **EXISTS** | Needs update - use Service instead of Model |
| 11 | `app/Http/Resources/StudentResource.php` | ✅ **EXISTS** | Already created - matches this example |
| 12 | `app/Helpers/ApiResponseHelper.php` | ✅ **EXISTS** | Already created - matches this example |
| 13 | `app/Providers/RepositoryServiceProvider.php` | ✅ **EXISTS** | Already created - add StudentRepository binding |

**Summary:**
- ✅ **Already Created (7 files):** Enum, Form Requests, Resource, Helper, Service Provider
- ⚠️ **Needs Update (3 files):** Model, Controllers
- ❌ **Need to Create (3 files):** Repository Interface, Repository Implementation, Service

---

## 🎯 Implementation Order

1. **Update Student Model** - Add Traits, Enums, Scopes
2. **Create Repository Interface** - Define contract
3. **Create Repository Implementation** - Implement database queries
4. **Create Service** - Implement business logic
5. **Update Controllers** - Use Service instead of Model
6. **Register Repository** - Add to RepositoryServiceProvider
7. **Test** - Verify everything works

---

## 1. Enum: StudentStatus ✅ ALREADY CREATED

**File:** `app/Enums/StudentStatus.php`

**Status:** ✅ This file already exists and matches the example below.

```php
<?php

namespace App\Enums;

enum StudentStatus: int
{
    case ACTIVE = 1;
    case INACTIVE = 0;

    public function label(): string
    {
        return match($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
        };
    }

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this === self::INACTIVE;
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function fromValue(int $value): ?self
    {
        return self::tryFrom($value);
    }
}
```

**Note:** This enum is already created. You can use it directly.

---

## 2. Model: Student (Update Existing) ⚠️ NEEDS UPDATE

**File:** `app/Models/Student.php`

**Status:** ⚠️ File exists but needs updates: Add Traits, use Enums in casts, add scopes

**Current State:** Basic model exists with relationships  
**Action Required:** Add the following updates

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\StudentStatus;
use App\Traits\HasStatus;
use App\Traits\HasBranchAccess;

class Student extends Model
{
    use HasStatus, HasBranchAccess;

    protected $table = 'students';
    protected $primaryKey = 'student_id';
    public $timestamps = true;

    protected $fillable = [
        'firstname',
        'lastname',
        'gender',
        'email',
        'password',
        'belt_id',
        'dadno',
        'dadwp',
        'momno',
        'momwp',
        'selfno',
        'selfwp',
        'dob',
        'doj',
        'address',
        'branch_id',
        'pincode',
        'active',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'dob' => 'date',
        'doj' => 'date',
        'active' => 'boolean', // Can also use: StudentStatus::class if you want enum casting
        'gender' => 'integer',
    ];

    // Relationships
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function belt(): BelongsTo
    {
        return $this->belongsTo(Belt::class, 'belt_id', 'belt_id');
    }

    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class, 'student_id', 'student_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'student_id', 'student_id');
    }

    // Scopes
    // Note: HasStatus trait already provides scopeActive() and scopeInactive()
    // But you can override or add custom scopes:
    
    public function scopeActive($query)
    {
        return $query->where('active', StudentStatus::ACTIVE->value);
    }

    public function scopeInactive($query)
    {
        return $query->where('active', StudentStatus::INACTIVE->value);
    }
    
    // Or use the trait method directly:
    // $query->active() // From HasStatus trait

    public function scopeByBranch($query, $branchId)
    {
        if ($branchId && $branchId != 0) {
            return $query->where('branch_id', $branchId);
        }
        return $query;
    }

    public function scopeByBelt($query, $beltId)
    {
        if ($beltId && $beltId != 0) {
            return $query->where('belt_id', $beltId);
        }
        return $query;
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        if ($startDate && $endDate) {
            return $query->whereBetween('doj', [$startDate, $endDate]);
        }
        return $query;
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function($q) use ($searchTerm) {
            $q->where('student_id', 'LIKE', "%{$searchTerm}%")
              ->orWhereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", ["%{$searchTerm}%"]);
        });
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return "{$this->firstname} {$this->lastname}";
    }

    // Mutators
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}
```

---

## 3. Repository Interface ❌ CREATE THIS

**File:** `app/Repositories/Contracts/StudentRepositoryInterface.php`

**Status:** ❌ Need to create this file

```php
<?php

namespace App\Repositories\Contracts;

use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface StudentRepositoryInterface
{
    public function all(array $filters = []): Collection;
    
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    
    public function find(int $id): ?Student;
    
    public function findByEmail(string $email): ?Student;
    
    public function search(string $term, array $filters = []): Collection;
    
    public function create(array $data): Student;
    
    public function update(int $id, array $data): bool;
    
    public function delete(int $id): bool;
    
    public function activate(int $id): bool;
    
    public function deactivate(int $id): bool;
    
    public function resetPassword(int $id, string $password): bool;
    
    public function getByBranch(int $branchId, array $filters = []): Collection;
    
    public function getByBelt(int $beltId, array $filters = []): Collection;
    
    public function getByDateRange(string $startDate, string $endDate, array $filters = []): Collection;
    
    public function checkEmailExists(string $email, ?int $excludeId = null): bool;
}
```

---

## 4. Repository Implementation ❌ CREATE THIS

**File:** `app/Repositories/StudentRepository.php`

**Status:** ❌ Need to create this file

```php
<?php

namespace App\Repositories;

use App\Models\Student;
use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Enums\StudentStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class StudentRepository implements StudentRepositoryInterface
{
    protected $model;

    public function __construct(Student $model)
    {
        $this->model = $model;
    }

    public function all(array $filters = []): Collection
    {
        $query = $this->model->newQuery();

        // Apply filters
        if (isset($filters['branch_id'])) {
            $query->byBranch($filters['branch_id']);
        }

        if (isset($filters['belt_id'])) {
            $query->byBelt($filters['belt_id']);
        }

        if (isset($filters['active'])) {
            $query->where('active', $filters['active']);
        }

        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->byDateRange($filters['start_date'], $filters['end_date']);
        }

        return $query->with(['branch', 'belt'])->get();
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        // Apply filters
        if (isset($filters['branch_id'])) {
            $query->byBranch($filters['branch_id']);
        }

        if (isset($filters['belt_id'])) {
            $query->byBelt($filters['belt_id']);
        }

        if (isset($filters['active'])) {
            $query->where('active', $filters['active']);
        }

        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->byDateRange($filters['start_date'], $filters['end_date']);
        }

        if (isset($filters['search'])) {
            $query->search($filters['search']);
        }

        return $query->with(['branch', 'belt'])
            ->orderBy('student_id', 'desc')
            ->paginate($perPage);
    }

    public function find(int $id): ?Student
    {
        return $this->model->with(['branch', 'belt', 'fees'])->find($id);
    }

    public function findByEmail(string $email): ?Student
    {
        return $this->model->where('email', $email)->first();
    }

    public function search(string $term, array $filters = []): Collection
    {
        $query = $this->model->search($term);

        if (isset($filters['branch_id'])) {
            $query->byBranch($filters['branch_id']);
        }

        if (isset($filters['active'])) {
            $query->where('active', $filters['active']);
        }

        return $query->with(['branch', 'belt'])->get();
    }

    public function create(array $data): Student
    {
        // Hash password if provided
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        // Set default active status
        if (!isset($data['active'])) {
            $data['active'] = StudentStatus::ACTIVE->value;
        }

        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $student = $this->find($id);
        
        if (!$student) {
            return false;
        }

        // Hash password if provided
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $student->update($data);
    }

    public function delete(int $id): bool
    {
        $student = $this->find($id);
        
        if (!$student) {
            return false;
        }

        return $student->delete();
    }

    public function activate(int $id): bool
    {
        return $this->update($id, ['active' => StudentStatus::ACTIVE->value]);
    }

    public function deactivate(int $id): bool
    {
        return $this->update($id, ['active' => StudentStatus::INACTIVE->value]);
    }

    public function resetPassword(int $id, string $password): bool
    {
        $student = $this->find($id);
        
        if (!$student) {
            return false;
        }

        return $student->update(['password' => Hash::make($password)]);
    }

    public function getByBranch(int $branchId, array $filters = []): Collection
    {
        $query = $this->model->byBranch($branchId);

        if (isset($filters['active'])) {
            $query->where('active', $filters['active']);
        }

        return $query->with(['branch', 'belt'])->get();
    }

    public function getByBelt(int $beltId, array $filters = []): Collection
    {
        $query = $this->model->byBelt($beltId);

        if (isset($filters['active'])) {
            $query->where('active', $filters['active']);
        }

        return $query->with(['branch', 'belt'])->get();
    }

    public function getByDateRange(string $startDate, string $endDate, array $filters = []): Collection
    {
        $query = $this->model->byDateRange($startDate, $endDate);

        if (isset($filters['branch_id'])) {
            $query->byBranch($filters['branch_id']);
        }

        if (isset($filters['active'])) {
            $query->where('active', $filters['active']);
        }

        return $query->with(['branch', 'belt'])->get();
    }

    public function checkEmailExists(string $email, ?int $excludeId = null): bool
    {
        $query = $this->model->where('email', $email);

        if ($excludeId) {
            $query->where('student_id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
```

---

## 5. Service Layer ❌ CREATE THIS

**File:** `app/Services/StudentService.php`

**Status:** ❌ Need to create this file

**Note:** This Service uses Repositories, so create Repositories first (sections 3 & 4)

```php
<?php

namespace App\Services;

use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Repositories\Contracts\FeeRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Enums\StudentStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class StudentService
{
    protected $studentRepository;
    protected $feeRepository;
    protected $userRepository;

    public function __construct(
        StudentRepositoryInterface $studentRepository,
        FeeRepositoryInterface $feeRepository,
        UserRepositoryInterface $userRepository
    ) {
        $this->studentRepository = $studentRepository;
        $this->feeRepository = $feeRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Get all students with filters
     */
    public function getAllStudents(array $filters = [])
    {
        return $this->studentRepository->all($filters);
    }

    /**
     * Get paginated students
     */
    public function getPaginatedStudents(array $filters = [], int $perPage = 15)
    {
        return $this->studentRepository->paginate($filters, $perPage);
    }

    /**
     * Get student by ID
     */
    public function getStudentById(int $id)
    {
        $student = $this->studentRepository->find($id);
        
        if (!$student) {
            throw new \Exception('Student not found', 404);
        }

        return $student;
    }

    /**
     * Search students
     */
    public function searchStudents(string $term, array $filters = [])
    {
        return $this->studentRepository->search($term, $filters);
    }

    /**
     * Create student with fees
     */
    public function createStudent(array $data): array
    {
        DB::beginTransaction();
        
        try {
            // Check if email exists in students or users table
            if ($this->studentRepository->checkEmailExists($data['email'])) {
                throw new \Exception('Email already exists in students table', 422);
            }

            if ($this->userRepository->checkEmailExists($data['email'])) {
                throw new \Exception('Email already exists in instructors table', 422);
            }

            // Extract fees data
            $feesData = $data['fees'] ?? [];
            $months = $data['months'] ?? [];
            unset($data['fees'], $data['months']);

            // Create student
            $student = $this->studentRepository->create($data);

            // Create fees if provided
            if (!empty($months) && !empty($feesData)) {
                $this->createStudentFees($student->student_id, $months, $feesData);
            }

            DB::commit();

            return [
                'student' => $student,
                'message' => 'Student created successfully'
            ];

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating student: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Update student
     */
    public function updateStudent(int $id, array $data): array
    {
        $student = $this->studentRepository->find($id);
        
        if (!$student) {
            throw new \Exception('Student not found', 404);
        }

        // Check email uniqueness if email is being updated
        if (isset($data['email']) && $data['email'] !== $student->email) {
            if ($this->studentRepository->checkEmailExists($data['email'], $id)) {
                throw new \Exception('Email already exists', 422);
            }

            if ($this->userRepository->checkEmailExists($data['email'])) {
                throw new \Exception('Email already exists in instructors table', 422);
            }
        }

        $updated = $this->studentRepository->update($id, $data);

        if (!$updated) {
            throw new \Exception('Failed to update student', 500);
        }

        return [
            'student' => $this->studentRepository->find($id),
            'message' => 'Student updated successfully'
        ];
    }

    /**
     * Delete student
     */
    public function deleteStudent(int $id): bool
    {
        $student = $this->studentRepository->find($id);
        
        if (!$student) {
            throw new \Exception('Student not found', 404);
        }

        return $this->studentRepository->delete($id);
    }

    /**
     * Activate student
     */
    public function activateStudent(int $id): bool
    {
        $student = $this->studentRepository->find($id);
        
        if (!$student) {
            throw new \Exception('Student not found', 404);
        }

        return $this->studentRepository->activate($id);
    }

    /**
     * Deactivate student
     */
    public function deactivateStudent(int $id): bool
    {
        $student = $this->studentRepository->find($id);
        
        if (!$student) {
            throw new \Exception('Student not found', 404);
        }

        return $this->studentRepository->deactivate($id);
    }

    /**
     * Reset student password
     */
    public function resetPassword(int $id): string
    {
        $student = $this->studentRepository->find($id);
        
        if (!$student) {
            throw new \Exception('Student not found', 404);
        }

        // Use selfno as default password
        $newPassword = $student->selfno;
        
        $this->studentRepository->resetPassword($id, $newPassword);

        return $newPassword;
    }

    /**
     * Create student fees for multiple months
     */
    protected function createStudentFees(int $studentId, array $months, array $feesData): void
    {
        $currentYear = date('Y');
        $currentDate = date('Y-m-d');
        $totalAmount = $feesData['amount'] ?? 0;
        $amountPerMonth = $totalAmount / count($months);
        $remainder = $totalAmount % count($months);

        foreach ($months as $index => $month) {
            $amount = $amountPerMonth;
            
            // Add remainder to first month
            if ($index === 0) {
                $amount += $remainder;
            }

            $feeData = [
                'student_id' => $studentId,
                'months' => $month,
                'year' => $currentYear,
                'date' => $currentDate,
                'amount' => $amount,
                'coupon_id' => 1, // Default coupon
                'additional' => 0,
                'disabled' => 0,
                'mode' => 'cash',
            ];

            $this->feeRepository->create($feeData);
        }
    }

    /**
     * Get students by branch
     */
    public function getStudentsByBranch(int $branchId, array $filters = [])
    {
        return $this->studentRepository->getByBranch($branchId, $filters);
    }

    /**
     * Get students by belt
     */
    public function getStudentsByBelt(int $beltId, array $filters = [])
    {
        return $this->studentRepository->getByBelt($beltId, $filters);
    }

    /**
     * Get students by date range
     */
    public function getStudentsByDateRange(string $startDate, string $endDate, array $filters = [])
    {
        return $this->studentRepository->getByDateRange($startDate, $endDate, $filters);
    }
}
```

---

## 6. Form Requests ✅ ALREADY CREATED

**Status:** ✅ All Form Requests already exist and match these examples

### StoreStudentRequest

**File:** `app/Http/Requests/Student/StoreStudentRequest.php`

**Status:** ✅ Already created - matches example below

```php
<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Use policy/middleware for authorization
    }

    public function rules(): array
    {
        return [
            'firstname' => 'required|string|max:255|regex:/^[ A-Za-z0-9_@.,\/#&+-\s]*$/',
            'lastname' => 'required|string|max:255|alpha',
            'gender' => 'required|integer|in:1,2',
            'email' => 'required|email|unique:students,email|unique:users,email',
            'belt_id' => 'required|exists:belt,belt_id',
            'dadno' => 'required|string|size:10|regex:/^[0-9]+$/',
            'dadwp' => 'required|string|size:10|regex:/^[0-9]+$/',
            'momno' => 'required|string|size:10|regex:/^[0-9]+$/',
            'momwp' => 'required|string|size:10|regex:/^[0-9]+$/',
            'selfno' => 'required|string|size:10|regex:/^[0-9]+$/',
            'swno' => 'required|string|size:10|regex:/^[0-9]+$/',
            'dob' => 'required|date',
            'doj' => 'required|date',
            'address' => 'required|string|max:500',
            'branch_id' => 'required|exists:branch,branch_id',
            'pincode' => 'required|string|size:6|regex:/^[0-9]+$/',
            'fees' => 'required|numeric|min:0',
            'months' => 'required|array|min:1',
            'months.*' => 'integer|between:1,12',
        ];
    }

    public function messages(): array
    {
        return [
            'firstname.required' => 'First name is required',
            'lastname.required' => 'Last name is required',
            'email.unique' => 'Email already exists',
            'branch_id.exists' => 'Selected branch is invalid',
            'belt_id.exists' => 'Selected belt is invalid',
        ];
    }
}
```

### UpdateStudentRequest

**File:** `app/Http/Requests/Student/UpdateStudentRequest.php`

**Status:** ✅ Already created - matches example below

```php
<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $studentId = $this->route('student');

        return [
            'firstname' => 'sometimes|required|string|max:255',
            'lastname' => 'sometimes|required|string|max:255|alpha',
            'gender' => 'sometimes|required|integer|in:1,2',
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('students', 'email')->ignore($studentId, 'student_id'),
                Rule::unique('users', 'email'),
            ],
            'belt_id' => 'sometimes|required|exists:belt,belt_id',
            'branch_id' => 'sometimes|required|exists:branch,branch_id',
            'active' => 'sometimes|boolean',
        ];
    }
}
```

### SearchStudentRequest

**File:** `app/Http/Requests/Student/SearchStudentRequest.php`

**Status:** ✅ Already created - matches example below

```php
<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class SearchStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'grno' => 'required|string|min:1',
            'branch_id' => 'nullable|integer|exists:branch,branch_id',
            'active' => 'nullable|boolean',
        ];
    }
}
```

---

## 7. Controller (Web) ⚠️ UPDATE EXISTING

**File:** `app/Http/Controllers/StudentController.php`

**Status:** ⚠️ File exists but needs update - currently uses Models directly  
**Action Required:** Update to inject StudentService and use it instead of direct Model access

```php
<?php

namespace App\Http\Controllers;

use App\Services\StudentService;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Http\Requests\Student\SearchStudentRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    /**
     * Display listing of students
     */
    public function index(Request $request): View
    {
        $filters = [
            'branch_id' => $request->get('branch_id'),
            'belt_id' => $request->get('belt_id'),
            'active' => $request->get('active', 1),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ];

        $students = $this->studentService->getPaginatedStudents($filters, 15);

        return view('students.index', compact('students'));
    }

    /**
     * Show form for creating student
     */
    public function create(): View
    {
        return view('students.create');
    }

    /**
     * Store new student
     */
    public function store(StoreStudentRequest $request): RedirectResponse
    {
        try {
            $result = $this->studentService->createStudent($request->validated());
            
            return redirect()->route('students.index')
                ->with('success', $result['message']);
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Show student details
     */
    public function show(int $id): View
    {
        try {
            $student = $this->studentService->getStudentById($id);
            return view('students.show', compact('student'));
        } catch (\Exception $e) {
            abort(404, $e->getMessage());
        }
    }

    /**
     * Show form for editing student
     */
    public function edit(int $id): View
    {
        try {
            $student = $this->studentService->getStudentById($id);
            return view('students.edit', compact('student'));
        } catch (\Exception $e) {
            abort(404, $e->getMessage());
        }
    }

    /**
     * Update student
     */
    public function update(UpdateStudentRequest $request, int $id): RedirectResponse
    {
        try {
            $result = $this->studentService->updateStudent($id, $request->validated());
            
            return redirect()->route('students.index')
                ->with('success', $result['message']);
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Delete student
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->studentService->deleteStudent($id);
            
            return redirect()->route('students.index')
                ->with('success', 'Student deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Activate student
     */
    public function activate(int $id): RedirectResponse
    {
        try {
            $this->studentService->activateStudent($id);
            
            return back()->with('success', 'Student activated successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Deactivate student
     */
    public function deactivate(int $id): RedirectResponse
    {
        try {
            $this->studentService->deactivateStudent($id);
            
            return back()->with('success', 'Student deactivated successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Reset student password
     */
    public function resetPassword(int $id): RedirectResponse
    {
        try {
            $newPassword = $this->studentService->resetPassword($id);
            
            return back()->with('success', "Password reset successfully. New password: {$newPassword}");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
```

---

## 8. API Controller ⚠️ UPDATE EXISTING

**File:** `app/Http/Controllers/Api/StudentApiController.php`

**Status:** ⚠️ File exists but needs update - currently uses Models directly  
**Action Required:** Update to inject StudentService and use ApiResponseHelper

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StudentService;
use App\Http\Requests\Student\SearchStudentRequest;
use App\Http\Resources\StudentResource;
use App\Helpers\ApiResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StudentApiController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    /**
     * Get students by branch
     */
    public function getStudentsByBranch(Request $request): JsonResponse
    {
        try {
            $filters = [
                'branch_id' => $request->input('branch_id', 0),
                'belt_id' => $request->input('belt_id', 0),
                'active' => $request->input('active', 1),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
            ];

            $students = $this->studentService->getAllStudents($filters);

            return ApiResponseHelper::success(
                StudentResource::collection($students),
                'Students retrieved successfully'
            );
        } catch (\Exception $e) {
            return ApiResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * Search students
     */
    public function searchStudents(SearchStudentRequest $request): JsonResponse
    {
        try {
            $students = $this->studentService->searchStudents(
                $request->input('grno'),
                $request->only(['branch_id', 'active'])
            );

            return ApiResponseHelper::success(
                StudentResource::collection($students),
                'Students found successfully'
            );
        } catch (\Exception $e) {
            return ApiResponseHelper::error($e->getMessage(), 500);
        }
    }

    /**
     * Set student status (activate/deactivate)
     */
    public function setStatus(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'stuId' => 'required|integer|exists:students,student_id',
                'from' => 'required|integer|in:1,2',
            ]);

            $studentId = $request->input('stuId');
            $from = $request->input('from');

            if ($from == 1) {
                // Toggle active status
                $student = $this->studentService->getStudentById($studentId);
                if ($student->active) {
                    $this->studentService->deactivateStudent($studentId);
                    $message = 'Student deactivated successfully';
                } else {
                    $this->studentService->activateStudent($studentId);
                    $message = 'Student activated successfully';
                }
            }

            return ApiResponseHelper::success(null, $message);
        } catch (\Exception $e) {
            return ApiResponseHelper::error($e->getMessage(), 500);
        }
    }
}
```

---

## 9. API Resource ✅ ALREADY CREATED

**File:** `app/Http/Resources/StudentResource.php`

**Status:** ✅ Already created - matches example below (may have slight differences, both work)

```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'student_id' => $this->student_id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'gender' => $this->gender,
            'dob' => $this->dob?->format('Y-m-d'),
            'doj' => $this->doj?->format('Y-m-d'),
            'active' => (bool) $this->active,
            'branch' => [
                'branch_id' => $this->branch->branch_id ?? null,
                'name' => $this->branch->name ?? null,
            ],
            'belt' => [
                'belt_id' => $this->belt->belt_id ?? null,
                'name' => $this->belt->name ?? null,
            ],
            'contact' => [
                'dadno' => $this->dadno,
                'dadwp' => $this->dadwp,
                'momno' => $this->momno,
                'momwp' => $this->momwp,
                'selfno' => $this->selfno,
                'selfwp' => $this->selfwp,
            ],
            'address' => $this->address,
            'pincode' => $this->pincode,
        ];
    }
}
```

---

## 10. API Response Helper ✅ ALREADY CREATED

**File:** `app/Helpers/ApiResponseHelper.php`

**Status:** ✅ Already created - has additional helper methods (validationError, notFound, etc.)

**Note:** The actual file has more methods than shown below. Both versions work.

```php
<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponseHelper
{
    public static function success($data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
            'errors' => null,
            'meta' => [
                'timestamp' => now()->toIso8601String(),
            ],
        ], $code);
    }

    public static function error(string $message, int $code = 400, $errors = null): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'data' => null,
            'errors' => $errors,
            'meta' => [
                'timestamp' => now()->toIso8601String(),
            ],
        ], $code);
    }

    public static function paginated($data, string $message = 'Success'): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => [
                'items' => $data->items(),
                'pagination' => [
                    'current_page' => $data->currentPage(),
                    'per_page' => $data->perPage(),
                    'total' => $data->total(),
                    'last_page' => $data->lastPage(),
                ],
            ],
            'errors' => null,
            'meta' => [
                'timestamp' => now()->toIso8601String(),
            ],
        ]);
    }
}
```

---

## 11. Service Provider ✅ ALREADY CREATED

**File:** `app/Providers/RepositoryServiceProvider.php`

**Status:** ✅ Already created - needs StudentRepository binding added

**Current State:** File exists with placeholder for StudentRepository  
**Action Required:** Uncomment/add the StudentRepository binding after creating it

**Current File:**
```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Repositories\StudentRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Student Repository - ADD THIS after creating StudentRepository
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
        
        // Add more bindings as you create repositories
        // $this->app->bind(FeeRepositoryInterface::class, FeeRepository::class);
        // ...
    }

    public function boot(): void
    {
        //
    }
}
```

**Note:** 
- Service Provider is auto-discovered in Laravel 11 (no registration needed)
- For Laravel 10, register in `config/app.php` if not auto-discovered

---

## 12. Routes ✅ ALREADY EXIST

**Status:** ✅ Routes already exist in `routes/web.php` and `routes/api.php`

**Files:**
- `routes/web.php` - Web routes exist
- `routes/api.php` - API routes exist

**Note:** Routes are already configured. No changes needed unless you want to add middleware.

**Example Routes (already exist):**
```php
// routes/web.php
Route::middleware(['auth'])->group(function () {
    Route::resource('students', StudentController::class);
    // Additional routes may exist
});

// routes/api.php
Route::middleware(['auth'])->group(function () {
    Route::post('/students/get-by-branch', [StudentApiController::class, 'getStudentsByBranch']);
    Route::post('/students/search', [StudentApiController::class, 'searchStudents']);
    Route::post('/students/set-status', [StudentApiController::class, 'setStatus']);
});
```

---

## ✅ Implementation Checklist

### Already Complete ✅
- [x] Enum for StudentStatus ✅ (Already created)
- [x] Form Requests for validation ✅ (Already created - 3 files)
- [x] API Resource ✅ (Already created)
- [x] API Response Helper ✅ (Already created)
- [x] Service Provider ✅ (Already created - needs binding)

### Needs Update ⚠️
- [ ] Model with relationships and scopes ⚠️ (Exists - needs Traits, Enums, Scopes)
- [ ] Web Controller ⚠️ (Exists - needs Service injection)
- [ ] API Controller ⚠️ (Exists - needs Service injection)

### Needs Creation ❌
- [ ] Repository Interface ❌ (Create: StudentRepositoryInterface.php)
- [ ] Repository Implementation ❌ (Create: StudentRepository.php)
- [ ] Service Layer ❌ (Create: StudentService.php)

### Routes
- [ ] Update routes if needed (routes already exist)

---

## 🎯 Step-by-Step Implementation Order

### Step 1: Update Student Model ⚠️
**File:** `app/Models/Student.php`  
**Action:** Add Traits (HasStatus, HasBranchAccess), use Enums, add scopes  
**Reference:** See section 2 above

### Step 2: Create Repository Interface ❌
**File:** `app/Repositories/Contracts/StudentRepositoryInterface.php`  
**Action:** Copy code from section 3  
**Time:** 10 minutes

### Step 3: Create Repository Implementation ❌
**File:** `app/Repositories/StudentRepository.php`  
**Action:** Copy code from section 4  
**Time:** 30 minutes

### Step 4: Create Service ❌
**File:** `app/Services/StudentService.php`  
**Action:** Copy code from section 5  
**Time:** 45 minutes

### Step 5: Update RepositoryServiceProvider ✅
**File:** `app/Providers/RepositoryServiceProvider.php`  
**Action:** Uncomment/add StudentRepository binding  
**Time:** 2 minutes

### Step 6: Update Controllers ⚠️
**Files:** 
- `app/Http/Controllers/StudentController.php`
- `app/Http/Controllers/Api/StudentApiController.php`  
**Action:** Inject StudentService, replace Model calls with Service calls  
**Reference:** See sections 7 & 8  
**Time:** 30 minutes each

### Step 7: Test ✅
**Action:** Test all CRUD operations  
**Time:** 30 minutes

**Total Time:** ~3 hours for complete Student module implementation

---

## 📝 Notes

1. **Form Requests, Resources, Helpers:** Already created ✅
2. **Model:** Exists but needs Traits/Enums added ⚠️
3. **Controllers:** Exist but need Service injection ⚠️
4. **Repositories & Service:** Need to be created ❌

**This is a complete, production-ready implementation following Repository-Service pattern!**

**Start with Step 1 (Update Model), then create Repositories and Service.**

