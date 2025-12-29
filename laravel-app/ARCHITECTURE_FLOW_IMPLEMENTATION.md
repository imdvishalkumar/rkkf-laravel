# Architecture Flow Implementation Guide
## Step-by-Step Implementation Following Data Flow

This guide shows you exactly how to implement each layer following the architecture flow.

---

## 🔄 Data Flow Overview

```
Request → Middleware → Controller → Form Request → Service → Repository → Model → Database
```

---

## Step 1: Middleware Layer ✅

**Purpose:** Authentication, Authorization, Branch Access

### ✅ Already Created
- `RoleMiddleware.php` - Role-based access
- `BranchAccessMiddleware.php` - Branch access control
- `ApiAuthMiddleware.php` - API authentication

### Usage in Routes

```php
// routes/web.php
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('students', StudentController::class);
});

Route::middleware(['auth', 'branch.access'])->group(function () {
    Route::get('/branch/{branch_id}/students', [StudentController::class, 'index']);
});

// routes/api.php
Route::middleware(['auth', 'api.auth'])->group(function () {
    Route::post('/students/get-by-branch', [StudentApiController::class, 'getStudentsByBranch']);
});
```

**Status:** ✅ Complete - All middleware created and ready

---

## Step 2: Controller Layer ✅

**Purpose:** Route handling, HTTP concerns only

### What Controllers Should Do:
- ✅ Receive HTTP requests
- ✅ Call Form Requests for validation
- ✅ Call Services for business logic
- ✅ Return HTTP responses
- ❌ NO business logic
- ❌ NO database queries
- ❌ NO validation rules

### Example: StudentController

```php
<?php

namespace App\Http\Controllers;

use App\Services\StudentService;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    /**
     * Display listing - Just calls service, returns view
     */
    public function index(Request $request)
    {
        $filters = $request->only(['branch_id', 'belt_id', 'active', 'start_date', 'end_date']);
        $students = $this->studentService->getPaginatedStudents($filters);
        
        return view('students.index', compact('students'));
    }

    /**
     * Store - Uses Form Request, calls Service
     */
    public function store(StoreStudentRequest $request)
    {
        try {
            $result = $this->studentService->createStudent($request->validated());
            return redirect()->route('students.index')
                ->with('success', $result['message']);
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Update - Uses Form Request, calls Service
     */
    public function update(UpdateStudentRequest $request, int $id)
    {
        try {
            $result = $this->studentService->updateStudent($id, $request->validated());
            return redirect()->route('students.index')
                ->with('success', $result['message']);
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
```

**Key Points:**
- ✅ Controller is thin - just HTTP handling
- ✅ All validation in Form Request
- ✅ All business logic in Service
- ✅ All database access through Service

**Status:** ✅ Controllers exist, need updates to use Services

---

## Step 3: Form Request Layer ✅

**Purpose:** Input validation, Authorization checks

### What Form Requests Should Do:
- ✅ Validate input data
- ✅ Check authorization
- ✅ Custom validation rules
- ❌ NO business logic

### Example: StoreStudentRequest

```php
<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Use policy or middleware for authorization
        return true;
    }

    public function rules(): array
    {
        return [
            'firstname' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'branch_id' => 'required|exists:branch,branch_id',
            // ... more rules
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Email already exists',
            'branch_id.exists' => 'Invalid branch selected',
        ];
    }
}
```

**Status:** ✅ Complete - All Form Requests created with validation rules

---

## Step 4: Service Layer ❌

**Purpose:** Business logic, calculations, orchestration

### What Services Should Do:
- ✅ Business logic
- ✅ Calculations
- ✅ Data transformation
- ✅ Multiple repository calls
- ✅ Transaction management
- ❌ NO direct database queries
- ❌ NO HTTP concerns

### Example: StudentService

