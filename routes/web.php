<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Crm\CompanyController;
use App\Http\Controllers\Crm\ContactController;
use App\Http\Controllers\Crm\PipelineController;
use App\Http\Controllers\Crm\DealController;
use App\Http\Controllers\Hrm\DepartmentController;
use App\Http\Controllers\Hrm\EmployeeController;
use App\Http\Controllers\Hrm\AttendanceController;
use App\Http\Controllers\Hrm\LeaveRequestController;
use App\Http\Controllers\Email\CampaignController;
use App\Http\Controllers\Email\SubscriberController;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Public Routes (SEO Optimized)
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [PublicPageController::class, 'home'])->name('home');

// Dynamic Pages (Must be last to avoid conflict)
Route::get('/{slug}', [PublicPageController::class, 'show'])->name('page.show');

// SEO Assets
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/robots.txt', function () {
    return response("User-agent: *\nDisallow:\nSitemap: " . url('/sitemap.xml'), 200)->header('Content-Type', 'text/plain');
})->name('robots.txt');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // CMS Modules
    Route::resource('settings', SettingController::class)->only(['index', 'update']);
    Route::resource('menus', MenuController::class);
    Route::resource('pages', AdminPageController::class);
    Route::resource('media', MediaController::class)->only(['index', 'store', 'destroy']);
    
    // Blog Module
    Route::resource('posts', PostController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('tags', TagController::class);

    // Audit Logs
    Route::resource('audit-logs', AuditLogController::class)->only(['index', 'show']);

    // CRM Module
    Route::resource('companies', CompanyController::class);
    Route::resource('contacts', ContactController::class);
    Route::resource('pipelines', PipelineController::class);
    Route::resource('deals', DealController::class);

    // HRM Module
    Route::resource('departments', DepartmentController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('attendances', AttendanceController::class);
    Route::resource('leave-requests', LeaveRequestController::class);

    // Email Marketing
    Route::resource('campaigns', CampaignController::class);
    Route::resource('subscribers', SubscriberController::class);
});

// Theme Customizer Routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/theme-customizer', [App\Http\Controllers\Admin\ThemeCustomizerController::class, 'index'])->name('theme.index');
    Route::post('/theme-customizer/update', [App\Http\Controllers\Admin\ThemeCustomizerController::class, 'update'])->name('theme.update');
    Route::get('/theme-customizer/reset', [App\Http\Controllers\Admin\ThemeCustomizerController::class, 'reset'])->name('theme.reset');
});

/*
|--------------------------------------------------------------------------
| Role & Theme Management Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->group(function () {
    // Roles & Permissions
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class)->prefix('admin')->names('admin.roles');
    
    // Theme Customizer
    Route::get('admin/theme', [\App\Http\Controllers\Admin\ThemeController::class, 'edit'])->name('admin.theme.edit');
    Route::post('admin/theme', [\App\Http\Controllers\Admin\ThemeController::class, 'update'])->name('admin.theme.update');
    Route::get('css/dynamic-theme.css', [\App\Http\Controllers\Admin\ThemeController::class, 'preview'])->name('admin.theme.preview');
});
