<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\SubscriberListController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\PayrollController;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Guest routes (login/register)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // CMS Module - Posts
    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('index');
        Route::get('/create', [PostController::class, 'create'])->name('create');
        Route::post('/', [PostController::class, 'store'])->name('store');
        Route::get('/{post}', [PostController::class, 'show'])->name('show');
        Route::get('/{post}/edit', [PostController::class, 'edit'])->name('edit');
        Route::put('/{post}', [PostController::class, 'update'])->name('update');
        Route::delete('/{post}', [PostController::class, 'destroy'])->name('destroy');
        Route::post('/{post}/publish', [PostController::class, 'publish'])->name('publish');
        Route::post('/{post}/draft', [PostController::class, 'draft'])->name('draft');
    });

    // Email Marketing Module - Campaigns
    Route::prefix('campaigns')->name('campaigns.')->group(function () {
        Route::get('/', [CampaignController::class, 'index'])->name('index');
        Route::get('/create', [CampaignController::class, 'create'])->name('create');
        Route::post('/', [CampaignController::class, 'store'])->name('store');
        Route::get('/{campaign}', [CampaignController::class, 'show'])->name('show');
        Route::get('/{campaign}/edit', [CampaignController::class, 'edit'])->name('edit');
        Route::put('/{campaign}', [CampaignController::class, 'update'])->name('update');
        Route::delete('/{campaign}', [CampaignController::class, 'destroy'])->name('destroy');
        Route::post('/{campaign}/send', [CampaignController::class, 'send'])->name('send');
    });

    // Email Marketing Module - Subscriber Lists
    Route::prefix('subscriber-lists')->name('subscriber-lists.')->group(function () {
        Route::get('/', [SubscriberListController::class, 'index'])->name('index');
        Route::get('/create', [SubscriberListController::class, 'create'])->name('create');
        Route::post('/', [SubscriberListController::class, 'store'])->name('store');
        Route::get('/{subscriberList}', [SubscriberListController::class, 'show'])->name('show');
        Route::get('/{subscriberList}/edit', [SubscriberListController::class, 'edit'])->name('edit');
        Route::put('/{subscriberList}', [SubscriberListController::class, 'update'])->name('update');
        Route::delete('/{subscriberList}', [SubscriberListController::class, 'destroy'])->name('destroy');
    });

    // Email Marketing Module - Templates
    Route::prefix('email-templates')->name('email-templates.')->group(function () {
        Route::get('/', [EmailTemplateController::class, 'index'])->name('index');
        Route::get('/create', [EmailTemplateController::class, 'create'])->name('create');
        Route::post('/', [EmailTemplateController::class, 'store'])->name('store');
        Route::get('/{emailTemplate}', [EmailTemplateController::class, 'show'])->name('show');
        Route::get('/{emailTemplate}/edit', [EmailTemplateController::class, 'edit'])->name('edit');
        Route::put('/{emailTemplate}', [EmailTemplateController::class, 'update'])->name('update');
        Route::delete('/{emailTemplate}', [EmailTemplateController::class, 'destroy'])->name('destroy');
    });

    // HRM Module - Employees
    Route::prefix('employees')->name('employees.')->group(function () {
        Route::get('/', [EmployeeController::class, 'index'])->name('index');
        Route::get('/create', [EmployeeController::class, 'create'])->name('create');
        Route::post('/', [EmployeeController::class, 'store'])->name('store');
        Route::get('/{employee}', [EmployeeController::class, 'show'])->name('show');
        Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])->name('edit');
        Route::put('/{employee}', [EmployeeController::class, 'update'])->name('update');
        Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->name('destroy');
    });

    // HRM Module - Attendance
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::post('/clock-in', [AttendanceController::class, 'clockIn'])->name('clock-in');
        Route::post('/clock-out', [AttendanceController::class, 'clockOut'])->name('clock-out');
        Route::get('/my-records', [AttendanceController::class, 'myRecords'])->name('my-records');
    });

    // HRM Module - Leave Requests
    Route::prefix('leave-requests')->name('leave-requests.')->group(function () {
        Route::get('/', [LeaveRequestController::class, 'index'])->name('index');
        Route::get('/create', [LeaveRequestController::class, 'create'])->name('create');
        Route::post('/', [LeaveRequestController::class, 'store'])->name('store');
        Route::get('/{leaveRequest}', [LeaveRequestController::class, 'show'])->name('show');
        Route::post('/{leaveRequest}/approve', [LeaveRequestController::class, 'approve'])->name('approve');
        Route::post('/{leaveRequest}/reject', [LeaveRequestController::class, 'reject'])->name('reject');
    });

    // HRM Module - Payroll
    Route::prefix('payrolls')->name('payrolls.')->group(function () {
        Route::get('/', [PayrollController::class, 'index'])->name('index');
        Route::get('/create', [PayrollController::class, 'create'])->name('create');
        Route::post('/', [PayrollController::class, 'store'])->name('store');
        Route::get('/{payroll}', [PayrollController::class, 'show'])->name('show');
        Route::get('/{payroll}/edit', [PayrollController::class, 'edit'])->name('edit');
        Route::put('/{payroll}', [PayrollController::class, 'update'])->name('update');
        Route::delete('/{payroll}', [PayrollController::class, 'destroy'])->name('destroy');
        Route::post('/{payroll}/generate', [PayrollController::class, 'generate'])->name('generate');
    });
});
