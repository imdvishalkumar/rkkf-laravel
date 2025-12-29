# Step-by-Step Implementation Guide
## Following Architecture Flow

This guide walks you through implementing each layer step-by-step.

---

## 🎯 Implementation Steps

### Step 1: Create Repository Interfaces

**Location:** `app/Repositories/Contracts/`

**Purpose:** Define contracts for data access

**Files to Create:**
1. `StudentRepositoryInterface.php`
2. `FeeRepositoryInterface.php`
3. `AttendanceRepositoryInterface.php`
4. `BranchRepositoryInterface.php`
5. `ProductRepositoryInterface.php`
6. `OrderRepositoryInterface.php`
7. `ExamRepositoryInterface.php`
8. `EventRepositoryInterface.php`
9. `UserRepositoryInterface.php`
10. `CouponRepositoryInterface.php`

**Example Pattern:**
```php
interface StudentRepositoryInterface
{
    public function all(array $filters = []): Collection;
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function find(int $id): ?Student;
    public function create(array $data): Student;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
```

**Reference:** See `SAMPLE_IMPLEMENTATION_STUDENT.md` for complete example

---

### Step 2: Create Repository Implementations

**Location:** `app/Repositories/`

**Purpose:** Implement database queries

**Files to Create:**
1. `StudentRepository.php`
2. `FeeRepository.php`
3. `AttendanceRepository.php`
4. `BranchRepository.php`
5. `ProductRepository.php`
6. `OrderRepository.php`
7. `ExamRepository.php`
8. `EventRepository.php`
9. `UserRepository.php`
10. `CouponRepository.php`

**Example Pattern:**
```php
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
        return $query->get();
    }
}
```

**Reference:** See `SAMPLE_IMPLEMENTATION_STUDENT.md` for complete example

---

### Step 3: Register Repositories in Service Provider

**File:** `app/Providers/RepositoryServiceProvider.php`

**Update:**
```php
public function register(): void
{
    $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
    $this->app->bind(FeeRepositoryInterface::class, FeeRepository::class);
    // ... add all repositories
}
```

---

### Step 4: Create Service Classes

**Location:** `app/Services/`

**Purpose:** Business logic layer

**Files to Create:**
1. `StudentService.php`
2. `FeeService.php`
3. `AttendanceService.php`
4. `BranchService.php`
5. `ProductService.php`
6. `OrderService.php`
7. `ExamService.php`
8. `EventService.php`
9. `UserService.php`
10. `CouponService.php`
11. `PaymentService.php`

**Example Pattern:**
```php
class StudentService
{
    protected $studentRepository;
    protected $feeRepository;

    public function __construct(
        StudentRepositoryInterface $studentRepository,
        FeeRepositoryInterface $feeRepository
    ) {
        $this->studentRepository = $studentRepository;
        $this->feeRepository = $feeRepository;
    }

    public function createStudent(array $data): array
    {
        DB::beginTransaction();
        try {
            // Business logic here
            $student = $this->studentRepository->create($data);
            // More business logic
            DB::commit();
            return ['student' => $student, 'message' => 'Success'];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
```

**Reference:** See `SAMPLE_IMPLEMENTATION_STUDENT.md` for complete example

---

### Step 5: Update Controllers to Use Services

**Location:** `app/Http/Controllers/`

**What to Change:**
- Remove direct Model access
- Inject Service in constructor
- Call Service methods instead of Model methods

**Before:**
```php
public function store(Request $request)
{
    $student = Student::create($request->all()); // ❌ Direct model access
    return redirect()->route('students.index');
}
```

**After:**
```php
protected $studentService;

public function __construct(StudentService $studentService)
{
    $this->studentService = $studentService;
}

public function store(StoreStudentRequest $request)
{
    $result = $this->studentService->createStudent($request->validated()); // ✅ Use service
    return redirect()->route('students.index')
        ->with('success', $result['message']);
}
```

---

### Step 6: Update Models

**Location:** `app/Models/`

**What to Add:**
- Use Enums for status/role fields
- Add Traits (HasStatus, HasBranchAccess)
- Add scopes
- Define relationships

**Example:**
```php
use App\Enums\StudentStatus;
use App\Traits\HasStatus;

class Student extends Model
{
    use HasStatus;

    protected $casts = [
        'active' => StudentStatus::class,
    ];

    public function scopeActive($query)
    {
        return $query->where('active', StudentStatus::ACTIVE->value);
    }
}
```

---

## 📋 Quick Implementation Checklist

### Phase 1: Repositories
- [ ] Create 10 Repository Interfaces
- [ ] Create 10 Repository Implementations
- [ ] Register in RepositoryServiceProvider
- [ ] Test repository methods

### Phase 2: Services
- [ ] Create 11 Service classes
- [ ] Inject repositories in constructors
- [ ] Implement business logic
- [ ] Handle transactions
- [ ] Test service methods

### Phase 3: Update Controllers
- [ ] Update StudentController
- [ ] Update FeeController
- [ ] Update AttendanceController
- [ ] Update BranchController
- [ ] Update ProductController
- [ ] Update OrderController
- [ ] Update ExamController
- [ ] Update EventController
- [ ] Update UserController
- [ ] Update CouponController
- [ ] Update API Controllers

### Phase 4: Update Models
- [ ] Add Enums to casts
- [ ] Add Traits
- [ ] Add scopes
- [ ] Verify relationships

---

## 🚀 Start Here

1. **Read:** `SAMPLE_IMPLEMENTATION_STUDENT.md` - Complete working example
2. **Create:** StudentRepositoryInterface and StudentRepository
3. **Create:** StudentService
4. **Update:** StudentController to use StudentService
5. **Test:** Verify everything works
6. **Repeat:** Apply same pattern to other modules

---

## 📚 Reference Files

- **Complete Example:** `SAMPLE_IMPLEMENTATION_STUDENT.md`
- **Architecture Flow:** `ARCHITECTURE_FLOW_IMPLEMENTATION.md`
- **Status Tracking:** `FOLDER_STRUCTURE_STATUS.md`

---

**Ready to start? Begin with Step 1: Create Repository Interfaces!**

