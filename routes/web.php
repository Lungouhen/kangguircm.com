<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - KangGui RCM SaaS Platform
|--------------------------------------------------------------------------
*/

// Public Home Page
Route::get('/', function () {
    return view('welcome');
});

// Public Blog Posts
Route::get('/blog', [App\Http\Controllers\Cms\PostController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [App\Http\Controllers\Cms\PostController::class, 'show'])->name('blog.show');

// Public Pages
Route::get('/page/{slug}', [App\Http\Controllers\Cms\PageController::class, 'show'])->name('page.show');

// Authentication Routes (to be implemented with Laravel Breeze/Jetstream)
// Route::middleware('guest')->group(function () {
//     Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
//     Route::post('/login', [AuthController::class, 'login']);
//     Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
//     Route::post('/register', [AuthController::class, 'register']);
// });

// Protected Dashboard Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // CMS Module Routes
    Route::prefix('cms')->name('cms.')->group(function () {
        Route::resource('posts', App\Http\Controllers\Cms\PostController::class);
        Route::resource('pages', App\Http\Controllers\Cms\PageController::class);
        Route::resource('categories', App\Http\Controllers\Cms\CategoryController::class);
        Route::resource('media', App\Http\Controllers\Cms\MediaController::class);
        
        // Custom actions
        Route::post('posts/{post}/publish', [App\Http\Controllers\Cms\PostController::class, 'publish'])->name('posts.publish');
        Route::post('posts/{post}/draft', [App\Http\Controllers\Cms\PostController::class, 'draft'])->name('posts.draft');
    });

    // Email Marketing Module Routes
    Route::prefix('email')->name('email.')->group(function () {
        Route::resource('lists', App\Http\Controllers\Email\SubscriberListController::class);
        Route::resource('subscribers', App\Http\Controllers\Email\SubscriberController::class);
        Route::resource('templates', App\Http\Controllers\Email\TemplateController::class);
        Route::resource('campaigns', App\Http\Controllers\Email\CampaignController::class);
        
        // Custom actions
        Route::post('campaigns/{campaign}/send', [App\Http\Controllers\Email\CampaignController::class, 'send'])->name('campaigns.send');
        Route::post('subscribers/import', [App\Http\Controllers\Email\SubscriberController::class, 'import'])->name('subscribers.import');
    });

    // HRM Module Routes
    Route::prefix('hrm')->name('hrm.')->group(function () {
        Route::resource('employees', App\Http\Controllers\Hrm\EmployeeController::class);
        Route::resource('attendances', App\Http\Controllers\Hrm\AttendanceController::class);
        Route::resource('leave-requests', App\Http\Controllers\Hrm\LeaveRequestController::class);
        Route::resource('payrolls', App\Http\Controllers\Hrm\PayrollController::class);
        
        // Custom actions
        Route::post('employees/{employee}/clock-in', [App\Http\Controllers\Hrm\AttendanceController::class, 'clockIn'])->name('attendances.clock-in');
        Route::post('employees/{employee}/clock-out', [App\Http\Controllers\Hrm\AttendanceController::class, 'clockOut'])->name('attendances.clock-out');
        Route::post('leave-requests/{leaveRequest}/approve', [App\Http\Controllers\Hrm\LeaveRequestController::class, 'approve'])->name('leave-requests.approve');
        Route::post('leave-requests/{leaveRequest}/reject', [App\Http\Controllers\Hrm\LeaveRequestController::class, 'reject'])->name('leave-requests.reject');
    });

    // User Management (Admin only)
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('index');
        Route::get('/{user}', [App\Http\Controllers\UserController::class, 'show'])->name('show');
        Route::put('/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [App\Http\Controllers\UserController::class, 'destroy'])->name('destroy');
    });
});
