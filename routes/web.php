<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContentEntryController as AdminContentEntryController;
use App\Http\Controllers\Admin\FormController;
use App\Http\Controllers\Admin\LegalPolicyController as AdminLegalPolicyController;
use App\Http\Controllers\Admin\MarketingLeadController as AdminMarketingLeadController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\NotificationDeliveryController;
use App\Http\Controllers\Admin\PageBuilderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\RedirectController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Admin\ThemeCustomizerController;
use App\Http\Controllers\Admin\WidgetManagerController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Cms\PostController;
use App\Http\Controllers\Crm\ActivityController;
use App\Http\Controllers\Crm\CompanyController;
use App\Http\Controllers\Crm\ContactController;
use App\Http\Controllers\Crm\DealController;
use App\Http\Controllers\Crm\PipelineController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Email\CampaignController;
use App\Http\Controllers\Email\EmailTemplateController;
use App\Http\Controllers\ConsentController;
use App\Http\Controllers\ContentEntryController;
use App\Http\Controllers\Email\SubscriberListController;
use App\Http\Controllers\FormSubmissionController;
use App\Http\Controllers\LegalPolicyController;
use App\Http\Controllers\Hrm\EmployeeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Hrm\PayrollController;
use App\Http\Controllers\MarketingLeadController;
use App\Http\Controllers\PagePreviewController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WhatsAppWebhookController;
use App\Models\Category;
use App\Models\Post;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.index');
Route::get('/robots.txt', fn () => response(
    "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /login\nDisallow: /register\nSitemap: ".url('/sitemap.xml')."\n",
    200,
    ['Content-Type' => 'text/plain; charset=UTF-8', 'Cache-Control' => 'public, max-age=3600']
))->name('robots.txt');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1')->name('login.attempt');

    if (config('app.allow_registration')) {
        Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:3,60')->name('register.store');
    }
});
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::prefix('admin')->name('admin.')->middleware('auth')->group(function (): void {
    Route::get('/', [DashboardController::class, 'index'])->middleware('role:admin')->name('dashboard');
    Route::resource('marketing-leads', AdminMarketingLeadController::class)
        ->only(['index', 'show', 'update'])->middleware('role:admin');

    Route::prefix('cms')->name('cms.')->group(function (): void {
        Route::get('posts', [PostController::class, 'index'])->middleware('permission:posts.view')->name('posts.index');
        Route::get('posts/create', [PostController::class, 'create'])->middleware('permission:posts.create')->name('posts.create');
        Route::post('posts', [PostController::class, 'store'])->middleware('permission:posts.create')->name('posts.store');
        Route::get('posts/{post}', [PostController::class, 'show'])->middleware('permission:posts.view')->name('posts.show');
        Route::get('posts/{post}/edit', [PostController::class, 'edit'])->middleware('permission:posts.edit')->name('posts.edit');
        Route::put('posts/{post}', [PostController::class, 'update'])->middleware('permission:posts.edit')->name('posts.update');
        Route::delete('posts/{post}', [PostController::class, 'destroy'])->middleware('permission:posts.delete')->name('posts.destroy');
        Route::post('posts/{post}/publish', [PostController::class, 'publish'])->middleware('permission:posts.publish')->name('posts.publish');
    });

    Route::resource('audit-logs', AuditLogController::class)->only(['index', 'show'])->middleware('role:admin');
    Route::resource('roles', RoleController::class)->middleware('role:admin');

    Route::get('pages', [PageController::class, 'index'])->middleware('permission:pages.view')->name('pages.index');
    Route::get('pages/create', [PageController::class, 'create'])->middleware('permission:pages.create')->name('pages.create');
    Route::post('pages', [PageController::class, 'store'])->middleware('permission:pages.create')->name('pages.store');
    Route::get('pages/{page}/edit', [PageController::class, 'edit'])->middleware('permission:pages.edit')->name('pages.edit');
    Route::put('pages/{page}', [PageController::class, 'update'])->middleware('permission:pages.edit')->name('pages.update');
    Route::delete('pages/{page}', [PageController::class, 'destroy'])->middleware('permission:pages.delete')->name('pages.destroy');
    Route::get('pages/{page}/revisions', [PageController::class, 'revisions'])->middleware('permission:pages.edit')->name('pages.revisions');
    Route::post('pages/{page}/revisions/{revision}/restore', [PageController::class, 'restoreRevision'])->middleware('permission:pages.edit')->name('pages.revisions.restore');
    Route::post('pages/{page}/restore', [PageController::class, 'restore'])->middleware('permission:pages.delete')->name('pages.restore');
    Route::delete('pages/{page}/force', [PageController::class, 'forceDelete'])->middleware('role:admin')->name('pages.force-delete');
    Route::get('pages/{page}/builder', [PageBuilderController::class, 'edit'])->middleware('permission:pages.edit')->name('pages.builder.edit');
    Route::put('pages/{page}/builder', [PageBuilderController::class, 'update'])->middleware(['permission:pages.edit', 'throttle:60,1'])->name('pages.builder.update');
    Route::post('pages/builder/preview', [PageBuilderController::class, 'previewWidget'])->middleware(['permission:pages.edit', 'throttle:120,1'])->name('pages.builder.preview');

    Route::get('categories', [CategoryController::class, 'index'])->middleware('permission:posts.view')->name('categories.index');
    Route::post('categories', [CategoryController::class, 'store'])->middleware('permission:posts.edit')->name('categories.store');
    Route::put('categories/{category}', [CategoryController::class, 'update'])->middleware('permission:posts.edit')->name('categories.update');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->middleware('permission:posts.delete')->name('categories.destroy');

    Route::get('content', [AdminContentEntryController::class, 'index'])->middleware('permission:pages.view')->name('content.index');
    Route::get('content/create', [AdminContentEntryController::class, 'create'])->middleware('permission:pages.create')->name('content.create');
    Route::post('content', [AdminContentEntryController::class, 'store'])->middleware('permission:pages.create')->name('content.store');
    Route::get('content/{entry}/edit', [AdminContentEntryController::class, 'edit'])->middleware('permission:pages.edit')->name('content.edit');
    Route::put('content/{entry}', [AdminContentEntryController::class, 'update'])->middleware('permission:pages.edit')->name('content.update');
    Route::delete('content/{entry}', [AdminContentEntryController::class, 'destroy'])->middleware('permission:pages.delete')->name('content.destroy');

    Route::get('forms', [FormController::class, 'index'])->middleware('permission:forms.manage')->name('forms.index');
    Route::post('forms', [FormController::class, 'store'])->middleware('permission:forms.manage')->name('forms.store');
    Route::get('forms/{form}/edit', [FormController::class, 'edit'])->middleware('permission:forms.manage')->name('forms.edit');
    Route::put('forms/{form}', [FormController::class, 'update'])->middleware('permission:forms.manage')->name('forms.update');
    Route::delete('forms/{form}', [FormController::class, 'destroy'])->middleware('permission:forms.manage')->name('forms.destroy');
    Route::post('forms/{form}/fields', [FormController::class, 'storeField'])->middleware('permission:forms.manage')->name('forms.fields.store');
    Route::put('form-fields/{field}', [FormController::class, 'updateField'])->middleware('permission:forms.manage')->name('form-fields.update');
    Route::delete('form-fields/{field}', [FormController::class, 'destroyField'])->middleware('permission:forms.manage')->name('form-fields.destroy');
    Route::get('forms/{form}/submissions', [FormController::class, 'submissions'])->middleware('permission:forms.manage')->name('forms.submissions');
    Route::get('forms/{form}/export', [FormController::class, 'export'])->middleware(['permission:forms.manage','throttle:10,1'])->name('forms.export');
    Route::get('form-submissions/{submission}', [FormController::class, 'showSubmission'])->middleware('permission:forms.manage')->name('form-submissions.show');
    Route::put('form-submissions/{submission}', [FormController::class, 'updateSubmission'])->middleware('permission:forms.manage')->name('form-submissions.update');

    Route::get('media', [MediaController::class, 'index'])->middleware('permission:media.upload')->name('media.index');
    Route::post('media', [MediaController::class, 'store'])->middleware(['permission:media.upload', 'throttle:30,1'])->name('media.store');
    Route::delete('media/{medium}', [MediaController::class, 'destroy'])->middleware('permission:media.delete')->name('media.destroy');

    Route::middleware('role:admin')->group(function (): void {
        Route::get('redirects', [RedirectController::class, 'index'])->name('redirects.index');
        Route::post('redirects', [RedirectController::class, 'store'])->name('redirects.store');
        Route::delete('redirects/{redirect}', [RedirectController::class, 'destroy'])->name('redirects.destroy');
        Route::get('legal', [AdminLegalPolicyController::class, 'index'])->name('legal.index');
        Route::post('legal', [AdminLegalPolicyController::class, 'store'])->name('legal.store');
        Route::get('legal/{policy}/edit', [AdminLegalPolicyController::class, 'edit'])->name('legal.edit');
        Route::put('legal/{policy}', [AdminLegalPolicyController::class, 'update'])->name('legal.update');
        Route::delete('legal/{policy}', [AdminLegalPolicyController::class, 'destroy'])->name('legal.destroy');
        Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
        Route::get('analytics/export', [AnalyticsController::class, 'export'])->middleware('throttle:10,1')->name('analytics.export');
        Route::get('notifications', [NotificationDeliveryController::class, 'index'])->name('notifications.index');
        Route::post('notifications/{delivery}/retry', [NotificationDeliveryController::class, 'retry'])->middleware('throttle:10,1')->name('notifications.retry');
        Route::get('settings/site', [SiteSettingController::class, 'edit'])->name('settings.site.edit');
        Route::put('settings/site', [SiteSettingController::class, 'update'])->name('settings.site.update');
        Route::get('menus', [MenuController::class, 'index'])->name('menus.index');
        Route::post('menus', [MenuController::class, 'store'])->name('menus.store');
        Route::post('menus/{menu}/items', [MenuController::class, 'storeItem'])->name('menus.items.store');
        Route::put('menu-items/{menuItem}', [MenuController::class, 'updateItem'])->name('menu-items.update');
        Route::delete('menu-items/{menuItem}', [MenuController::class, 'destroyItem'])->name('menu-items.destroy');
        Route::get('widgets', [WidgetManagerController::class, 'index'])->name('widgets.index');
        Route::put('widgets', [WidgetManagerController::class, 'update'])->name('widgets.update');

        Route::get('theme-customizer', [ThemeCustomizerController::class, 'index'])->name('theme.index');
        Route::post('theme-customizer', [ThemeCustomizerController::class, 'update'])->name('theme.update');
        Route::post('theme-customizer/reset', [ThemeCustomizerController::class, 'reset'])->name('theme.reset');
        Route::get('theme', [ThemeController::class, 'edit'])->name('theme.edit');
        Route::post('theme', [ThemeController::class, 'update'])->name('theme.save');

        Route::resource('companies', CompanyController::class);
        Route::resource('contacts', ContactController::class);
        Route::resource('pipelines', PipelineController::class);
        Route::resource('deals', DealController::class);
        Route::resource('activities', ActivityController::class);
    });

    Route::prefix('hrm')->name('hrm.')->group(function (): void {
        Route::resource('employees', EmployeeController::class)->only(['index', 'show'])->middleware('permission:employees.view');
        Route::post('employees/{employee}/clock-in', [EmployeeController::class, 'clockIn'])->middleware(['permission:attendance.manage', 'throttle:10,1'])->name('attendances.clock-in');
        Route::post('employees/{employee}/clock-out', [EmployeeController::class, 'clockOut'])->middleware(['permission:attendance.manage', 'throttle:10,1'])->name('attendances.clock-out');
        Route::get('payrolls/create', [PayrollController::class, 'create'])->middleware('permission:payroll.manage')->name('payrolls.create');
        Route::resource('payrolls', PayrollController::class)->only(['index', 'show'])->middleware('permission:payroll.view');
        Route::post('payrolls', [PayrollController::class, 'store'])->middleware('permission:payroll.manage')->name('payrolls.store');
        Route::put('payrolls/{payroll}', [PayrollController::class, 'update'])->middleware('permission:payroll.manage')->name('payrolls.update');
        Route::delete('payrolls/{payroll}', [PayrollController::class, 'destroy'])->middleware('permission:payroll.manage')->name('payrolls.destroy');
        Route::post('payrolls/{payroll}/generate', [PayrollController::class, 'generate'])->middleware(['permission:payroll.manage', 'throttle:10,1'])->name('payrolls.generate');
    });

    Route::prefix('email')->name('email.')->group(function (): void {
        Route::get('campaigns', [CampaignController::class, 'index'])->middleware('permission:campaigns.view')->name('campaigns.index');
        Route::get('campaigns/create', [CampaignController::class, 'create'])->middleware('permission:campaigns.create')->name('campaigns.create');
        Route::post('campaigns', [CampaignController::class, 'store'])->middleware('permission:campaigns.create')->name('campaigns.store');
        Route::get('campaigns/{campaign}', [CampaignController::class, 'show'])->middleware('permission:campaigns.view')->name('campaigns.show');
        Route::get('campaigns/{campaign}/edit', [CampaignController::class, 'edit'])->middleware('permission:campaigns.create')->name('campaigns.edit');
        Route::put('campaigns/{campaign}', [CampaignController::class, 'update'])->middleware('permission:campaigns.create')->name('campaigns.update');
        Route::post('campaigns/{campaign}/send', [CampaignController::class, 'send'])->middleware(['permission:campaigns.send', 'throttle:5,1'])->name('campaigns.send');
        Route::resource('templates', EmailTemplateController::class)->middleware('permission:templates.manage');
        Route::resource('lists', SubscriberListController::class)->middleware('permission:lists.manage');
    });
});

