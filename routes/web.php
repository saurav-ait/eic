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

    Route::get('accounts', [AccountController::class, 'index'])->name('accounts.index');
    Route::post('accounts', [AccountController::class, 'store'])->name('accounts.store');
    Route::put('accounts/{id}', [AccountController::class, 'update'])->name('accounts.update');
    Route::delete('accounts/{id}', [AccountController::class, 'destroy'])->name('accounts.destroy');

    Route::get('accounts/report', [AccountController::class, 'monthlyReport'])->name('accounts.report');
    Route::get('accounts/export/excel', [AccountController::class, 'exportExcel'])->name('accounts.export.excel');
    Route::get('accounts/export/pdf', [AccountController::class, 'exportPDF'])->name('accounts.export.pdf');
    Route::get('accounts/ledger/{vendor}', [AccountController::class, 'ledger'])->name('accounts.ledger');
    Route::get('accounts/vendors', [AccountController::class, 'vendorlist'])->name('accounts.vendors');

    /*
    |--------------------------------------------------------------------------
    | ADMIN PANEL
    |--------------------------------------------------------------------------
    */

    Route::prefix('admin')->middleware('role:Admin')->group(function () {

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
        
        /*
        |---------------- PROFILE ----------------|
        */
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
        });

});
