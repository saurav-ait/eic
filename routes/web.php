<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PassportController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\JobController;
use App\Models\JobCategory;
use App\Models\Passport;
use App\Models\Services;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ActivityTypeController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\TextTemplateController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AgentController;


/*
|--------------------------------------------------------------------------
| SHARED CSRF MIDDLEWARE STACK
|--------------------------------------------------------------------------
*/

$csrfMiddleware = [
    EncryptCookies::class,
    AddQueuedCookiesToResponse::class,
    StartSession::class,
    ShareErrorsFromSession::class,
    VerifyCsrfToken::class,
];

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/paginationvendorpublish', function () {
    try {
        Artisan::call('vendor:publish', [
            '--tag' => 'laravel-pagination'
        ]);
        return "Pagination views published successfully!";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});
Route::get('/optimizeclear', function () {
    try {
        Artisan::call('optimize:clear');
        return "Optimized!";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});
Route::get('/viewclear', function () {
    try {
        Artisan::call('view:clear');
        return "Cleared!";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});
Route::get('/cacheclear', function () {
    try {
        Artisan::call('cache:clear');
        return "Cleared!";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});
Route::get('/configclear', function () {
    try {
        Artisan::call('config:clear');
        return "Cleared!";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/migratefresh', function () {
    try {
        Artisan::call('migrate:fresh', [
            '--force' => true
        ]);
        return "Migrated!";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::middleware($csrfMiddleware)->group(function () {

    Route::get('/', [UserController::class, 'index'])->name('home');
    Route::get('/services', [UserController::class, 'services'])->name('services');
    Route::get('/services/{serviceSlug}', [UserController::class, 'serviceCategories'])
    ->name('service.categories');
    Route::get('/services/{serviceSlug}/{categorySlug}', [UserController::class, 'categorySubcategories'])->name('category.subcategories');
    Route::get('/services/{serviceSlug}/{categorySlug}/{subcategorySlug}', [UserController::class, 'subcategoryJobs'])
    ->name('subcategory.jobs');
    Route::get('/services/{serviceSlug}/{categorySlug}/{subcategorySlug}/{positionSlug?}', [UserController::class, 'servicePosition'])->name('service.position');
    Route::get('/work-visa', [UserController::class, 'workVisa'])->name('work-visa');
    Route::get('/drivers', [UserController::class, 'drivers'])->name('drivers');
    Route::get('/countries', [UserController::class, 'countries'])->name('countries');
    Route::get('/jobs', [UserController::class, 'jobs'])->name('jobs');
    Route::get('/contact', [UserController::class, 'contact'])->name('contact');
    Route::get('/assessment', [UserController::class, 'assessment'])->name('assessment');
    Route::get('/work-type/{slug?}', [UserController::class, 'serviceworktype'])->name('service-work-type');
    Route::get('/ahmed-videos', [UserController::class, 'ahmedVideos'])->name('ahmed-videos');

    /*
    |--------------------------------------------------------------------------
    | STATIC PAGES
    |--------------------------------------------------------------------------
    */

    Route::get('/terms', fn() => view('terms'))->name('terms');
    Route::get('/privacy', fn() => view('privacy'))->name('privacy');

});

/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/

Route::middleware(array_merge($csrfMiddleware, ['guestOnly']))->group(function () {

    Route::get('/login', [UserController::class, 'login'])->name('login');
    Route::post('/login', [UserController::class, 'auth_login'])->name('auth-login');

    Route::get('/register', [UserController::class, 'register'])->name('register');
    Route::post('/register', [UserController::class, 'auth_register'])->name('auth-register');

});

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(array_merge($csrfMiddleware, ['checkLogin']))->group(function () {

    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | ADMIN PANEL
    |--------------------------------------------------------------------------
    */

    Route::prefix('admin')->middleware('role:Admin')->group(function () {

        /*
        |---------------- ACCOUNTS ----------------|
        */
        Route::get('accounts', [AccountController::class, 'index'])->name('accounts.index');
        Route::post('accounts', [AccountController::class, 'store'])->name('accounts.store');
        Route::put('accounts/{id}', [AccountController::class, 'update'])->name('accounts.update');
        Route::delete('accounts/{id}', [AccountController::class, 'destroy'])->name('accounts.destroy');

        Route::get('accounts/report', [AccountController::class, 'monthlyReport'])->name('accounts.report');
        Route::get('accounts/export/excel', [AccountController::class, 'exportExcel'])->name('accounts.export.excel');
        Route::post('accounts/import', [AccountController::class, 'importExcel'])->name('accounts.import');
        Route::get('accounts/export/pdf', [AccountController::class, 'exportPDF'])->name('accounts.export.pdf');
        Route::get('accounts/ledger/{vendor}/pdf', [AccountController::class, 'exportLedgerPDF'])->name('accounts.ledger.pdf');
        Route::get('accounts/ledger/{vendor}', [AccountController::class, 'ledger'])->name('accounts.ledger');
        Route::get('accounts/vendors', [AccountController::class, 'vendorlist'])->name('accounts.vendors');

        /*
        |---------------- USER MANAGEMENT ----------------|
        */
        Route::get('/manage-users', [DashboardController::class, 'manageUsers'])->name('manage-users');
        Route::post('/update-role/{user}', [DashboardController::class, 'updateRole'])->name('update.role');

        /*
        |---------------- PASSPORT ----------------|
        */
        Route::get('passports', [PassportController::class, 'index'])->name('passports.index');
        Route::get('passports/create', [PassportController::class, 'create'])->name('passports.create');
        Route::post('passports', [PassportController::class, 'store'])->name('passports.store');
        Route::get('passports/{passport}', [PassportController::class, 'show'])->name('passports.show');
        Route::delete('passports/{passport}', [PassportController::class, 'destroy'])->name('passports.destroy');

        /*
        |---------------- DOCUMENTS ----------------|
        */
        Route::get('passports/{passport}/documents/create', [DocumentController::class, 'create'])->name('documents.create');
        Route::post('passports/{passport}/documents', [DocumentController::class, 'store'])->name('documents.store');
        Route::delete('documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

        /*
        |---------------- VIDEOS ----------------|
        */
        Route::get('passports/{passport}/videos/create', [VideoController::class, 'create'])->name('videos.create');
        Route::post('passports/{passport}/videos', [VideoController::class, 'store'])->name('videos.store');
        Route::delete('videos/{video}', [VideoController::class, 'destroy'])->name('videos.destroy');

        /*
        |---------------- JOB MANAGEMENT ----------------|
        */
        Route::get('/jobs/manage', [JobController::class, 'index'])->name('jobs.manage');

        /*
        |---------------- SERVICES ----------------|
        */
        Route::get('/jobs/services', [JobController::class, 'services'])->name('jobs.services');
        Route::post('/services', [JobController::class, 'storeService'])->name('services.store');
        Route::put('services/{service}', [JobController::class, 'updateService'])->name('services.update');
        Route::delete('services/{service}', [JobController::class, 'destroyService'])->name('services.destroy');

        /*
        |---------------- CATEGORY ACTIONS ----------------|
        */
        Route::get('/jobs/categories', function () {
            $categories = JobCategory::with('subcategories', 'service')->get();
            $services = Services::all();
            return view('client.jobs.categories', compact('categories', 'services'));
        })->name('jobs.categories');
        Route::post('/jobs/category', [JobController::class, 'storeCategory'])->name('jobs.category.store');
        Route::put('/jobs/category/{id}', [JobController::class, 'updateCategory'])->name('jobs.category.update');
        Route::delete('/jobs/category/{id}', [JobController::class, 'deleteCategory'])->name('jobs.category.delete');

        /*
        |---------------- SUBCATEGORY ACTIONS ----------------|
        */
        Route::post('/jobs/subcategory', [JobController::class, 'storeSubcategory'])->name('jobs.subcategory.store');
        Route::delete('/jobs/subcategory/{id}', [JobController::class, 'deleteSubcategory'])->name('jobs.subcategory.delete');

        /*
        |---------------- PASSPORT MANAGEMENT ----------------|
        */
        Route::get('/jobs/passport-status', [JobController::class, 'passportStatus'])->name('jobs.passport.status');
        Route::post('/jobs/passport-status', [JobController::class, 'updatePassportStatus'])->name('jobs.passport.status.update');

        /*
        |---------------- PASSPORT ASSIGN/UNASSIGN ----------------|
        */
        Route::get('/jobs/assign', function () {
            $categories = JobCategory::with('subcategories.passports', 'service')->get();
            $services = Services::with('categories.subcategories')->get();
            $passports = Passport::all();
            return view('client.jobs.assign', compact('categories', 'services', 'passports'));
        })->name('jobs.assign');
        Route::post('/jobs/assign-passport', [JobController::class, 'assignPassport'])->name('jobs.assign.passport');
        Route::post('/jobs/unassign-passport/{id}', [JobController::class, 'unassignPassport'])->name('jobs.unassign.passport');

        /*
        |---------------- STATUS MANAGEMENT ----------------|
        */
        Route::get('/jobs/status', [JobController::class, 'statusIndex'])->name('jobs.status');
        Route::post('/jobs/status', [JobController::class, 'storeStatus'])->name('jobs.status.store');
        Route::put('/jobs/status/{id}', [JobController::class, 'updateStatus'])->name('jobs.status.update');
        Route::delete('/jobs/status/{id}', [JobController::class, 'deleteStatus'])->name('jobs.status.delete');

        /*
        |---------------- LEADS MANAGEMENT ----------------|
        */
        Route::get('leads', [LeadController::class, 'index'])->name('leads.index');
        Route::post('leads', [LeadController::class, 'store'])->name('leads.store');
        Route::put('leads/{id}', [LeadController::class, 'update'])->name('leads.update');
        Route::delete('leads/{id}', [LeadController::class, 'destroy'])->name('leads.destroy');
        Route::post('leads/import', [LeadController::class, 'import'])->name('leads.import');
        Route::get('leads/export', [LeadController::class, 'export'])->name('leads.export');
        Route::post('leads/{id}/send-email', [LeadController::class, 'sendEmail'])->name('leads.send-email');
        Route::post('leads/{id}/send-text', [LeadController::class, 'sendText'])->name('leads.send-text');
        
        /*
        |---------------- ACTIVITY TYPES MANAGEMENT ----------------|
        */
        Route::get('activities', [ActivityTypeController::class, 'index'])->name('activities.index');
        Route::post('activities', [ActivityTypeController::class, 'store'])->name('activities.store');
        Route::put('activities/{id}', [ActivityTypeController::class, 'update'])->name('activities.update');
        Route::delete('activities/{id}', [ActivityTypeController::class, 'destroy'])->name('activities.destroy');

        /*
        |---------------- EMAIL TEMPLATES MANAGEMENT ----------------|
        */
        Route::get('email-templates', [EmailTemplateController::class, 'index'])->name('email-templates.index');
        Route::post('email-templates', [EmailTemplateController::class, 'store'])->name('email-templates.store');
        Route::put('email-templates/{id}', [EmailTemplateController::class, 'update'])->name('email-templates.update');
        Route::delete('email-templates/{id}', [EmailTemplateController::class, 'destroy'])->name('email-templates.destroy');

        /*
        |---------------- TEXT TEMPLATES MANAGEMENT ----------------|
        */
        Route::get('text-templates', [TextTemplateController::class, 'index'])->name('text-templates.index');
        Route::post('text-templates', [TextTemplateController::class, 'store'])->name('text-templates.store');
        Route::put('text-templates/{id}', [TextTemplateController::class, 'update'])->name('text-templates.update');
        Route::delete('text-templates/{id}', [TextTemplateController::class, 'destroy'])->name('text-templates.destroy');
        
        /*
        |---------------- PROFILE ----------------|
        */
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

        /*
        |------------------AGENT MANAGEMENT------------------|
        */
        Route::get('agents', [AgentController::class, 'index'])->name('agent.index');
        Route::post('agents', [AgentController::class, 'store'])->name('agent.store');
        Route::put('agents/{id}', [AgentController::class, 'update'])->name('agent.update');
        Route::delete('agents/{id}', [AgentController::class, 'destroy'])->name('agent.destroy');

        /*
        |------------------CoUNTRY MANAGEMENT------------------|
        */
        Route::get('countries', [CountryController::class, 'index'])->name('country.index');
        Route::post('countries', [CountryController::class, 'store'])->name('country.store');
        Route::put('countries/{id}', [CountryController::class, 'update'])->name('country.update');
        Route::delete('countries/{id}', [CountryController::class, 'destroy'])->name('country.destroy');

        /*
        |------------------VENDOR MANAGEMENT------------------|
        */
        Route::get('vendors', [VendorController::class, 'index'])->name('vendor.index');
        Route::post('vendors', [VendorController::class, 'store'])->name('vendor.store');
        Route::put('vendors/{id}', [VendorController::class, 'update'])->name('vendor.update');
        Route::delete('vendors/{id}', [VendorController::class, 'destroy'])->name('vendor.destroy');

    });

});