```php
<?php

namespace App\Services;

use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Repositories\Contracts\FeeRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
     * Business Logic: Create student with fees
     * - Validates email uniqueness
     * - Creates student
     * - Creates fees for multiple months
     * - Handles transactions
     */
    public function createStudent(array $data): array
    {
        DB::beginTransaction();
        
        try {
            // Business rule: Check email in both students and users
            if ($this->studentRepository->checkEmailExists($data['email'])) {
                throw new \Exception('Email already exists in students', 422);
            }

            if ($this->userRepository->checkEmailExists($data['email'])) {
                throw new \Exception('Email already exists in instructors', 422);
            }

            // Extract fees data
            $feesData = $data['fees'] ?? [];
            $months = $data['months'] ?? [];
            unset($data['fees'], $data['months']);

            // Create student through repository
            $student = $this->studentRepository->create($data);

            // Business logic: Create fees for multiple months
            if (!empty($months) && !empty($feesData)) {
                $this->createStudentFees($student->student_id, $months, $feesData);
            }

            DB::commit();

            return [
                'student' => $student,
                'message' => 'Student created successfully'
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating student: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Business Logic: Calculate and create fees
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
            
            // Business rule: Add remainder to first month
            if ($index === 0) {
                $amount += $remainder;
            }

            $feeData = [
                'student_id' => $studentId,
                'months' => $month,
                'year' => $currentYear,
                'date' => $currentDate,
                'amount' => $amount,
                'coupon_id' => 1,
                'mode' => 'cash',
            ];

            $this->feeRepository->create($feeData);
        }
    }
}
```

**Key Points:**
- ✅ All business logic here
- ✅ Uses repositories, not direct DB access
- ✅ Handles transactions
- ✅ Can call multiple repositories
- ✅ Performs calculations

**Status:** ❌ Need to create (11 Service files)

---

## Step 5: Repository Layer ❌

**Purpose:** Database queries, data access

### What Repositories Should Do:
- ✅ Database queries
- ✅ Query building
- ✅ Data filtering
- ✅ Pagination
- ❌ NO business logic
- ❌ NO HTTP concerns

### Example: StudentRepository

```php
<?php

namespace App\Repositories;

use App\Models\Student;
use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Enums\StudentStatus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class StudentRepository implements StudentRepositoryInterface
{
    protected $model;

    public function __construct(Student $model)
    {
        $this->model = $model;
    }

    /**
     * Database Query: Get all students with filters
     */
    public function all(array $filters = []): Collection
    {
        $query = $this->model->newQuery();

        // Apply filters
        if (isset($filters['branch_id'])) {
            $query->byBranch($filters['branch_id']);
        }

        if (isset($filters['active'])) {
            $query->where('active', $filters['active']);
        }

        return $query->with(['branch', 'belt'])->get();
    }

    /**
     * Database Query: Paginated results
     */
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        // Apply filters
        if (isset($filters['branch_id'])) {
            $query->byBranch($filters['branch_id']);
        }

        if (isset($filters['search'])) {
            $query->search($filters['search']);
        }

        return $query->with(['branch', 'belt'])
            ->orderBy('student_id', 'desc')
            ->paginate($perPage);
    }

    /**
     * Database Query: Create student
     */
    public function create(array $data): Student
    {
        // Hash password if provided
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->model->create($data);
    }

    /**
     * Database Query: Find by ID
     */
    public function find(int $id): ?Student
    {
        return $this->model->with(['branch', 'belt', 'fees'])->find($id);
    }
}
```

**Key Points:**
- ✅ Only database queries
- ✅ Uses model scopes
- ✅ Eager loading relationships
- ✅ No business logic
- ✅ Reusable queries

**Status:** ❌ Need to create (20 Repository files - 10 Interfaces + 10 Implementations)

---

## Step 6: Model Layer ✅

**Purpose:** Eloquent ORM, relationships, scopes