Route::get('/css/dynamic-theme.css', [ThemeController::class, 'preview'])->name('theme.preview');
Route::get('/search', SearchController::class)->middleware('throttle:30,1')->name('search');
Route::get('/content/{type}/{slug}', [ContentEntryController::class, 'show'])->name('content.show');
Route::get('/legal/{slug}', [LegalPolicyController::class, 'show'])->name('legal.show');
Route::post('/privacy/consent', [ConsentController::class, 'store'])->middleware('throttle:10,1')->name('consent.store');
Route::post('/forms/{form:slug}/submit', [FormSubmissionController::class, 'store'])->middleware('throttle:5,1')->name('forms.submit');
Route::post('/request-rcm-assessment', [MarketingLeadController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('marketing-leads.store');
Route::get('/webhooks/whatsapp', [WhatsAppWebhookController::class, 'verify'])->middleware('throttle:20,1')->name('webhooks.whatsapp.verify');
Route::post('/webhooks/whatsapp', [WhatsAppWebhookController::class, 'handle'])->middleware('throttle:120,1')->name('webhooks.whatsapp.handle');
Route::get('/preview/pages/{page}', PagePreviewController::class)->middleware(['signed','throttle:30,1'])->name('pages.preview');
Route::get('/blog', fn () => view('public.blog', [
    'posts' => Post::query()->published()->where('noindex', false)->latest('published_at')->paginate((int) SiteSetting::valueOf('blog_per_page', 12)),
    'blogTitle' => SiteSetting::valueOf('blog_title', 'Revenue cycle management insights'),
    'blogDescription' => SiteSetting::valueOf('blog_description', 'Practical guidance for stronger billing operations, fewer denials, and healthier collections.'),
]))->name('posts.index');
Route::get('/blog/{post:slug}', function (Post $post) {
    abort_unless($post->status === Post::STATUS_PUBLISHED && $post->published_at?->isPast(), 404);

    return view('public.post', compact('post'));
})->name('post.show');
Route::get('/category/{category:slug}', fn (Category $category) => view('public.category', [
    'category' => $category,
    'posts' => $category->posts()->published()->paginate(),
]))->name('category.show');

// Public CMS pages must stay last so they cannot shadow named application routes.
Route::get('/{slug}', [PublicPageController::class, 'show'])->name('page.show');
