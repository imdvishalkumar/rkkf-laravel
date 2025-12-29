<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\StudentRepositoryInterface;
use App\Repositories\StudentRepository;
// Add more repository bindings as you create them
// use App\Repositories\Contracts\FeeRepositoryInterface;
// use App\Repositories\FeeRepository;
// use App\Repositories\Contracts\BranchRepositoryInterface;
// use App\Repositories\BranchRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     * 
     * Bind repository interfaces to their implementations.
     * This allows dependency injection to work properly.
     */
    public function register(): void
    {
        // Student Repository
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
        
        // Add more repository bindings as you create them:
        // $this->app->bind(FeeRepositoryInterface::class, FeeRepository::class);
        // $this->app->bind(BranchRepositoryInterface::class, BranchRepository::class);
        // $this->app->bind(AttendanceRepositoryInterface::class, AttendanceRepository::class);
        // $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        // $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        // $this->app->bind(ExamRepositoryInterface::class, ExamRepository::class);
        // $this->app->bind(EventRepositoryInterface::class, EventRepository::class);
        // $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        // $this->app->bind(CouponRepositoryInterface::class, CouponRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