### What Models Should Do:
- ✅ Define relationships
- ✅ Define scopes
- ✅ Accessors/Mutators
- ✅ Events
- ❌ NO business logic
- ❌ NO complex queries (use Repository)

### Example: Student Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasStatus;
use App\Traits\HasBranchAccess;
use App\Enums\StudentStatus;

class Student extends Model
{
    use HasStatus, HasBranchAccess;

    protected $table = 'students';
    protected $primaryKey = 'student_id';

    protected $fillable = [
        'firstname', 'lastname', 'email', 'branch_id', 'belt_id', 'active'
    ];

    // Relationships
    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function fees()
    {
        return $this->hasMany(Fee::class, 'student_id', 'student_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('active', StudentStatus::ACTIVE->value);
    }

    public function scopeByBranch($query, $branchId)
    {
        if ($branchId && $branchId != 0) {
            return $query->where('branch_id', $branchId);
        }
        return $query;
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return "{$this->firstname} {$this->lastname}";
    }
}
```

**Key Points:**
- ✅ Relationships defined
- ✅ Scopes for reusable queries
- ✅ Accessors for computed attributes
- ✅ Uses Enums and Traits

**Status:** ✅ Most models exist, some need updates

---

## Step 7: Database ✅

**Purpose:** Data storage

**Status:** ✅ Database exists, migrations created

---

## 📋 Implementation Checklist

### Layer 1: Middleware ✅
- [x] RoleMiddleware created
- [x] BranchAccessMiddleware created
- [x] ApiAuthMiddleware created
- [x] Registered in bootstrap/app.php

### Layer 2: Controllers ✅
- [x] All controllers exist
- [ ] Update to use Services (not direct Model access)
- [ ] Remove business logic from controllers
- [ ] Remove database queries from controllers

### Layer 3: Form Requests ✅
- [x] All Form Requests created
- [x] Validation rules defined
- [x] Authorization checks in place

### Layer 4: Services ❌
- [ ] Create StudentService
- [ ] Create FeeService
- [ ] Create AttendanceService
- [ ] Create BranchService
- [ ] Create ProductService
- [ ] Create OrderService
- [ ] Create ExamService
- [ ] Create EventService
- [ ] Create UserService
- [ ] Create CouponService
- [ ] Create PaymentService

### Layer 5: Repositories ❌
- [ ] Create Repository Interfaces (10 files)
- [ ] Create Repository Implementations (10 files)
- [ ] Register in RepositoryServiceProvider

### Layer 6: Models ✅
- [x] Most models exist
- [ ] Update to use Enums
- [ ] Add Traits (HasStatus, HasBranchAccess)
- [ ] Add scopes
- [ ] Define relationships

### Layer 7: Database ✅
- [x] Database exists
- [x] Migrations created

---

## 🎯 Implementation Order

1. **Start with Repositories** (Layer 5)
   - Create Interfaces first
   - Then Implementations
   - Register in ServiceProvider

2. **Create Services** (Layer 4)
   - Use Repositories
   - Add business logic
   - Handle transactions

3. **Update Controllers** (Layer 2)
   - Remove direct Model access
   - Use Services instead
   - Keep thin - just HTTP handling

4. **Update Models** (Layer 6)
   - Add Enums
   - Add Traits
   - Add scopes

---

## 📚 Reference

- **Complete Example:** See `SAMPLE_IMPLEMENTATION_STUDENT.md` for full implementation
- **Architecture Guide:** See `ARCHITECTURE_MIGRATION_GUIDE.md` for details
- **Status:** See `FOLDER_STRUCTURE_STATUS.md` for progress

---

## ✅ Summary

**Foundation Complete:**
- ✅ Middleware (3/3)
- ✅ Form Requests (25/25)
- ✅ Controllers exist (16/16) - need updates
- ✅ Models exist (15/18) - need updates

**Need to Create:**
- ❌ Repositories (0/20)
- ❌ Services (0/11)

**Next Step:** Start creating Repositories following the StudentRepository example!

